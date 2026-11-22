@extends('layouts.support_layout')

@section('content')
<section class="content-header">
  <h1>
    Support Administrator Dashboard
    <small>Global overview across all client offices</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Support Admin Dashboard</li>
  </ol>
</section>

<section class="content">
  <!-- Top Row Financial Cards -->
  <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3>{{ $stats['total'] }}</h3>
          <p>Total All Tickets</p>
        </div>
        <div class="icon"><i class="fa fa-ticket"></i></div>
        <a href="{{ route('admin.support.tickets.index') }}" class="small-box-footer">View All <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3>{{ $stats['open'] + $stats['under_review'] }}</h3>
          <p>Pending Review ({{ $stats['open'] }} New)</p>
        </div>
        <div class="icon"><i class="fa fa-bell-o"></i></div>
        <a href="{{ route('admin.support.tickets.index', ['status' => 'OPEN']) }}" class="small-box-footer">Review New <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="small-box bg-orange">
        <div class="inner">
          <h3>{{ $stats['awaiting_approval'] }}</h3>
          <p>Awaiting Client Approval</p>
        </div>
        <div class="icon"><i class="fa fa-clock-o"></i></div>
        <a href="{{ route('admin.support.tickets.index', ['status' => 'AWAITING_APPROVAL']) }}" class="small-box-footer">View Quotations <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="small-box bg-purple">
        <div class="inner">
          <h3>{{ $stats['in_progress'] }}</h3>
          <p>In Progress (Active Dev)</p>
        </div>
        <div class="icon"><i class="fa fa-cogs"></i></div>
        <a href="{{ route('admin.support.tickets.index', ['status' => 'IN_PROGRESS']) }}" class="small-box-footer">View In Progress <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
  </div>

  <!-- Second Row Financial Statistics -->
  <div class="row">
    <div class="col-md-4 col-sm-6 col-xs-12">
      <div class="info-box bg-green">
        <span class="info-box-icon"><i class="fa fa-check-circle"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Total Resolved & Closed</span>
          <span class="info-box-number">{{ $stats['resolved'] + $stats['closed'] }}</span>
          <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
          <span class="progress-description">{{ $stats['resolved'] }} Resolved, {{ $stats['closed'] }} Closed</span>
        </div>
      </div>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12">
      <div class="info-box bg-teal">
        <span class="info-box-icon"><i class="fa fa-inr"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Total Collected Revenue</span>
          <span class="info-box-number">₹{{ number_format($stats['total_collected'], 2) }}</span>
          <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
          <span class="progress-description">Actual Billed: ₹{{ number_format($stats['actual_revenue'], 2) }}</span>
        </div>
      </div>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12">
      <div class="info-box {{ $stats['outstanding'] > 0 ? 'bg-red' : 'bg-gray' }}">
        <span class="info-box-icon"><i class="fa fa-exclamation-circle"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Total Outstanding Balance</span>
          <span class="info-box-number">₹{{ number_format($stats['outstanding'], 2) }}</span>
          <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
          <span class="progress-description">Pending client payments</span>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <!-- Urgent Tickets Needing Attention -->
    <div class="col-md-6">
      <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-exclamation-triangle text-red"></i> High Priority & Urgent Tickets</h3>
          <div class="box-tools pull-right">
            <span class="label label-danger">{{ $urgentTickets->count() }} Urgent</span>
          </div>
        </div>
        <div class="box-body table-responsive no-padding">
          <table class="table table-hover table-condensed">
            <thead>
              <tr class="bg-gray-light">
                <th>Ticket #</th>
                <th>Office</th>
                <th>Subject</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse($urgentTickets as $ticket)
                <tr>
                  <td>
                    <a href="{{ route('admin.support.tickets.show', $ticket->id) }}">
                      <strong>{{ $ticket->ticket_number }}</strong>
                    </a>
                  </td>
                  <td><span class="label label-default">{{ $ticket->office->office_name ?? 'N/A' }}</span></td>
                  <td>{{ \Illuminate\Support\Str::limit($ticket->subject, 30) }}</td>
                  <td><span class="label {{ $ticket->priority_badge_class }}">{{ ucfirst($ticket->priority) }}</span></td>
                  <td><span class="label {{ $ticket->status_badge_class }}">{{ str_replace('_', ' ', $ticket->status) }}</span></td>
                  <td>
                    <a href="{{ route('admin.support.tickets.show', $ticket->id) }}" class="btn btn-xs btn-primary btn-flat">
                      Manage
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center text-muted" style="padding: 20px;">
                    No urgent tickets pending.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Recent Global Tickets -->
    <div class="col-md-6">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-history"></i> Recent Ticket Submissions</h3>
          <div class="box-tools pull-right">
            <a href="{{ route('admin.support.tickets.index') }}" class="btn btn-xs btn-default btn-flat">View All</a>
          </div>
        </div>
        <div class="box-body table-responsive no-padding">
          <table class="table table-hover table-condensed">
            <thead>
              <tr class="bg-gray-light">
                <th>Ticket #</th>
                <th>Office</th>
                <th>Category</th>
                <th>Status</th>
                <th>Created</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse($recentTickets as $ticket)
                <tr>
                  <td>
                    <a href="{{ route('admin.support.tickets.show', $ticket->id) }}">
                      <strong>{{ $ticket->ticket_number }}</strong>
                    </a>
                  </td>
                  <td>{{ $ticket->office->office_name ?? 'N/A' }}</td>
                  <td>{{ $ticket->category->name ?? 'N/A' }}</td>
                  <td><span class="label {{ $ticket->status_badge_class }}">{{ str_replace('_', ' ', $ticket->status) }}</span></td>
                  <td>{{ $ticket->created_at->format('d-M H:i') }}</td>
                  <td>
                    <a href="{{ route('admin.support.tickets.show', $ticket->id) }}" class="btn btn-xs btn-default btn-flat">
                      View
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center text-muted" style="padding: 20px;">
                    No tickets registered.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

