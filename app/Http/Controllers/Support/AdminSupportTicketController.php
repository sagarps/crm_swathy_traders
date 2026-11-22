<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Http\Requests\Support\StoreTicketMessageRequest;
use App\Http\Requests\Support\QuotationRequest;
use App\Http\Requests\Support\RecordPaymentRequest;
use App\SupportTicket;
use App\SupportTicketCategory;
use App\SupportTicketMessage;
use App\SupportTicketAttachment;
use App\SupportTicketPayment;
use App\SupportTicketStatusHistory;
use App\Services\Support\SupportNotificationService;
use App\Office;
use App\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class AdminSupportTicketController extends Controller
{
    /**
     * Administrator Dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total' => SupportTicket::count(),
            'open' => SupportTicket::where('status', SupportTicket::STATUS_OPEN)->count(),
            'under_review' => SupportTicket::where('status', SupportTicket::STATUS_UNDER_REVIEW)->count(),
            'waiting_client' => SupportTicket::where('status', SupportTicket::STATUS_WAITING_FOR_CLIENT)->count(),
            'awaiting_approval' => SupportTicket::whereIn('status', [SupportTicket::STATUS_QUOTATION_SENT, SupportTicket::STATUS_AWAITING_APPROVAL])->count(),
            'approved' => SupportTicket::where('status', SupportTicket::STATUS_APPROVED)->count(),
            'in_progress' => SupportTicket::where('status', SupportTicket::STATUS_IN_PROGRESS)->count(),
            'resolved' => SupportTicket::where('status', SupportTicket::STATUS_RESOLVED)->count(),
            'closed' => SupportTicket::where('status', SupportTicket::STATUS_CLOSED)->count(),
            'chargeable' => SupportTicket::where('is_chargeable', true)->count(),
            'estimated_revenue' => (float) SupportTicket::where('is_chargeable', true)->sum('estimated_amount'),
            'actual_revenue' => (float) SupportTicket::where('is_chargeable', true)->sum('actual_amount'),
            'total_collected' => (float) SupportTicketPayment::sum('amount'),
        ];

        $stats['outstanding'] = max(0, $stats['actual_revenue'] - $stats['total_collected']);

        // Recent tickets
        $recentTickets = SupportTicket::with(['office', 'category', 'creator'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Urgent tickets needing attention
        $urgentTickets = SupportTicket::with(['office', 'category', 'creator'])
            ->whereIn('status', [
                SupportTicket::STATUS_OPEN,
                SupportTicket::STATUS_UNDER_REVIEW,
                SupportTicket::STATUS_IN_PROGRESS
            ])
            ->whereIn('priority', [SupportTicket::PRIORITY_HIGH, SupportTicket::PRIORITY_URGENT])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('support.admin.dashboard', compact('stats', 'recentTickets', 'urgentTickets'));
    }

    /**
     * Administrator All Tickets Listing (Global)
     */
    public function index(Request $request)
    {
        $query = SupportTicket::with(['office', 'category', 'creator']);

        if ($request->filled('office_id')) {
            $query->where('office_id', $request->office_id);
        }

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

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->from_date)));
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', date('Y-m-d', strtotime($request->to_date)));
        }

        $tickets = $query->orderByDesc('created_at')->paginate(20)->appends($request->query());

        $offices = Office::orderBy('office_name')->get();
        $categories = SupportTicketCategory::active()->get();

        return view('support.admin.index', compact('tickets', 'offices', 'categories'));
    }

    /**
     * Administrator Ticket Details
     */
    public function show($id)
    {
        $ticket = SupportTicket::with([
            'category',
            'creator',
            'office',
            'customer',
            'approver',
            'messages.user',
            'messages.attachments',
            'attachments' => function ($q) {
                $q->whereNull('message_id');
            },
            'statusHistories.user',
            'payments.recorder'
        ])->findOrFail($id);

        $categories = SupportTicketCategory::active()->get();

        return view('support.admin.show', compact('ticket', 'categories'));
    }

    /**
     * Reply to client
     */
    public function reply($id, StoreTicketMessageRequest $request)
    {
        $ticket = SupportTicket::findOrFail($id);

        DB::transaction(function () use ($ticket, $request) {
            $message = SupportTicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => Auth::id(),
                'message' => $request->message,
                'message_type' => SupportTicketMessage::TYPE_REPLY,
            ]);

            // Handle file attachments
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

            // Optional status transition if requested
            if ($request->filled('set_waiting_client') && $request->set_waiting_client == '1') {
                $oldStatus = $ticket->status;
                $ticket->status = SupportTicket::STATUS_WAITING_FOR_CLIENT;
                $ticket->save();

                SupportTicketStatusHistory::create([
                    'ticket_id' => $ticket->id,
                    'old_status' => $oldStatus,
                    'new_status' => SupportTicket::STATUS_WAITING_FOR_CLIENT,
                    'changed_by' => Auth::id(),
                    'remarks' => 'Status set to Waiting for Client upon administrator reply.',
                ]);
            }
        });

        SupportNotificationService::adminReplied($ticket, $request->message);

        return redirect()->route('admin.support.tickets.show', $ticket->id)
            ->with('success', 'Reply sent to client successfully.');
    }

    /**
     * Add Private Internal Note (Administrator Only)
     */
    public function internalNote($id, Request $request)
    {
        $ticket = SupportTicket::findOrFail($id);

        $request->validate([
            'note' => 'required|string|min:2',
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg,zip,txt|max:10240',
        ]);

        DB::transaction(function () use ($ticket, $request) {
            $message = SupportTicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => Auth::id(),
                'message' => $request->note,
                'message_type' => SupportTicketMessage::TYPE_INTERNAL_NOTE,
            ]);

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
        });

        return redirect()->route('admin.support.tickets.show', $ticket->id)
            ->with('success', 'Private internal note recorded successfully.');
    }

    /**
     * Update Ticket Status
     */
    public function updateStatus($id, Request $request)
    {
        $ticket = SupportTicket::findOrFail($id);

        $request->validate([
            'status' => 'required|in:OPEN,UNDER_REVIEW,WAITING_FOR_CLIENT,QUOTATION_SENT,AWAITING_APPROVAL,APPROVED,IN_PROGRESS,RESOLVED,CLOSED,REJECTED',
            'remarks' => 'nullable|string|max:500',
        ]);

        $newStatus = $request->status;

        // Rule: Chargeable ticket cannot start development without client quotation approval
        if ($newStatus === SupportTicket::STATUS_IN_PROGRESS && $ticket->is_chargeable) {
            if ($ticket->client_approval_status !== SupportTicket::APPROVAL_APPROVED) {
                return redirect()->back()->withErrors([
                    'status' => 'Cannot start work (IN_PROGRESS) on a chargeable ticket until the client approves the quotation.'
                ]);
            }
        }

        DB::transaction(function () use ($ticket, $newStatus, $request) {
            $oldStatus = $ticket->status;
            $ticket->status = $newStatus;

            if ($newStatus === SupportTicket::STATUS_RESOLVED) {
                $ticket->resolved_at = now();
            } elseif ($newStatus === SupportTicket::STATUS_CLOSED) {
                $ticket->closed_at = now();
            }

            $ticket->save();

            SupportTicketStatusHistory::create([
                'ticket_id' => $ticket->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'changed_by' => Auth::id(),
                'remarks' => $request->remarks ?: "Status updated to {$newStatus}.",
            ]);
        });

        SupportNotificationService::statusChanged($ticket, $ticket->getOriginal('status') ?? 'OPEN', $newStatus);

        return redirect()->route('admin.support.tickets.show', $ticket->id)
            ->with('success', "Ticket status updated to {$newStatus}.");
    }

    /**
     * Update Priority
     */
    public function updatePriority($id, Request $request)
    {
        $ticket = SupportTicket::findOrFail($id);

        $request->validate([
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $oldPriority = $ticket->priority;
        $ticket->priority = $request->priority;
        $ticket->save();

        SupportTicketStatusHistory::create([
            'ticket_id' => $ticket->id,
            'old_status' => $ticket->status,
            'new_status' => $ticket->status,
            'changed_by' => Auth::id(),
            'remarks' => "Priority changed from " . ucfirst($oldPriority) . " to " . ucfirst($request->priority) . ".",
        ]);

        return redirect()->route('admin.support.tickets.show', $ticket->id)
            ->with('success', "Ticket priority updated to " . ucfirst($request->priority) . ".");
    }

    /**
     * Save / Send Quotation
     */
    public function saveQuotation($id, QuotationRequest $request)
    {
        $ticket = SupportTicket::findOrFail($id);

        DB::transaction(function () use ($ticket, $request) {
            $isChargeable = (bool) $request->is_chargeable;
            $oldStatus = $ticket->status;

            $ticket->is_chargeable = $isChargeable;

            if ($isChargeable) {
                $ticket->quotation_description = $request->quotation_description;
                $ticket->estimated_amount = $request->estimated_amount;
                $ticket->estimated_completion_date = $request->estimated_completion_date;
                $ticket->client_approval_status = SupportTicket::APPROVAL_PENDING;
                $ticket->status = SupportTicket::STATUS_AWAITING_APPROVAL;

                // Also initialize actual amount if not set
                if (empty($ticket->actual_amount)) {
                    $ticket->actual_amount = $request->estimated_amount;
                }
            } else {
                $ticket->quotation_description = null;
                $ticket->estimated_amount = 0.00;
                $ticket->actual_amount = 0.00;
                $ticket->estimated_completion_date = null;
                $ticket->client_approval_status = SupportTicket::APPROVAL_NOT_REQUIRED;
                // If it was in quotation review, it can move to under review or in progress
                if (in_array($ticket->status, [SupportTicket::STATUS_QUOTATION_SENT, SupportTicket::STATUS_AWAITING_APPROVAL])) {
                    $ticket->status = SupportTicket::STATUS_UNDER_REVIEW;
                }
            }

            $ticket->save();

            $remark = $isChargeable
                ? "Quotation sent to client (Estimated: ₹" . number_format($request->estimated_amount, 2) . ")."
                : "Ticket marked as FREE support.";

            SupportTicketStatusHistory::create([
                'ticket_id' => $ticket->id,
                'old_status' => $oldStatus,
                'new_status' => $ticket->status,
                'changed_by' => Auth::id(),
                'remarks' => $remark,
            ]);
        });

        if ($request->is_chargeable) {
            SupportNotificationService::quotationSent($ticket);
        }

        $msg = $request->is_chargeable ? 'Quotation saved and submitted to client for approval.' : 'Ticket updated as Free Support.';
        return redirect()->route('admin.support.tickets.show', $ticket->id)->with('success', $msg);
    }

    /**
     * Update Actual Amount
     */
    public function updateActualAmount($id, Request $request)
    {
        $ticket = SupportTicket::findOrFail($id);

        $request->validate([
            'actual_amount' => 'required|numeric|min:0',
        ]);

        $oldAmount = $ticket->actual_amount;
        $ticket->actual_amount = $request->actual_amount;
        $ticket->save();

        SupportTicketStatusHistory::create([
            'ticket_id' => $ticket->id,
            'old_status' => $ticket->status,
            'new_status' => $ticket->status,
            'changed_by' => Auth::id(),
            'remarks' => "Actual development amount updated from ₹" . number_format((float) $oldAmount, 2) . " to ₹" . number_format((float) $request->actual_amount, 2) . ".",
        ]);

        return redirect()->route('admin.support.tickets.show', $ticket->id)
            ->with('success', 'Actual development amount updated successfully.');
    }

    /**
     * Record Client Payment
     */
    public function recordPayment($id, RecordPaymentRequest $request)
    {
        $ticket = SupportTicket::findOrFail($id);

        if (!$ticket->is_chargeable) {
            return redirect()->back()->withErrors(['amount' => 'Payments cannot be recorded for Free support tickets.']);
        }

        $outstanding = $ticket->outstanding_amount;
        $amount = (float) $request->amount;

        if ($amount > $outstanding) {
            return redirect()->back()->withErrors([
                'amount' => "Payment amount (₹" . number_format($amount, 2) . ") cannot exceed the current outstanding amount (₹" . number_format($outstanding, 2) . ")."
            ]);
        }

        DB::transaction(function () use ($ticket, $request, $amount) {
            SupportTicketPayment::create([
                'ticket_id' => $ticket->id,
                'amount' => $amount,
                'payment_date' => $request->payment_date,
                'payment_method' => $request->payment_method,
                'reference_number' => $request->reference_number,
                'remarks' => $request->remarks,
                'recorded_by' => Auth::id(),
            ]);

            SupportTicketStatusHistory::create([
                'ticket_id' => $ticket->id,
                'old_status' => $ticket->status,
                'new_status' => $ticket->status,
                'changed_by' => Auth::id(),
                'remarks' => "Payment of ₹" . number_format($amount, 2) . " recorded via {$request->payment_method}.",
            ]);
        });

        return redirect()->route('admin.support.tickets.show', $ticket->id)
            ->with('success', 'Payment recorded successfully.');
    }

    /**
     * Administrator Reports
     */
    public function reports(Request $request)
    {
        $reportType = $request->get('type', 'office_summary');
        $officeId = $request->get('office_id');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');

        $query = SupportTicket::with(['office', 'category', 'creator', 'payments']);

        if ($officeId) {
            $query->where('office_id', $officeId);
        }

        if ($fromDate) {
            $query->whereDate('created_at', '>=', date('Y-m-d', strtotime($fromDate)));
        }

        if ($toDate) {
            $query->whereDate('created_at', '<=', date('Y-m-d', strtotime($toDate)));
        }

        $tickets = $query->orderByDesc('created_at')->get();
        $offices = Office::orderBy('office_name')->get();
        $categories = SupportTicketCategory::active()->get();

        // Aggregations
        $officeWise = SupportTicket::select('office_id', DB::raw('count(*) as count'), DB::raw('sum(actual_amount) as total_actual'))
            ->groupBy('office_id')
            ->with('office')
            ->get();

        $categoryWise = SupportTicket::select('category_id', DB::raw('count(*) as count'))
            ->groupBy('category_id')
            ->with('category')
            ->get();

        $statusWise = SupportTicket::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        $priorityWise = SupportTicket::select('priority', DB::raw('count(*) as count'))
            ->groupBy('priority')
            ->get();

        $paymentsTotal = SupportTicketPayment::sum('amount');
        $actualTotal = SupportTicket::where('is_chargeable', true)->sum('actual_amount');
        $estimatedTotal = SupportTicket::where('is_chargeable', true)->sum('estimated_amount');
        $outstandingTotal = max(0, $actualTotal - $paymentsTotal);

        return view('support.admin.reports', compact(
            'reportType',
            'tickets',
            'offices',
            'categories',
            'officeWise',
            'categoryWise',
            'statusWise',
            'priorityWise',
            'paymentsTotal',
            'actualTotal',
            'estimatedTotal',
            'outstandingTotal',
            'fromDate',
            'toDate',
            'officeId'
        ));
    }

    /**
     * Export Reports to PDF
     */
    public function exportPdf(Request $request)
    {
        $query = SupportTicket::with(['office', 'category', 'creator', 'payments']);

        if ($request->filled('office_id')) {
            $query->where('office_id', $request->office_id);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->from_date)));
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', date('Y-m-d', strtotime($request->to_date)));
        }

        $tickets = $query->orderByDesc('created_at')->get();

        $pdf = Pdf::loadView('support.admin.pdf_report', compact('tickets'));
        return $pdf->download('support-tickets-report-' . date('Y-m-d') . '.pdf');
    }
}
