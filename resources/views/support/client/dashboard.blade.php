@extends('layouts.support_layout')

@section('content')
<section class="content-header">
  <h1>
    Support Dashboard
    <small>Overview of tickets for your office</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Support Dashboard</li>
  </ol>
</section>

<section class="content">
  <!-- Top Action Button -->
  <div class="row" style="margin-bottom: 15px;">
    <div class="col-xs-12">
      <a href="{{ route('support.tickets.create') }}" class="btn btn-primary btn-flat pull-right">
        <i class="fa fa-plus-circle"></i> Create New Ticket
      </a>
      <a href="{{ route('support.tickets.index') }}" class="btn btn-default btn-flat pull-right" style="margin-right: 10px;">
        <i class="fa fa-list"></i> View All Tickets
      </a>
    </div>
  </div>

  <!-- Summary Cards -->
  <div class="row">
    <!-- Total Tickets -->
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box bg-aqua">
        <span class="info-box-icon"><i class="fa fa-ticket"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Total Tickets</span>
          <span class="info-box-number">{{ $stats['total'] }}</span>
          <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
          <span class="progress-description">All-time office requests</span>
        </div>
      </div>
    </div>

    <!-- Open Tickets -->
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box bg-blue">
        <span class="info-box-icon"><i class="fa fa-folder-open"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Open Tickets</span>
          <span class="info-box-number">{{ $stats['open'] }}</span>
          <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
          <span class="progress-description">Awaiting administrator review</span>
        </div>
      </div>
    </div>

    <!-- Under Review -->
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box bg-purple">
        <span class="info-box-icon"><i class="fa fa-search"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Under Review</span>
          <span class="info-box-number">{{ $stats['under_review'] }}</span>
          <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
          <span class="progress-description">Being evaluated by developer</span>
        </div>
      </div>
    </div>

    <!-- Waiting for Client -->
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box bg-yellow">
        <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Waiting for You</span>
          <span class="info-box-number">{{ $stats['waiting_client'] }}</span>
          <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
          <span class="progress-description">Requires client response/approval</span>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <!-- In Progress -->
    <div class="col-md-4 col-sm-6 col-xs-12">
      <div class="info-box bg-navy">
        <span class="info-box-icon"><i class="fa fa-cogs"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">In Progress</span>
          <span class="info-box-number">{{ $stats['in_progress'] }}</span>
          <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
          <span class="progress-description">Currently in development</span>
        </div>
      </div>
    </div>

    <!-- Resolved -->
    <div class="col-md-4 col-sm-6 col-xs-12">
      <div class="info-box bg-green">
        <span class="info-box-icon"><i class="fa fa-check-circle"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Resolved</span>
          <span class="info-box-number">{{ $stats['resolved'] }}</span>
          <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
          <span class="progress-description">Completed successfully</span>
        </div>
      </div>
    </div>

    <!-- Chargeable Tickets -->
    <div class="col-md-4 col-sm-6 col-xs-12">
      <div class="info-box bg-teal">
        <span class="info-box-icon"><i class="fa fa-money"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Chargeable Requests</span>
          <span class="info-box-number">{{ $stats['chargeable'] }}</span>
          <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
          <span class="progress-description">Customizations & enhancements</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Recent Tickets Table -->
  <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title"><i class="fa fa-clock-o"></i> Recent Tickets</h3>
      <div class="box-tools pull-right">
        <a href="{{ route('support.tickets.index') }}" class="btn btn-sm btn-default btn-flat">View All</a>
      </div>
    </div>
    <div class="box-body table-responsive no-padding">
      <table class="table table-hover table-ticket-hover">
        <thead>
          <tr class="bg-gray-light">
            <th>Ticket #</th>
            <th>Subject</th>
            <th>Category</th>
            <th>Created By</th>
            <th>Priority</th>
            <th>Status</th>
            <th>Created Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentTickets as $ticket)
            <tr>
              <td>
                <a href="{{ route('support.tickets.show', $ticket->id) }}">
                  <strong>{{ $ticket->ticket_number }}</strong>
                </a>
              </td>
              <td>{{ \Illuminate\Support\Str::limit($ticket->subject, 45) }}</td>
              <td><span class="label label-default">{{ $ticket->category->name ?? 'N/A' }}</span></td>
              <td>{{ $ticket->creator->username ?? 'User' }}</td>
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
              <td>{{ $ticket->created_at->format('d-M-Y H:i') }}</td>
              <td>
                <a href="{{ route('support.tickets.show', $ticket->id) }}" class="btn btn-xs btn-primary btn-flat">
                  <i class="fa fa-eye"></i> View
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center text-muted" style="padding: 30px;">
                <i class="fa fa-info-circle fa-2x"></i><br>
                No tickets created yet. Click "Create New Ticket" to raise your first request.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</section>
@endsection

