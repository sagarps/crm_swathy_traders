@extends('layouts.support_layout')

@section('content')
<section class="content-header">
  <h1>
    Ticket Details: <span class="text-primary">{{ $ticket->ticket_number }}</span>
    <small>{{ $ticket->subject }}</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('support.dashboard') }}">Support</a></li>
    <li><a href="{{ route('support.tickets.index') }}">Tickets</a></li>
    <li class="active">{{ $ticket->ticket_number }}</li>
  </ol>
</section>

<section class="content">
  <!-- Top Action Bar -->
  <div class="row" style="margin-bottom: 15px;">
    <div class="col-xs-12">
      <a href="{{ route('support.tickets.index') }}" class="btn btn-default btn-flat">
        <i class="fa fa-arrow-left"></i> Back to All Tickets
      </a>

      <!-- Reopen Action if Resolved or Closed -->
      @if(in_array($ticket->status, [\App\SupportTicket::STATUS_RESOLVED, \App\SupportTicket::STATUS_CLOSED]))
        <button type="button" class="btn btn-warning btn-flat pull-right" data-toggle="modal" data-target="#modal-reopen">
          <i class="fa fa-undo"></i> Request Ticket Reopening
        </button>
      @endif
    </div>
  </div>

  <div class="row">
    <!-- Left Column: Ticket Details, Quotation Card, Conversation & Reply Form -->
    <div class="col-md-8">

      <!-- Quotation Approval Card (Highlighted if awaiting client decision) -->
      @if($ticket->is_chargeable && $ticket->quotation_description)
        <div class="box {{ $ticket->client_approval_status === 'pending' ? 'box-warning' : 'box-success' }} box-solid">
          <div class="box-header with-border">
            <h3 class="box-title">
              <i class="fa fa-file-text-o"></i> Quotation & Development Estimate
            </h3>
            <span class="pull-right">
              @if($ticket->client_approval_status === 'approved')
                <span class="label label-success">QUOTATION APPROVED</span>
              @elseif($ticket->client_approval_status === 'rejected')
                <span class="label label-danger">QUOTATION REJECTED</span>
              @else
                <span class="label label-warning">ACTION REQUIRED: AWAITING YOUR APPROVAL</span>
              @endif
            </span>
          </div>
          <div class="box-body">
            <div class="well well-sm" style="background: #fff;">
              <h4>Scope of Work:</h4>
              <p style="white-space: pre-wrap;">{{ $ticket->quotation_description }}</p>
            </div>

            <div class="row">
              <div class="col-sm-4 text-center">
                <h5>Estimated Charge</h5>
                <h3 class="text-green" style="margin-top: 5px;">₹{{ number_format($ticket->estimated_amount, 2) }}</h3>
              </div>
              <div class="col-sm-4 text-center">
                <h5>Target Completion Date</h5>
                <h4 style="margin-top: 5px;">
                  <i class="fa fa-calendar"></i> {{ $ticket->estimated_completion_date ? $ticket->estimated_completion_date->format('d-M-Y') : 'To be confirmed' }}
                </h4>
              </div>
              <div class="col-sm-4 text-center">
                <h5>Approval Status</h5>
                <h4 style="margin-top: 5px;">
                  @if($ticket->client_approval_status === 'approved')
                    <span class="text-green"><i class="fa fa-check"></i> Approved</span>
                    @if($ticket->client_approved_at)
                      <br><small class="text-muted">on {{ $ticket->client_approved_at->format('d-M-Y H:i') }}</small>
                    @endif
                  @elseif($ticket->client_approval_status === 'rejected')
                    <span class="text-red"><i class="fa fa-times"></i> Rejected</span>
                  @else
                    <span class="text-yellow"><i class="fa fa-clock-o"></i> Pending Client Approval</span>
                  @endif
                </h4>
              </div>
            </div>

            <!-- Approval Action Buttons (Only shown when pending) -->
            @if($ticket->client_approval_status === 'pending' || in_array($ticket->status, [\App\SupportTicket::STATUS_AWAITING_APPROVAL, \App\SupportTicket::STATUS_QUOTATION_SENT]))
              <hr style="margin: 15px 0;">
              <div class="text-center">
                <button type="button" class="btn btn-success btn-lg btn-flat" data-toggle="modal" data-target="#modal-approve-quotation" style="margin-right: 15px;">
                  <i class="fa fa-check"></i> APPROVE QUOTATION
                </button>
                <button type="button" class="btn btn-danger btn-lg btn-flat" data-toggle="modal" data-target="#modal-reject-quotation">
                  <i class="fa fa-times"></i> REJECT QUOTATION
                </button>
              </div>
            @endif
          </div>
        </div>
      @endif

      <!-- Ticket Main Box -->
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-info-circle"></i> {{ $ticket->subject }}</h3>
          <div class="box-tools pull-right">
            <span class="label {{ $ticket->status_badge_class }} badge-status">
              {{ str_replace('_', ' ', $ticket->status) }}
            </span>
            <span class="label {{ $ticket->priority_badge_class }} badge-status">
              {{ ucfirst($ticket->priority) }} PRIORITY
            </span>
          </div>
        </div>

        <div class="box-body">
          <!-- Description -->
          <h4 style="margin-top: 0;"><i class="fa fa-align-left text-muted"></i> Original Request / Issue Description</h4>
          <div class="well well-sm" style="background-color: #fafbfc; border-color: #e1e4e8; white-space: pre-wrap; font-size: 13.5px; line-height: 1.6;">{{ $ticket->description }}</div>

          <!-- Attachments Section -->
          @if($ticket->attachments->count() > 0)
            <div style="margin-top: 20px;">
              <h4><i class="fa fa-paperclip text-muted"></i> Attached Files ({{ $ticket->attachments->count() }})</h4>
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
                        <a href="{{ route('support.attachments.download', $att->id) }}" class="btn btn-default btn-xs pull-right" title="Download">
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

      <!-- Conversation History (Visible Client Replies only) -->
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-comments-o"></i> Conversation History</h3>
        </div>
        <div class="box-body chat" style="max-height: 600px; overflow-y: auto;">
          @forelse($ticket->clientVisibleMessages as $msg)
            <div class="item" style="padding: 12px 10px; border-bottom: 1px solid #f4f4f4;">
              <img src="{{ asset('lte/dist/img/user.png') }}" alt="user image" class="img-circle" style="width: 40px; height: 40px; float: left; margin-right: 12px;">
              <p class="message" style="margin-left: 55px;">
                <span class="name">
                  <strong>{{ $msg->user->username ?? 'User' }}</strong>
                  @if($msg->user && $msg->user->isSupportAdmin())
                    <span class="label label-primary" style="font-size: 10px;">Application Developer</span>
                  @else
                    <span class="label label-default" style="font-size: 10px;">Client</span>
                  @endif
                  <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> {{ $msg->created_at->format('d-M-Y H:i') }}</small>
                </span>
                <span style="display: block; margin-top: 6px; white-space: pre-wrap; font-size: 13px;">{{ $msg->message }}</span>
              </p>

              <!-- Message Attachments if any -->
              @if($msg->attachments->count() > 0)
                <div style="margin-left: 55px; margin-top: 8px;">
                  @foreach($msg->attachments as $msgAtt)
                    <a href="{{ route('support.attachments.download', $msgAtt->id) }}" class="btn btn-xs btn-default btn-flat" style="margin-right: 5px;">
                      <i class="fa fa-paperclip"></i> {{ $msgAtt->original_filename }} ({{ $msgAtt->formatted_size }})
                    </a>
                  @endforeach
                </div>
              @endif
            </div>
          @empty
            <div class="text-center text-muted" style="padding: 25px;">
              <i class="fa fa-comment-o fa-2x"></i><br>
              No replies yet. Use the reply box below to communicate with the developer.
            </div>
          @endforelse
        </div>
      </div>

      <!-- Reply Box -->
      @if($ticket->status !== \App\SupportTicket::STATUS_CLOSED)
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-reply"></i> Post a Reply</h3>
          </div>
          <form action="{{ route('support.tickets.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="box-body">
              <div class="form-group {{ $errors->has('message') ? 'has-error' : '' }}">
                <textarea name="message" class="form-control" rows="4" placeholder="Type your reply or additional details here..." required>{{ old('message') }}</textarea>
                @if($errors->has('message'))
                  <span class="help-block">{{ $errors->first('message') }}</span>
                @endif
              </div>
@if(1==2)
              <div class="form-group">
                <label for="attachments"><i class="fa fa-paperclip"></i> Add Attachments (Optional)</label>
                <input type="file" name="attachments[]" multiple class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.zip,.txt">
              </div>
@endif              
            </div>
            <div class="box-footer">
              <button type="submit" class="btn btn-success btn-flat">
                <i class="fa fa-send"></i> Send Reply
              </button>
            </div>
          </form>
        </div>
      @else
        <div class="callout callout-default" style="background: #f7f7f7;">
          <h4><i class="fa fa-lock"></i> Ticket is Closed</h4>
          <p>This ticket is closed. If you require further assistance on this issue, you can request reopening using the button above.</p>
        </div>
      @endif

    </div>

    <!-- Right Column: Meta Information, Financials, Timeline -->
    <div class="col-md-4">

      <!-- Ticket Information Widget -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-info"></i> Ticket Summary</h3>
        </div>
        <div class="box-body no-padding">
          <table class="table table-striped">
            <tr>
              <th style="width: 40%;">Ticket Number:</th>
              <td><strong>{{ $ticket->ticket_number }}</strong></td>
            </tr>
            <tr>
              <th>Status:</th>
              <td><span class="label {{ $ticket->status_badge_class }}">{{ str_replace('_', ' ', $ticket->status) }}</span></td>
            </tr>
            <tr>
              <th>Priority:</th>
              <td><span class="label {{ $ticket->priority_badge_class }}">{{ ucfirst($ticket->priority) }}</span></td>
            </tr>
            <tr>
              <th>Category:</th>
              <td>{{ $ticket->category->name ?? 'General' }}</td>
            </tr>
            <tr>
              <th>Created By:</th>
              <td>{{ $ticket->creator->username ?? 'N/A' }}</td>
            </tr>
            <tr>
              <th>Office:</th>
              <td>{{ $ticket->office->office_name ?? 'N/A' }}</td>
            </tr>
            <tr>
              <th>Created Date:</th>
              <td>{{ $ticket->created_at->format('d-M-Y H:i') }}</td>
            </tr>
            <tr>
              <th>Last Updated:</th>
              <td>{{ $ticket->updated_at->format('d-M-Y H:i') }}</td>
            </tr>
            @if($ticket->resolved_at)
              <tr>
                <th>Resolved Date:</th>
                <td><span class="text-green">{{ $ticket->resolved_at->format('d-M-Y H:i') }}</span></td>
              </tr>
            @endif
          </table>
        </div>
      </div>

      <!-- Financial Information (if Chargeable) -->
      @if($ticket->is_chargeable)
        <div class="box box-warning">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-money"></i> Financial & Payment Summary</h3>
          </div>
          <div class="box-body no-padding">
            <table class="table table-striped">
              <tr>
                <th>Billing Classification:</th>
                <td><span class="label label-warning">Chargeable</span></td>
              </tr>
              <tr>
                <th>Estimated Amount:</th>
                <td>₹{{ number_format((float) $ticket->estimated_amount, 2) }}</td>
              </tr>
              <tr>
                <th>Actual Charge:</th>
                <td>
                  @if($ticket->actual_amount > 0)
                    <strong>₹{{ number_format((float) $ticket->actual_amount, 2) }}</strong>
                  @else
                    <span class="text-muted">Pending final invoice</span>
                  @endif
                </td>
              </tr>
              <tr>
                <th>Total Paid:</th>
                <td class="text-green">₹{{ number_format((float) $ticket->totalPaid(), 2) }}</td>
              </tr>
              <tr>
                <th>Outstanding Amount:</th>
                <td class="text-red"><strong>₹{{ number_format((float) $ticket->outstanding_amount, 2) }}</strong></td>
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

            <!-- Payment entries history -->
            @if($ticket->payments->count() > 0)
              <div style="padding: 10px;">
                <h5 style="font-weight: 600;">Payment Receipts:</h5>
                <ul class="list-unstyled">
                  @foreach($ticket->payments as $pay)
                    <li style="padding: 4px 0; border-bottom: 1px dashed #eee;">
                      <i class="fa fa-check text-green"></i> ₹{{ number_format($pay->amount, 2) }}
                      <small class="text-muted">on {{ $pay->payment_date->format('d-M-Y') }} ({{ $pay->payment_method }})</small>
                      @if($pay->reference_number)
                        <br><small class="text-muted">Ref: {{ $pay->reference_number }}</small>
                      @endif
                    </li>
                  @endforeach
                </ul>
              </div>
            @endif
          </div>
        </div>
      @endif

      <!-- Status History Timeline -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-history"></i> Status History</h3>
        </div>
        <div class="box-body" style="padding: 10px;">
          <ul class="timeline" style="margin-bottom: 0;">
            @foreach($ticket->statusHistories as $history)
              <li>
                <i class="fa fa-circle bg-blue" style="width: 24px; height: 24px; font-size: 11px; line-height: 24px;"></i>
                <div class="timeline-item" style="margin-left: 36px; padding: 6px 10px;">
                  <span class="time" style="font-size: 11px;"><i class="fa fa-clock-o"></i> {{ $history->created_at->format('d-M-Y H:i') }}</span>
                  <h5 class="timeline-header" style="font-size: 12px; margin: 0; padding: 0; border-bottom: 0;">
                    <strong>{{ str_replace('_', ' ', $history->new_status) }}</strong>
                    <small>by {{ $history->user->username ?? 'System' }}</small>
                  </h5>
                  @if($history->remarks)
                    <div class="timeline-body" style="font-size: 11.5px; padding: 4px 0 0 0; color: #555;">
                      {{ $history->remarks }}
                    </div>
                  @endif
                </div>
              </li>
            @endforeach
            <li><i class="fa fa-clock-o bg-gray" style="width: 24px; height: 24px; font-size: 11px; line-height: 24px;"></i></li>
          </ul>
        </div>
      </div>

    </div>
  </div>
</section>
@endsection

@section('modals')
<!-- Approve Quotation Modal -->
<div class="modal fade" id="modal-approve-quotation">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('support.tickets.quotation.respond', $ticket->id) }}" method="POST">
        @csrf
        <input type="hidden" name="action" value="approve">
        <div class="modal-header bg-green">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><i class="fa fa-check"></i> Approve Quotation</h4>
        </div>
        <div class="modal-body">
          <p>You are approving the development quotation for ticket <strong>{{ $ticket->ticket_number }}</strong>:</p>
          <ul>
            <li>Estimated Amount: <strong>₹{{ number_format((float) $ticket->estimated_amount, 2) }}</strong></li>
            <li>Target Completion: <strong>{{ $ticket->estimated_completion_date ? $ticket->estimated_completion_date->format('d-M-Y') : 'To be confirmed' }}</strong></li>
          </ul>
          <div class="form-group">
            <label for="approve_remarks">Approval Remarks / Purchase Order Ref (Optional):</label>
            <textarea name="remarks" id="approve_remarks" class="form-control" rows="3" placeholder="Optional notes or reference number..."></textarea>
          </div>
          <p class="text-muted"><small>By clicking Approve, you authorize the application developer to commence work on this request.</small></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success btn-flat"><i class="fa fa-check"></i> Confirm Approval</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Reject Quotation Modal -->
<div class="modal fade" id="modal-reject-quotation">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('support.tickets.quotation.respond', $ticket->id) }}" method="POST">
        @csrf
        <input type="hidden" name="action" value="reject">
        <div class="modal-header bg-red">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><i class="fa fa-times"></i> Reject Quotation</h4>
        </div>
        <div class="modal-body">
          <p>Please provide a reason for rejecting the quotation for <strong>{{ $ticket->ticket_number }}</strong>:</p>
          <div class="form-group">
            <label for="reject_remarks">Reason for Rejection <span class="text-danger">*</span>:</label>
            <textarea name="remarks" id="reject_remarks" class="form-control" rows="3" required placeholder="State why the quotation is rejected or requested adjustments..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger btn-flat"><i class="fa fa-times"></i> Confirm Rejection</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Reopen Modal -->
<div class="modal fade" id="modal-reopen">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('support.tickets.reopen', $ticket->id) }}" method="POST">
        @csrf
        <div class="modal-header bg-yellow">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><i class="fa fa-undo"></i> Request Ticket Reopening</h4>
        </div>
        <div class="modal-body">
          <p>Please state why you wish to reopen ticket <strong>{{ $ticket->ticket_number }}</strong>:</p>
          <div class="form-group">
            <label for="reopen_reason">Reason for Reopening <span class="text-danger">*</span>:</label>
            <textarea name="reason" id="reopen_reason" class="form-control" rows="4" required placeholder="Explain why the resolution did not fully address the issue..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-warning btn-flat"><i class="fa fa-undo"></i> Submit Reopen Request</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

