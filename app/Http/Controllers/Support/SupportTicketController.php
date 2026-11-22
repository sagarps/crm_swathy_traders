<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Http\Requests\Support\StoreTicketRequest;
use App\Http\Requests\Support\StoreTicketMessageRequest;
use App\SupportTicket;
use App\SupportTicketCategory;
use App\SupportTicketMessage;
use App\SupportTicketAttachment;
use App\SupportTicketStatusHistory;
use App\Services\Support\TicketNumberService;
use App\Services\Support\SupportNotificationService;
use App\Customer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class SupportTicketController extends Controller
{
    /**
     * Client Support Dashboard
     */
    public function dashboard()
    {
        $officeId = Auth::user()->office_id;

        $stats = [
            'total' => SupportTicket::where('office_id', $officeId)->count(),
            'open' => SupportTicket::where('office_id', $officeId)->where('status', SupportTicket::STATUS_OPEN)->count(),
            'under_review' => SupportTicket::where('office_id', $officeId)->where('status', SupportTicket::STATUS_UNDER_REVIEW)->count(),
            'waiting_client' => SupportTicket::where('office_id', $officeId)->where('status', SupportTicket::STATUS_WAITING_FOR_CLIENT)->count(),
            'in_progress' => SupportTicket::where('office_id', $officeId)->where('status', SupportTicket::STATUS_IN_PROGRESS)->count(),
            'resolved' => SupportTicket::where('office_id', $officeId)->where('status', SupportTicket::STATUS_RESOLVED)->count(),
            'chargeable' => SupportTicket::where('office_id', $officeId)->where('is_chargeable', true)->count(),
        ];

        $recentTickets = SupportTicket::with(['category', 'creator'])
            ->where('office_id', $officeId)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('support.client.dashboard', compact('stats', 'recentTickets'));
    }

    /**
     * Client Tickets Listing (Office Scoped)
     */
    public function index(Request $request)
    {
        $officeId = Auth::user()->office_id;

        $query = SupportTicket::with(['category', 'creator'])
            ->where('office_id', $officeId);

        // Filters
        if ($request->filled('ticket_number')) {
            $query->where('ticket_number', 'like', '%' . trim($request->ticket_number) . '%');
        }

        if ($request->filled('subject')) {
            $query->where('subject', 'like', '%' . trim($request->subject) . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('is_chargeable')) {
            if ($request->is_chargeable === '1') {
                $query->where('is_chargeable', true);
            } elseif ($request->is_chargeable === '0') {
                $query->where('is_chargeable', false);
            }
        }

        if ($request->filled('created_by')) {
            $query->where('created_by', $request->created_by);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->from_date)));
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', date('Y-m-d', strtotime($request->to_date)));
        }

        $tickets = $query->orderByDesc('created_at')->paginate(15)->appends($request->query());

        $categories = SupportTicketCategory::active()->get();
        $officeUsers = User::where('office_id', $officeId)->where('is_active', 'Y')->get();

        return view('support.client.index', compact('tickets', 'categories', 'officeUsers'));
    }

    /**
     * Create Ticket Form
     */
    public function create()
    {
        $categories = SupportTicketCategory::active()->get();
        $customers = Customer::where('office_id', Auth::user()->office_id)->orderBy('customer_name')->get();

        return view('support.client.create', compact('categories', 'customers'));
    }

    /**
     * Store Ticket
     */
    public function store(StoreTicketRequest $request)
    {
        $officeId = Auth::user()->office_id;

        $ticket = DB::transaction(function () use ($request, $officeId) {
            $ticketNumber = TicketNumberService::generate();

            $ticket = SupportTicket::create([
                'ticket_number' => $ticketNumber,
                'office_id' => $officeId,
                'customer_id' => $request->customer_id,
                'created_by' => Auth::id(),
                'category_id' => $request->category_id,
                'subject' => $request->subject,
                'description' => $request->description,
                'priority' => $request->priority,
                'status' => SupportTicket::STATUS_OPEN,
                'client_approval_status' => SupportTicket::APPROVAL_NOT_REQUIRED,
            ]);

            // Save status history
            SupportTicketStatusHistory::create([
                'ticket_id' => $ticket->id,
                'old_status' => null,
                'new_status' => SupportTicket::STATUS_OPEN,
                'changed_by' => Auth::id(),
                'remarks' => 'Ticket created by client user.',
            ]);

            // Handle file attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    if ($file && $file->isValid()) {
                        $originalName = $file->getClientOriginalName();
                        $mimeType = $file->getClientMimeType();
                        $size = $file->getSize();
                        $storedPath = $file->store('support_attachments/' . $ticket->id, 'local');

                        SupportTicketAttachment::create([
                            'ticket_id' => $ticket->id,
                            'message_id' => null,
                            'uploaded_by' => Auth::id(),
                            'original_filename' => $originalName,
                            'file_path' => $storedPath,
                            'mime_type' => $mimeType,
                            'file_size' => $size,
                        ]);
                    }
                }
            }

            return $ticket;
        });

        SupportNotificationService::ticketCreated($ticket);

        return redirect()->route('support.tickets.show', $ticket->id)
            ->with('success', "Support Ticket {$ticket->ticket_number} created successfully!");
    }

    /**
     * Ticket Details Page (Client View)
     */
    public function show($id)
    {
        $ticket = SupportTicket::with([
            'category',
            'creator',
            'office',
            'customer',
            'approver',
            'clientVisibleMessages.user',
            'clientVisibleMessages.attachments',
            'attachments' => function ($q) {
                $q->whereNull('message_id');
            },
            'statusHistories.user',
            'payments.recorder'
        ])->findOrFail($id);

        Gate::authorize('view', $ticket);

        return view('support.client.show', compact('ticket'));
    }

    /**
     * Post a reply
     */
    public function reply($id, StoreTicketMessageRequest $request)
    {
        $ticket = SupportTicket::findOrFail($id);
        Gate::authorize('reply', $ticket);

        DB::transaction(function () use ($ticket, $request) {
            $message = SupportTicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => Auth::id(),
                'message' => $request->message,
                'message_type' => SupportTicketMessage::TYPE_REPLY,
            ]);

            // Handle attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    if ($file && $file->isValid()) {
                        $storedPath = $file->store('support_attachments/' . $ticket->id, 'local');

                        SupportTicketAttachment::create([
                            'ticket_id' => $ticket->id,
                            'message_id' => $message->id,
                            'uploaded_by' => Auth::id(),
                            'original_filename' => $file->getClientOriginalName(),
                            'file_path' => $storedPath,
                            'mime_type' => $file->getClientMimeType(),
                            'file_size' => $file->getSize(),
                        ]);
                    }
                }
            }

            // If ticket was waiting for client, switch status to UNDER_REVIEW
            if ($ticket->status === SupportTicket::STATUS_WAITING_FOR_CLIENT) {
                $oldStatus = $ticket->status;
                $ticket->status = SupportTicket::STATUS_UNDER_REVIEW;
                $ticket->save();

                SupportTicketStatusHistory::create([
                    'ticket_id' => $ticket->id,
                    'old_status' => $oldStatus,
                    'new_status' => SupportTicket::STATUS_UNDER_REVIEW,
                    'changed_by' => Auth::id(),
                    'remarks' => 'Status automatically updated on client reply.',
                ]);
            }
        });

        SupportNotificationService::clientReplied($ticket, $request->message);

        return redirect()->route('support.tickets.show', $ticket->id)
            ->with('success', 'Your reply has been posted successfully.');
    }

    /**
     * Approve or Reject Quotation
     */
    public function respondQuotation($id, Request $request)
    {
        $ticket = SupportTicket::findOrFail($id);
        Gate::authorize('respondQuotation', $ticket);

        $request->validate([
            'action' => 'required|in:approve,reject',
            'remarks' => 'nullable|string|max:1000',
        ]);

        $action = $request->action;
        $remarks = $request->remarks ?: ($action === 'approve' ? 'Quotation approved by client.' : 'Quotation rejected by client.');

        DB::transaction(function () use ($ticket, $action, $remarks) {
            $oldStatus = $ticket->status;

            if ($action === 'approve') {
                $ticket->client_approval_status = SupportTicket::APPROVAL_APPROVED;
                $ticket->client_approved_by = Auth::id();
                $ticket->client_approved_at = now();
                $ticket->status = SupportTicket::STATUS_APPROVED;
            } else {
                $ticket->client_approval_status = SupportTicket::APPROVAL_REJECTED;
                $ticket->status = SupportTicket::STATUS_REJECTED;
            }

            $ticket->save();

            SupportTicketStatusHistory::create([
                'ticket_id' => $ticket->id,
                'old_status' => $oldStatus,
                'new_status' => $ticket->status,
                'changed_by' => Auth::id(),
                'remarks' => $remarks,
            ]);
        });

        SupportNotificationService::quotationResponded($ticket, $action);

        $msg = $action === 'approve' ? 'Quotation approved successfully!' : 'Quotation has been rejected.';
        return redirect()->route('support.tickets.show', $ticket->id)->with('success', $msg);
    }

    /**
     * Request Reopening of a Resolved Ticket
     */
    public function requestReopen($id, Request $request)
    {
        $ticket = SupportTicket::findOrFail($id);
        Gate::authorize('requestReopen', $ticket);

        $request->validate([
            'reason' => 'required|string|min:5|max:1000',
        ]);

        DB::transaction(function () use ($ticket, $request) {
            $oldStatus = $ticket->status;
            $ticket->status = SupportTicket::STATUS_UNDER_REVIEW;
            $ticket->resolved_at = null;
            $ticket->closed_at = null;
            $ticket->save();

            SupportTicketStatusHistory::create([
                'ticket_id' => $ticket->id,
                'old_status' => $oldStatus,
                'new_status' => SupportTicket::STATUS_UNDER_REVIEW,
                'changed_by' => Auth::id(),
                'remarks' => 'Ticket reopening requested by client. Reason: ' . $request->reason,
            ]);

            // Also post reason as a message
            SupportTicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => Auth::id(),
                'message' => "Reopen Request: " . $request->reason,
                'message_type' => SupportTicketMessage::TYPE_REPLY,
            ]);
        });

        SupportNotificationService::ticketReopened($ticket, $request->reason);

        return redirect()->route('support.tickets.show', $ticket->id)
            ->with('success', 'Ticket reopened successfully. The administrator will review your request.');
    }

    /**
     * Download Protected Attachment
     */
    public function downloadAttachment($id)
    {
        $attachment = SupportTicketAttachment::with(['ticket', 'message'])->findOrFail($id);

        Gate::authorize('downloadAttachment', $attachment);

        if (!Storage::disk('local')->exists($attachment->file_path)) {
            abort(404, 'The requested attachment file was not found on the server.');
        }

        return Storage::disk('local')->download($attachment->file_path, $attachment->original_filename);
    }
}
