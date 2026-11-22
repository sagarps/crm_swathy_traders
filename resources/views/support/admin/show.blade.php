@extends('layouts.support_layout')

@section('content')
<section class="content-header">
  <h1>
    Manage Ticket: <span class="text-primary">{{ $ticket->ticket_number }}</span>
    <small>{{ $ticket->office->office_name ?? 'Client Office' }}</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('admin.support.dashboard') }}">Support Admin</a></li>
    <li><a href="{{ route('admin.support.tickets.index') }}">Tickets</a></li>
    <li class="active">{{ $ticket->ticket_number }}</li>
  </ol>
</section>

<section class="content">
  <!-- Top Navigation & Status Bar -->
  <div class="row" style="margin-bottom: 15px;">
    <div class="col-xs-12">
      <a href="{{ route('admin.support.tickets.index') }}" class="btn btn-default btn-flat">
        <i class="fa fa-arrow-left"></i> Back to All Tickets
      </a>

      <!-- Quick Action Modals Triggers -->
      <div class="pull-right">
        <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#modal-status">
          <i class="fa fa-refresh"></i> Update Status
        </button>
        <button type="button" class="btn btn-default btn-flat" data-toggle="modal" data-target="#modal-priority">
          <i class="fa fa-flag"></i> Priority: {{ ucfirst($ticket->priority) }}
        </button>
        <button type="button" class="btn btn-warning btn-flat" data-toggle="modal" data-target="#modal-quotation">
          <i class="fa fa-file-text-o"></i> Quotation & Charges
        </button>
        @if($ticket->is_chargeable)
          <button type="button" class="btn btn-success btn-flat" data-toggle="modal" data-target="#modal-payment">
            <i class="fa fa-money"></i> Record Payment
          </button>
        @endif
      </div>
    </div>
  </div>

  <div class="row">
    <!-- Left Column: Details, Conversations, Notes, Reply -->
    <div class="col-md-8">

      <!-- Quotation Banner if active -->
      @if($ticket->is_chargeable && $ticket->quotation_description)
        <div class="box box-warning box-solid">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-file-text-o"></i> Active Quotation</h3>
            <div class="box-tools pull-right">
              @if($ticket->client_approval_status === 'approved')
                <span class="label label-success">CLIENT APPROVED</span>
              @elseif($ticket->client_approval_status === 'rejected')
                <span class="label label-danger">CLIENT REJECTED</span>
              @else
                <span class="label label-warning">PENDING CLIENT APPROVAL</span>
              @endif
            </div>
          </div>
          <div class="box-body" style="background: #fff;">
            <p><strong>Quotation Scope:</strong> {{ $ticket->quotation_description }}</p>
            <div class="row text-center">
              <div class="col-xs-4">
                <strong>Estimated:</strong><br>
                <span class="text-green lead">₹{{ number_format((float) $ticket->estimated_amount, 2) }}</span>
              </div>
              <div class="col-xs-4">
                <strong>Completion Date:</strong><br>
                {{ $ticket->estimated_completion_date ? $ticket->estimated_completion_date->format('d-M-Y') : 'N/A' }}
              </div>
              <div class="col-xs-4">
                <strong>Approval Status:</strong><br>
                {{ ucfirst($ticket->client_approval_status) }}
                @if($ticket->client_approved_at)
                  <br><small class="text-muted">{{ $ticket->client_approved_at->format('d-M-Y H:i') }}</small>
                @endif
              </div>
            </div>
          </div>
        </div>
      @endif

      <!-- Ticket Core Box -->
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-info-circle"></i> {{ $ticket->subject }}</h3>
          <div class="box-tools pull-right">
            <span class="label {{ $ticket->status_badge_class }} badge-status">
              {{ str_replace('_', ' ', $ticket->status) }}
            </span>
            <span class="label {{ $ticket->priority_badge_class }} badge-status">
              {{ ucfirst($ticket->priority) }}
            </span>
          </div>
        </div>

        <div class="box-body">
          <h4><i class="fa fa-align-left text-muted"></i> Client Issue / Request Description</h4>
          <div class="well well-sm" style="background-color: #fafbfc; white-space: pre-wrap; font-size: 13.5px;">{{ $ticket->description }}</div>

          <!-- Attachments -->
          @if($ticket->attachments->count() > 0)
            <div style="margin-top: 15px;">
              <h4><i class="fa fa-paperclip text-muted"></i> Client Attachments ({{ $ticket->attachments->count() }})</h4>
              <ul class="mailbox-attachments clearfix">
                @foreach($ticket->attachments as $att)
                  <li>
                    <span class="mailbox-attachment-icon"><i class="fa fa-file-text-o"></i></span>
                    <div class="mailbox-attachment-info">
                      <a href="{{ route('support.attachments.download', $att->id) }}" class="mailbox-attachment-name" title="{{ $att->original_filename }}">
                        <i class="fa fa-paperclip"></i> {{ \Illuminate\Support\Str::limit($att->original_filename, 18) }}
                      </a>
                      <span class="mailbox-attachment-size">
                        {{ $att->formatted_size }}
                        <a href="{{ route('support.attachments.download', $att->id) }}" class="btn btn-default btn-xs pull-right">
                          <i class="fa fa-cloud-download"></i>
                        </a>
                      </span>
                    </div>
                  </li>
                @endforeach
              </ul>
            </div>
          @endif
        </div>
      </div>

      <!-- Unified Conversation Thread (Replies + Private Internal Notes) -->
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-comments"></i> Communications & Activity Thread</h3>
          <div class="box-tools pull-right">
            <span class="text-muted"><i class="fa fa-lock text-yellow"></i> Yellow cards are Developer-only private internal notes</span>
          </div>
        </div>
        <div class="box-body chat" style="max-height: 650px; overflow-y: auto;">
          @forelse($ticket->messages as $msg)
            @if($msg->isInternalNote())
              <!-- Private Internal Note (Visible to Admins only) -->
              <div class="item" style="padding: 12px 10px; background-color: #fffdf2; border-left: 4px solid #f39c12; margin-bottom: 10px; border-radius: 3px;">
                <img src="{{ asset('lte/dist/img/user.png') }}" alt="user image" class="img-circle" style="width: 38px; height: 38px; float: left; margin-right: 10px;">
                <div class="message" style="margin-left: 50px;">
                  <span class="name">
                    <strong>{{ $msg->user->username ?? 'Admin' }}</strong>
                    <span class="label label-warning" style="font-size: 10px;"><i class="fa fa-lock"></i> PRIVATE INTERNAL NOTE</span>
                    <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> {{ $msg->created_at->format('d-M-Y H:i') }}</small>
                  </span>
                  <p style="margin-top: 6px; white-space: pre-wrap; font-size: 13px; color: #555;">{{ $msg->message }}</p>

                  @if($msg->attachments->count() > 0)
                    <div style="margin-top: 6px;">
                      @foreach($msg->attachments as $att)
                        <a href="{{ route('support.attachments.download', $att->id) }}" class="btn btn-xs btn-default btn-flat" style="margin-right: 5px;">
                          <i class="fa fa-paperclip"></i> {{ $att->original_filename }} ({{ $att->formatted_size }})
                        </a>
                      @endforeach
                    </div>
                  @endif
                </div>
              </div>
            @else
              <!-- Standard Public Reply (Visible to Client) -->
              <div class="item" style="padding: 12px 10px; border-bottom: 1px solid #f4f4f4; margin-bottom: 10px;">
                <img src="{{ asset('lte/dist/img/user.png') }}" alt="user image" class="img-circle" style="width: 38px; height: 38px; float: left; margin-right: 10px;">
                <div class="message" style="margin-left: 50px;">
                  <span class="name">
                    <strong>{{ $msg->user->username ?? 'User' }}</strong>
                    @if($msg->user && $msg->user->isSupportAdmin())
                      <span class="label label-primary" style="font-size: 10px;">Developer Reply</span>
                    @else
                      <span class="label label-default" style="font-size: 10px;">Client Reply</span>
                    @endif
                    <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> {{ $msg->created_at->format('d-M-Y H:i') }}</small>
                  </span>
                  <p style="margin-top: 6px; white-space: pre-wrap; font-size: 13px;">{{ $msg->message }}</p>

                  @if($msg->attachments->count() > 0)
                    <div style="margin-top: 6px;">
                      @foreach($msg->attachments as $att)
                        <a href="{{ route('support.attachments.download', $att->id) }}" class="btn btn-xs btn-default btn-flat" style="margin-right: 5px;">
                          <i class="fa fa-paperclip"></i> {{ $att->original_filename }} ({{ $att->formatted_size }})
                        </a>
                      @endforeach
                    </div>
                  @endif
                </div>
              </div>
            @endif
          @empty
            <div class="text-center text-muted" style="padding: 25px;">
              No messages recorded yet.
            </div>
          @endforelse
        </div>
      </div>

      <!-- Action Tabs: Reply to Client OR Add Internal Note -->
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab_reply" data-toggle="tab"><i class="fa fa-reply text-blue"></i> Reply to Client</a></li>
          <li><a href="#tab_note" data-toggle="tab"><i class="fa fa-lock text-yellow"></i> Add Private Internal Note</a></li>
        </ul>
        <div class="tab-content">
          <!-- Tab: Public Reply -->
          <div class="tab-pane active" id="tab_reply">
            <form action="{{ route('admin.support.tickets.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="form-group {{ $errors->has('message') ? 'has-error' : '' }}">
                <textarea name="message" class="form-control" rows="4" placeholder="Type your reply to the client..." required></textarea>
                @if($errors->has('message'))
                  <span class="help-block">{{ $errors->first('message') }}</span>
                @endif
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label><i class="fa fa-paperclip"></i> Attachments</label>
                    <input type="file" name="attachments[]" multiple class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.zip,.txt">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="checkbox" style="margin-top: 25px;">
                    <label>
                      <input type="checkbox" name="set_waiting_client" value="1">
                      Set ticket status to <strong>WAITING FOR CLIENT</strong>
                    </label>
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-primary btn-flat">
                <i class="fa fa-paper-plane"></i> Send Reply to Client
              </button>
            </form>
          </div>

          <!-- Tab: Private Internal Note -->
          <div class="tab-pane" id="tab_note">
            <form action="{{ route('admin.support.tickets.internal_note', $ticket->id) }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="form-group {{ $errors->has('note') ? 'has-error' : '' }}">
                <textarea name="note" class="form-control" rows="4" placeholder="Enter private note for developers/administrators only. Clients will NEVER see this..." required></textarea>
                @if($errors->has('note'))
                  <span class="help-block">{{ $errors->first('note') }}</span>
                @endif
              </div>
              <div class="form-group">
                <label><i class="fa fa-paperclip"></i> Private Attachments</label>
                <input type="file" name="attachments[]" multiple class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.zip,.txt">
              </div>
              <button type="submit" class="btn btn-warning btn-flat">
                <i class="fa fa-lock"></i> Save Private Note
              </button>
            </form>
          </div>
        </div>
      </div>

    </div>

    <!-- Right Column: Meta Info, Financial Ledger, Timeline -->
    <div class="col-md-4">

      <!-- Metadata Box -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-info"></i> Ticket Overview</h3>
        </div>
        <div class="box-body no-padding">
          <table class="table table-striped">
            <tr>
              <th style="width: 40%;">Ticket Number:</th>
              <td><strong>{{ $ticket->ticket_number }}</strong></td>
            </tr>
            <tr>
              <th>Office / Company:</th>
              <td><strong>{{ $ticket->office->office_name ?? 'N/A' }}</strong></td>
            </tr>
            <tr>
              <th>Created By:</th>
              <td>{{ $ticket->creator->username ?? 'N/A' }}</td>
            </tr>
            <tr>
              <th>Category:</th>
              <td>{{ $ticket->category->name ?? 'General' }}</td>
            </tr>
            <tr>
              <th>Priority:</th>
              <td><span class="label {{ $ticket->priority_badge_class }}">{{ ucfirst($ticket->priority) }}</span></td>
            </tr>
            <tr>
              <th>Current Status:</th>
              <td><span class="label {{ $ticket->status_badge_class }}">{{ str_replace('_', ' ', $ticket->status) }}</span></td>
            </tr>
            <tr>
              <th>Type:</th>
              <td>
                @if(is_null($ticket->is_chargeable))
                  <span class="text-muted">Unclassified</span>
                @elseif($ticket->is_chargeable)
                  <span class="label label-warning">Chargeable Request</span>
                @else
                  <span class="label label-success">Free Support</span>
                @endif
              </td>
            </tr>
            <tr>
              <th>Created At:</th>
              <td>{{ $ticket->created_at->format('d-M-Y H:i') }}</td>
            </tr>
            @if($ticket->resolved_at)
              <tr>
                <th>Resolved At:</th>
                <td><span class="text-green">{{ $ticket->resolved_at->format('d-M-Y H:i') }}</span></td>
              </tr>
            @endif
            @if($ticket->closed_at)
              <tr>
                <th>Closed At:</th>
                <td><span class="text-muted">{{ $ticket->closed_at->format('d-M-Y H:i') }}</span></td>
              </tr>
            @endif
          </table>
        </div>
      </div>

      <!-- Financial & Payments Box -->
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-inr"></i> Financials & Collections</h3>
          <div class="box-tools pull-right">
            @if($ticket->is_chargeable)
              <button type="button" class="btn btn-xs btn-success btn-flat" data-toggle="modal" data-target="#modal-payment">
                + Payment
              </button>
            @endif
          </div>
        </div>
        <div class="box-body no-padding">
          <table class="table table-striped">
            <tr>
              <th style="width: 45%;">Estimated Amount:</th>
              <td>₹{{ number_format((float) $ticket->estimated_amount, 2) }}</td>
            </tr>
            <tr>
              <th>Actual Amount:</th>
              <td>
                <span class="lead text-primary" style="font-size: 16px; font-weight: bold;">
                  ₹{{ number_format((float) $ticket->actual_amount, 2) }}
                </span>
                <button type="button" class="btn btn-xs btn-default pull-right" data-toggle="modal" data-target="#modal-actual-amount" title="Edit Actual Amount">
                  <i class="fa fa-pencil"></i>
                </button>
              </td>
            </tr>
            <tr>
              <th>Total Collected:</th>
              <td class="text-green" style="font-weight: bold;">₹{{ number_format((float) $ticket->totalPaid(), 2) }}</td>
            </tr>
            <tr>
              <th>Outstanding Balance:</th>
              <td class="text-red" style="font-weight: bold;">₹{{ number_format((float) $ticket->outstanding_amount, 2) }}</td>
            </tr>
            <tr>
              <th>Payment Status:</th>
              <td>
                <span class="label {{ $ticket->payment_status === 'PAID' ? 'label-success' : ($ticket->payment_status === 'PARTIALLY PAID' ? 'label-warning' : 'label-danger') }}">
                  {{ $ticket->payment_status }}
                </span>
              </td>
            </tr>
          </table>

          <!-- Payment Ledger -->
          @if($ticket->payments->count() > 0)
            <div style="padding: 10px;">
              <h5 style="font-weight: 600; margin-top: 5px;">Payment Entries:</h5>
              <table class="table table-condensed table-bordered" style="font-size: 12px;">
                <thead>
                  <tr class="bg-gray-light">
                    <th>Date</th>
                    <th>Method</th>
                    <th>Ref #</th>
                    <th>Amount</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($ticket->payments as $p)
                    <tr>
                      <td>{{ $p->payment_date->format('d-M-Y') }}</td>
                      <td>{{ $p->payment_method }}</td>
                      <td>{{ $p->reference_number ?: '-' }}</td>
                      <td class="text-green font-bold">₹{{ number_format($p->amount, 2) }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>

      <!-- Status History Box -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-history"></i> Status Audit History</h3>
        </div>
        <div class="box-body" style="padding: 10px;">
          <ul class="timeline" style="margin-bottom: 0;">
            @foreach($ticket->statusHistories as $hist)
              <li>
                <i class="fa fa-circle bg-blue" style="width: 22px; height: 22px; font-size: 10px; line-height: 22px;"></i>
                <div class="timeline-item" style="margin-left: 32px; padding: 6px 10px;">
                  <span class="time" style="font-size: 11px;"><i class="fa fa-clock-o"></i> {{ $hist->created_at->format('d-M H:i') }}</span>
                  <h5 class="timeline-header" style="font-size: 12px; margin: 0; padding: 0; border-bottom: 0;">
                    <strong>{{ str_replace('_', ' ', $hist->new_status) }}</strong>
                    <small>by {{ $hist->user->username ?? 'System' }}</small>
                  </h5>
                  @if($hist->remarks)
                    <div class="timeline-body" style="font-size: 11px; padding: 3px 0 0 0; color: #555;">
                      {{ $hist->remarks }}
                    </div>
                  @endif
                </div>
              </li>
            @endforeach
            <li><i class="fa fa-clock-o bg-gray" style="width: 22px; height: 22px; font-size: 10px; line-height: 22px;"></i></li>
          </ul>
        </div>
      </div>

    </div>
  </div>
</section>
@endsection

@section('modals')
<!-- Modal: Update Status -->
<div class="modal fade" id="modal-status">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('admin.support.tickets.status.update', $ticket->id) }}" method="POST">
        @csrf
        <div class="modal-header bg-primary">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><i class="fa fa-refresh"></i> Transition Ticket Status</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="status">Select New Status <span class="text-danger">*</span></label>
            <select name="status" id="status" class="form-control" required>
              <option value="OPEN" {{ $ticket->status === 'OPEN' ? 'selected' : '' }}>OPEN</option>
              <option value="UNDER_REVIEW" {{ $ticket->status === 'UNDER_REVIEW' ? 'selected' : '' }}>UNDER_REVIEW</option>
              <option value="WAITING_FOR_CLIENT" {{ $ticket->status === 'WAITING_FOR_CLIENT' ? 'selected' : '' }}>WAITING_FOR_CLIENT</option>
              <option value="QUOTATION_SENT" {{ $ticket->status === 'QUOTATION_SENT' ? 'selected' : '' }}>QUOTATION_SENT</option>
              <option value="AWAITING_APPROVAL" {{ $ticket->status === 'AWAITING_APPROVAL' ? 'selected' : '' }}>AWAITING_APPROVAL</option>
              <option value="APPROVED" {{ $ticket->status === 'APPROVED' ? 'selected' : '' }}>APPROVED</option>
              <option value="IN_PROGRESS" {{ $ticket->status === 'IN_PROGRESS' ? 'selected' : '' }}>IN_PROGRESS</option>
              <option value="RESOLVED" {{ $ticket->status === 'RESOLVED' ? 'selected' : '' }}>RESOLVED</option>
              <option value="CLOSED" {{ $ticket->status === 'CLOSED' ? 'selected' : '' }}>CLOSED</option>
              <option value="REJECTED" {{ $ticket->status === 'REJECTED' ? 'selected' : '' }}>REJECTED</option>
            </select>
          </div>
          @if($ticket->is_chargeable && $ticket->client_approval_status !== 'approved')
            <div class="callout callout-warning">
              <small><i class="fa fa-warning"></i> Notice: Development work (IN_PROGRESS) cannot commence until the client approves the quotation.</small>
            </div>
          @endif
          <div class="form-group">
            <label for="status_remarks">Remarks / Reason for Transition (Optional):</label>
            <textarea name="remarks" id="status_remarks" class="form-control" rows="3" placeholder="Optional audit trail remarks..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-save"></i> Save Status</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal: Update Priority -->
<div class="modal fade" id="modal-priority">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <form action="{{ route('admin.support.tickets.priority.update', $ticket->id) }}" method="POST">
        @csrf
        <div class="modal-header bg-gray">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><i class="fa fa-flag"></i> Update Priority</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="modal_priority">Priority Level</label>
            <select name="priority" id="modal_priority" class="form-control" required>
              <option value="low" {{ $ticket->priority === 'low' ? 'selected' : '' }}>Low</option>
              <option value="medium" {{ $ticket->priority === 'medium' ? 'selected' : '' }}>Medium</option>
              <option value="high" {{ $ticket->priority === 'high' ? 'selected' : '' }}>High</option>
              <option value="urgent" {{ $ticket->priority === 'urgent' ? 'selected' : '' }}>Urgent</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-save"></i> Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal: Quotation & Charges Management -->
<div class="modal fade" id="modal-quotation">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('admin.support.tickets.quotation.save', $ticket->id) }}" method="POST">
        @csrf
        <div class="modal-header bg-yellow">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><i class="fa fa-file-text-o"></i> Quotation & Cost Classification</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Billing Classification <span class="text-danger">*</span></label>
            <div>
              <label class="radio-inline">
                <input type="radio" name="is_chargeable" value="0" {{ (!$ticket->is_chargeable) ? 'checked' : '' }} onchange="$('#chargeable-fields').slideUp();"> Free Support
              </label>
              <label class="radio-inline">
                <input type="radio" name="is_chargeable" value="1" {{ ($ticket->is_chargeable) ? 'checked' : '' }} onchange="$('#chargeable-fields').slideDown();"> Chargeable Customization / Development
              </label>
            </div>
          </div>

          <div id="chargeable-fields" style="{{ ($ticket->is_chargeable) ? '' : 'display: none;' }}">
            <div class="form-group">
              <label for="quotation_description">Scope of Work / Quotation Description <span class="text-danger">*</span></label>
              <textarea name="quotation_description" id="quotation_description" class="form-control" rows="4" placeholder="Detail the development work, requirements, and deliverables...">{{ old('quotation_description', $ticket->quotation_description) }}</textarea>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="estimated_amount">Estimated Development Amount (₹) <span class="text-danger">*</span></label>
                  <input type="number" step="0.01" name="estimated_amount" id="estimated_amount" class="form-control" placeholder="0.00" value="{{ old('estimated_amount', $ticket->estimated_amount) }}">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="estimated_completion_date">Expected Completion Date</label>
                  <input type="text" name="estimated_completion_date" id="estimated_completion_date" class="form-control datepicker" placeholder="YYYY-MM-DD" value="{{ old('estimated_completion_date', $ticket->estimated_completion_date ? $ticket->estimated_completion_date->format('Y-m-d') : '') }}">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-warning btn-flat"><i class="fa fa-save"></i> Save & Submit Quotation</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal: Update Actual Amount -->
<div class="modal fade" id="modal-actual-amount">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <form action="{{ route('admin.support.tickets.actual_amount.update', $ticket->id) }}" method="POST">
        @csrf
        <div class="modal-header bg-primary">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><i class="fa fa-edit"></i> Actual Amount</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="actual_amount">Actual Development Charge (₹) <span class="text-danger">*</span></label>
            <input type="number" step="0.01" name="actual_amount" id="actual_amount" class="form-control" value="{{ old('actual_amount', $ticket->actual_amount ?: $ticket->estimated_amount) }}" required min="0">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-save"></i> Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal: Record Payment -->
@if($ticket->is_chargeable)
<div class="modal fade" id="modal-payment">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('admin.support.tickets.payments.store', $ticket->id) }}" method="POST">
        @csrf
        <div class="modal-header bg-green">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><i class="fa fa-money"></i> Record Client Payment</h4>
        </div>
        <div class="modal-body">
          <div class="callout callout-info">
            <p><strong>Actual Billed:</strong> ₹{{ number_format((float) $ticket->actual_amount, 2) }} | <strong>Outstanding:</strong> ₹{{ number_format((float) $ticket->outstanding_amount, 2) }}</p>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="pay_amount">Payment Amount (₹) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="amount" id="pay_amount" class="form-control" max="{{ $ticket->outstanding_amount }}" placeholder="0.00" value="{{ old('amount', $ticket->outstanding_amount) }}" required min="0.01">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="pay_date">Payment Date <span class="text-danger">*</span></label>
                <input type="text" name="payment_date" id="pay_date" class="form-control datepicker" value="{{ date('Y-m-d') }}" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="pay_method">Payment Method <span class="text-danger">*</span></label>
                <select name="payment_method" id="pay_method" class="form-control" required>
                  <option value="Bank Transfer (NEFT/RTGS/IMPS)">Bank Transfer (NEFT/RTGS/IMPS)</option>
                  <option value="UPI / Online">UPI / Online</option>
                  <option value="Cheque">Cheque</option>
                  <option value="Cash">Cash</option>
                  <option value="Other">Other</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="pay_ref">Reference / Transaction #</label>
                <input type="text" name="reference_number" id="pay_ref" class="form-control" placeholder="UTR / Cheque #">
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="pay_remarks">Remarks / Notes</label>
            <textarea name="remarks" id="pay_remarks" class="form-control" rows="2" placeholder="Optional payment receipt notes..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success btn-flat"><i class="fa fa-check"></i> Record Payment</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif
@endsection

@section('scripts')
<script>
  $(document).ready(function() {
    // Explicit tab switching handler
    $('.nav-tabs a').on('click', function(e) {
      e.preventDefault();
      $(this).tab('show');
    });
  });
</script>
@endsection

