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
        <i class="fa fa-bell text-danger"></i> Security Incidents & Threat Alerts
        <small>Triage and investigate unusual behavioral patterns detected by the anomaly engine</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('security.dashboard')}}"><i class="fa fa-shield"></i> Security</a></li>
        <li class="active">Alerts & Threats</li>
      </ol>
    </section>

    <section class="content">
      @if(Session::has('success'))
        <div class="alert alert-success alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <i class="fa fa-check"></i> {{ Session::get('success') }}
        </div>
      @endif

      <!-- Filter Bar -->
      <div class="box box-default">
        <div class="box-body">
          <form method="GET" action="{{ route('security.alerts') }}" class="form-inline">
            <div class="form-group" style="margin-right: 15px;">
              <label for="status">Status:</label>
              <select name="status" id="status" class="form-control input-sm">
                <option value="" {{ request('status') == '' ? 'selected' : '' }}>Open & Investigating</option>
                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open Only</option>
                <option value="investigating" {{ request('status') == 'investigating' ? 'selected' : '' }}>Investigating</option>
                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                <option value="false_positive" {{ request('status') == 'false_positive' ? 'selected' : '' }}>False Positive</option>
              </select>
            </div>

            <div class="form-group" style="margin-right: 15px;">
              <label for="severity">Severity:</label>
              <select name="severity" id="severity" class="form-control input-sm">
                <option value="">-- All Severities --</option>
                <option value="critical" {{ request('severity') == 'critical' ? 'selected' : '' }}>Critical</option>
                <option value="high" {{ request('severity') == 'high' ? 'selected' : '' }}>High</option>
                <option value="medium" {{ request('severity') == 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="low" {{ request('severity') == 'low' ? 'selected' : '' }}>Low</option>
              </select>
            </div>

            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-filter"></i> Filter</button>
            <a href="{{ route('security.alerts') }}" class="btn btn-sm btn-default"><i class="fa fa-refresh"></i> Reset</a>
          </form>
        </div>
      </div>

      <!-- Alerts List -->
      <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title">Incident Queue ({{ $alerts->total() }} alerts)</h3>
        </div>
        <div class="box-body table-responsive no-padding">
          <table class="table table-hover table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>Severity</th>
                <th>Threat Type</th>
                <th>Title</th>
                <th>Offending IP</th>
                <th>Target User</th>
                <th>Status</th>
                <th>Detected At</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($alerts as $alert)
                <tr>
                  <td>{{ $alert->id }}</td>
                  <td>
                    @if($alert->severity === 'critical')
                      <span class="label label-danger">CRITICAL</span>
                    @elseif($alert->severity === 'high')
                      <span class="label label-warning">HIGH</span>
                    @elseif($alert->severity === 'medium')
                      <span class="label label-info">MEDIUM</span>
                    @else
                      <span class="label label-default">LOW</span>
                    @endif
                  </td>
                  <td><code>{{ $alert->alert_type }}</code></td>
                  <td>
                    <strong>{{ $alert->title }}</strong>
                    <br><small class="text-muted">{{ $alert->description }}</small>
                  </td>
                  <td><code>{{ $alert->ip_address ?? 'N/A' }}</code></td>
                  <td>{{ $alert->user?->username ?? 'N/A' }}</td>
                  <td>
                    @if($alert->status === 'open')
                      <span class="label label-danger">OPEN</span>
                    @elseif($alert->status === 'investigating')
                      <span class="label label-warning">INVESTIGATING</span>
                    @elseif($alert->status === 'resolved')
                      <span class="label label-success">RESOLVED</span>
                    @else
                      <span class="label label-default">FALSE POSITIVE</span>
                    @endif
                  </td>
                  <td><small>{{ $alert->created_at->format('Y-m-d H:i') }}</small></td>
                  <td>
                    <button class="btn btn-xs btn-primary btn-resolve" 
                            data-id="{{ $alert->id }}"
                            data-title="{{ $alert->title }}"
                            data-status="{{ $alert->status }}"
                            data-notes="{{ $alert->resolution_notes }}">
                      <i class="fa fa-check-square-o"></i> Triage
                    </button>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="9" class="text-center text-muted" style="padding: 30px;">
                    <i class="fa fa-check-circle text-success" style="font-size: 24px;"></i>
                    <p style="margin-top: 10px;">No alerts found for this filter criteria.</p>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="box-footer clearfix">
          <div class="pull-right">
            {{ $alerts->links() }}
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<!-- Resolution Modal -->
<div class="modal fade" id="modal-resolve" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="POST" id="form-resolve" action="">
        @csrf
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="resolve-title"><i class="fa fa-wrench"></i> Triage Security Incident</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="modal-status">Incident Status:</label>
            <select name="status" id="modal-status" class="form-control" required>
              <option value="investigating">Investigating (In Progress)</option>
              <option value="resolved">Resolved (Mitigated / Addressed)</option>
              <option value="false_positive">False Positive (Benign Activity)</option>
            </select>
          </div>

          <div class="form-group">
            <label for="resolution_notes">Resolution / Mitigation Notes:</label>
            <textarea name="resolution_notes" id="modal-notes" rows="4" class="form-control" placeholder="Enter findings, firewall rules added, or root cause explanation..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Resolution</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="{{asset('lte/bower_components/jquery/dist/jquery.min.js')}}"></script>
<script src="{{asset('lte/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<script src="{{asset('lte/dist/js/adminlte.min.js')}}"></script>

<script type="text/javascript">
$(function () {
  $('.btn-resolve').on('click', function () {
    var id = $(this).data('id');
    var title = $(this).data('title');
    var status = $(this).data('status');
    var notes = $(this).data('notes') || '';

    $('#resolve-title').text('Triage Incident #' + id + ': ' + title);
    $('#form-resolve').attr('action', "{{ url('/security/alerts') }}/" + id + "/resolve");
    $('#modal-status').val(status === 'open' ? 'investigating' : status);
    $('#modal-notes').val(notes);

    $('#modal-resolve').modal('show');
  });
});
</script>
</body>
</html>
