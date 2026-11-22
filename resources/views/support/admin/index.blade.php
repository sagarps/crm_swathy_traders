@extends('layouts.support_layout')

@section('content')
<section class="content-header">
  <h1>
    All Support Tickets (Global)
    <small>Manage customer tickets from all client offices</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('admin.support.dashboard') }}">Support Admin</a></li>
    <li class="active">All Tickets</li>
  </ol>
</section>

<section class="content">
  <!-- Global Filters Box -->
  <div class="box box-default">
    <div class="box-header with-border">
      <h3 class="box-title"><i class="fa fa-filter"></i> Search & Filter Tickets</h3>
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
      </div>
    </div>
    <div class="box-body">
      <form action="{{ route('admin.support.tickets.index') }}" method="GET" class="form-horizontal">
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label class="col-sm-4 control-label">Office</label>
              <div class="col-sm-8">
                <select name="office_id" class="form-control input-sm select2" style="width: 100%;">
                  <option value="">-- All Offices --</option>
                  @foreach($offices as $off)
                    <option value="{{ $off->id }}" {{ request('office_id') == $off->id ? 'selected' : '' }}>
                      {{ $off->office_name }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="form-group">
              <label class="col-sm-4 control-label">Ticket #</label>
              <div class="col-sm-8">
                <input type="text" name="ticket_number" class="form-control input-sm" placeholder="TKT-..." value="{{ request('ticket_number') }}">
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="form-group">
              <label class="col-sm-4 control-label">Subject</label>
              <div class="col-sm-8">
                <input type="text" name="subject" class="form-control input-sm" placeholder="Subject keywords..." value="{{ request('subject') }}">
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="form-group">
              <label class="col-sm-4 control-label">Category</label>
              <div class="col-sm-8">
                <select name="category_id" class="form-control input-sm">
                  <option value="">-- All Categories --</option>
                  @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                      {{ $cat->name }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label class="col-sm-4 control-label">Status</label>
              <div class="col-sm-8">
                <select name="status" class="form-control input-sm">
                  <option value="">-- All Statuses --</option>
                  <option value="OPEN" {{ request('status') == 'OPEN' ? 'selected' : '' }}>Open</option>
                  <option value="UNDER_REVIEW" {{ request('status') == 'UNDER_REVIEW' ? 'selected' : '' }}>Under Review</option>
                  <option value="WAITING_FOR_CLIENT" {{ request('status') == 'WAITING_FOR_CLIENT' ? 'selected' : '' }}>Waiting For Client</option>
                  <option value="QUOTATION_SENT" {{ request('status') == 'QUOTATION_SENT' ? 'selected' : '' }}>Quotation Sent</option>
                  <option value="AWAITING_APPROVAL" {{ request('status') == 'AWAITING_APPROVAL' ? 'selected' : '' }}>Awaiting Approval</option>
                  <option value="APPROVED" {{ request('status') == 'APPROVED' ? 'selected' : '' }}>Approved</option>
                  <option value="IN_PROGRESS" {{ request('status') == 'IN_PROGRESS' ? 'selected' : '' }}>In Progress</option>
                  <option value="RESOLVED" {{ request('status') == 'RESOLVED' ? 'selected' : '' }}>Resolved</option>
                  <option value="CLOSED" {{ request('status') == 'CLOSED' ? 'selected' : '' }}>Closed</option>
                  <option value="REJECTED" {{ request('status') == 'REJECTED' ? 'selected' : '' }}>Rejected</option>
                </select>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="form-group">
              <label class="col-sm-4 control-label">Priority</label>
              <div class="col-sm-8">
                <select name="priority" class="form-control input-sm">
                  <option value="">-- All Priorities --</option>
                  <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                  <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                  <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                  <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="form-group">
              <label class="col-sm-4 control-label">Type</label>
              <div class="col-sm-8">
                <select name="is_chargeable" class="form-control input-sm">
                  <option value="">-- Free / Chargeable --</option>
                  <option value="0" {{ request('is_chargeable') === '0' ? 'selected' : '' }}>Free Support</option>
                  <option value="1" {{ request('is_chargeable') === '1' ? 'selected' : '' }}>Chargeable</option>
                </select>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="form-group">
              <label class="col-sm-4 control-label">Date Range</label>
              <div class="col-sm-4" style="padding-right: 2px;">
                <input type="text" name="from_date" class="form-control input-sm datepicker" placeholder="From" value="{{ request('from_date') }}">
              </div>
              <div class="col-sm-4" style="padding-left: 2px;">
                <input type="text" name="to_date" class="form-control input-sm datepicker" placeholder="To" value="{{ request('to_date') }}">
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12 text-right">
            <button type="submit" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-search"></i> Apply Filters</button>
            <a href="{{ route('admin.support.tickets.index') }}" class="btn btn-default btn-sm btn-flat"><i class="fa fa-refresh"></i> Reset</a>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Global Tickets Directory Table -->
  <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title"><i class="fa fa-table"></i> Tickets Directory</h3>
      <div class="box-tools pull-right">
        <a href="{{ route('admin.support.categories.index') }}" class="btn btn-default btn-sm btn-flat" style="margin-right: 5px;">
          <i class="fa fa-tags"></i> Manage Categories
        </a>
        <a href="{{ route('admin.support.reports') }}" class="btn btn-info btn-sm btn-flat">
          <i class="fa fa-bar-chart"></i> Reports
        </a>
      </div>
    </div>

    <div class="box-body table-responsive no-padding">
      <table class="table table-hover table-striped table-ticket-hover">
        <thead>
          <tr class="bg-gray-light">
            <th>Ticket #</th>
            <th>Office / Company</th>
            <th>Created By</th>
            <th>Subject</th>
            <th>Category</th>
            <th>Priority</th>
            <th>Status</th>
            <th>Type</th>
            <th>Estimated (₹)</th>
            <th>Actual (₹)</th>
            <th>Created Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($tickets as $ticket)
            <tr>
              <td>
                <a href="{{ route('admin.support.tickets.show', $ticket->id) }}">
                  <strong>{{ $ticket->ticket_number }}</strong>
                </a>
              </td>
              <td>
                <span class="label label-default">{{ $ticket->office->office_name ?? 'N/A' }}</span>
              </td>
              <td>{{ $ticket->creator->username ?? 'N/A' }}</td>
              <td>
                <a href="{{ route('admin.support.tickets.show', $ticket->id) }}" style="color: #333;">
                  {{ \Illuminate\Support\Str::limit($ticket->subject, 35) }}
                </a>
              </td>
              <td>{{ $ticket->category->name ?? 'N/A' }}</td>
              <td>
                <span class="label {{ $ticket->priority_badge_class }}">
                  {{ ucfirst($ticket->priority) }}
                </span>
              </td>
              <td>
                <span class="label {{ $ticket->status_badge_class }}">
                  {{ str_replace('_', ' ', $ticket->status) }}
                </span>
              </td>
              <td>
                @if(is_null($ticket->is_chargeable))
                  <span class="text-muted">Unclassified</span>
                @elseif($ticket->is_chargeable)
                  <span class="label label-warning">Chargeable</span>
                @else
                  <span class="label label-success">Free</span>
                @endif
              </td>
              <td>
                {{ $ticket->estimated_amount > 0 ? number_format($ticket->estimated_amount, 2) : '-' }}
              </td>
              <td>
                <strong>{{ $ticket->actual_amount > 0 ? number_format($ticket->actual_amount, 2) : '-' }}</strong>
              </td>
              <td>{{ $ticket->created_at->format('d-M-Y') }}</td>
              <td>
                <a href="{{ route('admin.support.tickets.show', $ticket->id) }}" class="btn btn-xs btn-primary btn-flat" title="Manage Ticket">
                  <i class="fa fa-wrench"></i> Manage
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="12" class="text-center text-muted" style="padding: 40px;">
                <i class="fa fa-folder-open-o fa-3x"></i><br><br>
                No tickets found matching the specified filters.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="box-footer clearfix">
      <div class="pull-left" style="margin-top: 5px;">
        Showing {{ $tickets->firstItem() ?? 0 }} to {{ $tickets->lastItem() ?? 0 }} of {{ $tickets->total() }} tickets
      </div>
      <div class="pull-right">
        {{ $tickets->links() }}
      </div>
    </div>
  </div>
</section>
@endsection

