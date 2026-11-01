<!DOCTYPE html>
<html>
<head>
  @include('layouts/title')
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="{{asset('lte/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('lte/bower_components/font-awesome/css/font-awesome.min.css')}}">
  <link rel="stylesheet" href="{{asset('lte/dist/css/AdminLTE.min.css')}}">
  <link rel="stylesheet" href="{{asset('lte/dist/css/skins/_all-skins.min.css')}}">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue fixed sidebar-mini">
<div class="wrapper">
  @include('layouts/header')
  @include('layouts/leftside')

  <div class="content-wrapper"><br>
    <section class="content-header">
      <h1>
        <i class="fa fa-list-alt text-primary"></i> Activity & Audit Log Explorer
        <small>Search and inspect CRM user actions with zero-leak credential guarantee</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('security.dashboard')}}"><i class="fa fa-shield"></i> Security</a></li>
        <li class="active">Activity Explorer</li>
      </ol>
    </section>

    <section class="content">
      <!-- Search & Filter Card -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-filter"></i> Search & Filters</h3>
        </div>
        <div class="box-body">
          <form method="GET" action="{{ route('security.activities') }}" class="form-inline" style="display: flex; flex-wrap: wrap; gap: 10px; align-items: flex-end;">
            <div class="form-group">
              <label for="search">Keyword / IP:</label><br>
              <input type="text" name="search" id="search" class="form-control input-sm" value="{{ request('search') }}" placeholder="Search description, IP...">
            </div>

            <div class="form-group">
              <label for="user_id">User:</label><br>
              <select name="user_id" id="user_id" class="form-control input-sm">
                <option value="">-- All Users --</option>
                @foreach($users as $u)
                  <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->username }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="action">Action:</label><br>
              <select name="action" id="action" class="form-control input-sm">
                <option value="">-- All Actions --</option>
                @foreach($actions as $act)
                  <option value="{{ $act }}" {{ request('action') == $act ? 'selected' : '' }}>{{ strtoupper($act) }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="severity">Severity:</label><br>
              <select name="severity" id="severity" class="form-control input-sm">
                <option value="">-- All --</option>
                <option value="info" {{ request('severity') == 'info' ? 'selected' : '' }}>Info</option>
                <option value="warning" {{ request('severity') == 'warning' ? 'selected' : '' }}>Warning</option>
                <option value="critical" {{ request('severity') == 'critical' ? 'selected' : '' }}>Critical</option>
                <option value="security" {{ request('severity') == 'security' ? 'selected' : '' }}>Security</option>
              </select>
            </div>

            <div class="form-group">
              <label for="from_date">From Date:</label><br>
              <input type="date" name="from_date" id="from_date" class="form-control input-sm" value="{{ request('from_date') }}">
            </div>

            <div class="form-group">
              <label for="to_date">To Date:</label><br>
              <input type="date" name="to_date" id="to_date" class="form-control input-sm" value="{{ request('to_date') }}">
            </div>

            <div class="form-group" style="padding-bottom: 5px;">
              <label>
                <input type="checkbox" name="suspicious_only" value="1" {{ request('suspicious_only') ? 'checked' : '' }}> Suspicious Only
              </label>
            </div>

            <div class="form-group" style="margin-left: auto;">
              <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Apply Filter</button>
              <a href="{{ route('security.activities') }}" class="btn btn-sm btn-default"><i class="fa fa-refresh"></i> Reset</a>
            </div>
          </form>
        </div>
      </div>

      <!-- Activities Table -->
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Audit Records ({{ $activities->total() }} total)</h3>
        </div>
        <div class="box-body table-responsive no-padding">
          <table class="table table-hover table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>Timestamp</th>
                <th>Action</th>
                <th>User</th>
                <th>Office</th>
                <th>IP Address</th>
                <th>Severity</th>
                <th>Description</th>
                <th style="width: 80px;">Details</th>
              </tr>
            </thead>
            <tbody>
              @forelse($activities as $item)
                <tr class="{{ $item->is_suspicious ? 'danger' : '' }}">
                  <td>{{ $item->id }}</td>
                  <td><small>{{ $item->created_at->format('Y-m-d H:i:s') }}</small></td>
                  <td>
                    @if($item->action === 'login')
                      <span class="label label-success">LOGIN</span>
                    @elseif($item->action === 'login_failed')
                      <span class="label label-warning">LOGIN_FAIL</span>
                    @elseif($item->action === 'created')
                      <span class="label label-info">CREATED</span>
                    @elseif($item->action === 'updated')
                      <span class="label label-primary">UPDATED</span>
                    @elseif($item->action === 'deleted')
                      <span class="label label-danger">DELETED</span>
                    @elseif($item->action === 'access_denied')
                      <span class="label label-danger">DENIED</span>
                    @else
                      <span class="label label-default">{{ strtoupper($item->action) }}</span>
                    @endif
                  </td>
                  <td><strong>{{ $item->user?->username ?? 'Guest' }}</strong></td>
                  <td>{{ $item->office?->office_name ?? 'N/A' }}</td>
                  <td><code>{{ $item->ip_address }}</code></td>
                  <td>
                    @if($item->severity === 'critical')
                      <span class="label label-danger">CRITICAL</span>
                    @elseif($item->severity === 'warning')
                      <span class="label label-warning">WARNING</span>
                    @elseif($item->severity === 'security')
                      <span class="label label-purple" style="background:#605ca8;color:#fff;">SECURITY</span>
                    @else
                      <span class="label label-info">INFO</span>
                    @endif
                  </td>
                  <td>
                    {{ $item->description }}
                    @if($item->is_suspicious)
                      <span class="label label-danger" style="margin-left: 5px;">ANOMALY</span>
                    @endif
                  </td>
                  <td>
                    <button class="btn btn-xs btn-default btn-inspect" data-id="{{ $item->id }}">
                      <i class="fa fa-eye"></i> View
                    </button>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="9" class="text-center text-muted" style="padding: 30px;">
                    No activity logs match your search criteria.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="box-footer clearfix">
          <div class="pull-right">
            {{ $activities->links() }}
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<!-- Activity Inspector Modal -->
<div class="modal fade" id="modal-activity" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-info-circle text-primary"></i> Activity Detail Inspector</h4>
      </div>
      <div class="modal-body" id="modal-activity-body">
        <div class="text-center" style="padding: 30px;"><i class="fa fa-spin fa-spinner fa-2x"></i> Loading...</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="{{asset('lte/bower_components/jquery/dist/jquery.min.js')}}"></script>
<script src="{{asset('lte/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<script src="{{asset('lte/dist/js/adminlte.min.js')}}"></script>

<script type="text/javascript">
$(function () {
  $('.btn-inspect').on('click', function () {
    var id = $(this).data('id');
    $('#modal-activity').modal('show');
    $('#modal-activity-body').html('<div class="text-center" style="padding: 30px;"><i class="fa fa-spin fa-spinner fa-2x"></i> Loading activity #' + id + '...</div>');

    $.getJSON("{{ url('/security/activities') }}/" + id, function (data) {
      var html = '<table class="table table-bordered table-striped">' +
        '<tr><th style="width: 160px;">Event ID:</th><td>' + data.id + '</td></tr>' +
        '<tr><th>Action:</th><td><strong>' + data.action + '</strong></td></tr>' +
        '<tr><th>Description:</th><td>' + data.description + '</td></tr>' +
        '<tr><th>User / Office:</th><td>' + data.user + ' (' + data.office + ')</td></tr>' +
        '<tr><th>IP Address:</th><td><code>' + data.ip_address + '</code></td></tr>' +
        '<tr><th>HTTP Request:</th><td>' + (data.request_method || 'CLI') + ' ' + (data.request_url || '') + '</td></tr>' +
        '<tr><th>User Agent:</th><td><small>' + (data.user_agent || 'N/A') + '</small></td></tr>' +
        '<tr><th>Severity / Anomaly:</th><td>' + data.severity + (data.is_suspicious ? ' <span class="label label-danger">ANOMALOUS</span>' : '') + '</td></tr>' +
        '<tr><th>Timestamp:</th><td>' + data.created_at + '</td></tr>' +
        '</table>' +
        '<h4>Sanitized Change Diff / Metadata:</h4>' +
        '<pre style="background: #272822; color: #f8f8f2; padding: 12px; border-radius: 4px; max-height: 300px; overflow-y: auto;">' +
        JSON.stringify(data.changes, null, 2) +
        '</pre>';

      $('#modal-activity-body').html(html);
    }).fail(function () {
      $('#modal-activity-body').html('<div class="alert alert-danger">Failed to load activity details.</div>');
    });
  });
});
</script>
</body>
</html>
