@extends('layouts.support_layout')

@section('content')
<section class="content-header">
  <h1>
    Support Management Reports
    <small>Analytical, operational, and financial summaries</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('admin.support.dashboard') }}">Support Admin</a></li>
    <li class="active">Reports</li>
  </ol>
</section>

<section class="content">
  <!-- Filter & Report Type Selector Box -->
  <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title"><i class="fa fa-filter"></i> Report Parameters</h3>
      <div class="box-tools pull-right">
        <a href="{{ route('admin.support.reports.export_pdf', request()->all()) }}" class="btn btn-danger btn-sm btn-flat" target="_blank">
          <i class="fa fa-file-pdf-o"></i> Export PDF
        </a>
      </div>
    </div>
    <div class="box-body">
      <form action="{{ route('admin.support.reports') }}" method="GET" class="form-horizontal">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label class="col-sm-4 control-label">Report View</label>
              <div class="col-sm-8">
                <select name="type" class="form-control input-sm">
                  <option value="office_summary" {{ $reportType === 'office_summary' ? 'selected' : '' }}>1. Office-wise Summary</option>
                  <option value="category_summary" {{ $reportType === 'category_summary' ? 'selected' : '' }}>2. Category-wise Summary</option>
                  <option value="status_summary" {{ $reportType === 'status_summary' ? 'selected' : '' }}>3. Status-wise Summary</option>
                  <option value="priority_summary" {{ $reportType === 'priority_summary' ? 'selected' : '' }}>4. Priority-wise Summary</option>
                  <option value="financial_summary" {{ $reportType === 'financial_summary' ? 'selected' : '' }}>5. Financial & Outstanding Collections</option>
                  <option value="detailed_list" {{ $reportType === 'detailed_list' ? 'selected' : '' }}>6. Detailed Tickets List</option>
                </select>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="form-group">
              <label class="col-sm-4 control-label">Office</label>
              <div class="col-sm-8">
                <select name="office_id" class="form-control input-sm">
                  <option value="">-- All Offices --</option>
                  @foreach($offices as $off)
                    <option value="{{ $off->id }}" {{ $officeId == $off->id ? 'selected' : '' }}>{{ $off->office_name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label class="col-sm-4 control-label">Date Range</label>
              <div class="col-sm-4" style="padding-right: 2px;">
                <input type="text" name="from_date" class="form-control input-sm datepicker" placeholder="From Date" value="{{ $fromDate }}">
              </div>
              <div class="col-sm-4" style="padding-left: 2px;">
                <input type="text" name="to_date" class="form-control input-sm datepicker" placeholder="To Date" value="{{ $toDate }}">
              </div>
            </div>
          </div>

          <div class="col-md-1">
            <button type="submit" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-search"></i> Run</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Key Aggregate Metrics -->
  <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box bg-aqua">
        <span class="info-box-icon"><i class="fa fa-ticket"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Filtered Tickets</span>
          <span class="info-box-number">{{ $tickets->count() }}</span>
        </div>
      </div>
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box bg-blue">
        <span class="info-box-icon"><i class="fa fa-calculator"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Estimated Charges</span>
          <span class="info-box-number">₹{{ number_format((float) $estimatedTotal, 2) }}</span>
        </div>
      </div>
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box bg-green">
        <span class="info-box-icon"><i class="fa fa-check"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Total Collected</span>
          <span class="info-box-number">₹{{ number_format((float) $paymentsTotal, 2) }}</span>
        </div>
      </div>
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box {{ $outstandingTotal > 0 ? 'bg-red' : 'bg-gray' }}">
        <span class="info-box-icon"><i class="fa fa-exclamation-triangle"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Total Outstanding</span>
          <span class="info-box-number">₹{{ number_format((float) $outstandingTotal, 2) }}</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Report Data Body -->
  @if($reportType === 'office_summary')
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-building"></i> Office-Wise Ticket Distribution & Charges</h3>
      </div>
      <div class="box-body table-responsive no-padding">
        <table class="table table-bordered table-striped">
          <thead>
            <tr class="bg-gray-light">
              <th>Office / Company Name</th>
              <th>Total Tickets</th>
              <th>Total Actual Development Charges</th>
            </tr>
          </thead>
          <tbody>
            @foreach($officeWise as $row)
              <tr>
                <td><strong>{{ $row->office->office_name ?? 'N/A' }}</strong></td>
                <td><span class="badge bg-blue">{{ $row->count }}</span></td>
                <td class="text-green font-bold">₹{{ number_format((float) $row->total_actual, 2) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

  @elseif($reportType === 'category_summary')
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-tags"></i> Category-Wise Ticket Distribution</h3>
      </div>
      <div class="box-body table-responsive no-padding">
        <table class="table table-bordered table-striped">
          <thead>
            <tr class="bg-gray-light">
              <th>Category</th>
              <th>Ticket Count</th>
              <th>Percentage</th>
            </tr>
          </thead>
          <tbody>
            @foreach($categoryWise as $cat)
              <tr>
                <td><strong>{{ $cat->category->name ?? 'General' }}</strong></td>
                <td><span class="badge bg-aqua">{{ $cat->count }}</span></td>
                <td>{{ $tickets->count() > 0 ? round(($cat->count / $tickets->count()) * 100, 1) : 0 }}%</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

  @elseif($reportType === 'status_summary')
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-tasks"></i> Status-Wise Ticket Breakdown</h3>
      </div>
      <div class="box-body table-responsive no-padding">
        <table class="table table-bordered table-striped">
          <thead>
            <tr class="bg-gray-light">
              <th>Status</th>
              <th>Ticket Count</th>
            </tr>
          </thead>
          <tbody>
            @foreach($statusWise as $st)
              <tr>
                <td><strong>{{ str_replace('_', ' ', $st->status) }}</strong></td>
                <td><span class="badge bg-purple">{{ $st->count }}</span></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

  @elseif($reportType === 'priority_summary')
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-flag"></i> Priority-Wise Distribution</h3>
      </div>
      <div class="box-body table-responsive no-padding">
        <table class="table table-bordered table-striped">
          <thead>
            <tr class="bg-gray-light">
              <th>Priority Level</th>
              <th>Ticket Count</th>
            </tr>
          </thead>
          <tbody>
            @foreach($priorityWise as $pr)
              <tr>
                <td><strong>{{ ucfirst($pr->priority) }}</strong></td>
                <td><span class="badge bg-yellow">{{ $pr->count }}</span></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

  @else
    <!-- Detailed Tickets Listing -->
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-list"></i> Detailed Ticket Report ({{ $tickets->count() }} records)</h3>
      </div>
      <div class="box-body table-responsive no-padding">
        <table class="table table-bordered table-striped table-hover">
          <thead>
            <tr class="bg-gray-light">
              <th>Ticket #</th>
              <th>Office</th>
              <th>Subject</th>
              <th>Category</th>
              <th>Status</th>
              <th>Priority</th>
              <th>Estimated (₹)</th>
              <th>Actual (₹)</th>
              <th>Paid (₹)</th>
              <th>Outstanding (₹)</th>
              <th>Created Date</th>
            </tr>
          </thead>
          <tbody>
            @foreach($tickets as $t)
              <tr>
                <td><strong>{{ $t->ticket_number }}</strong></td>
                <td>{{ $t->office->office_name ?? 'N/A' }}</td>
                <td>{{ \Illuminate\Support\Str::limit($t->subject, 30) }}</td>
                <td>{{ $t->category->name ?? 'N/A' }}</td>
                <td><span class="label {{ $t->status_badge_class }}">{{ str_replace('_', ' ', $t->status) }}</span></td>
                <td><span class="label {{ $t->priority_badge_class }}">{{ ucfirst($t->priority) }}</span></td>
                <td>{{ $t->estimated_amount > 0 ? number_format($t->estimated_amount, 2) : '-' }}</td>
                <td>{{ $t->actual_amount > 0 ? number_format($t->actual_amount, 2) : '-' }}</td>
                <td class="text-green">{{ $t->totalPaid() > 0 ? number_format($t->totalPaid(), 2) : '-' }}</td>
                <td class="text-red font-bold">{{ $t->outstanding_amount > 0 ? number_format($t->outstanding_amount, 2) : '0.00' }}</td>
                <td>{{ $t->created_at->format('d-M-Y') }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endif
</section>
@endsection

