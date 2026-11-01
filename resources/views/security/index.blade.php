<!DOCTYPE html>
<html>
<head>
  @include('layouts/title')
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="{{asset('lte/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('lte/bower_components/font-awesome/css/font-awesome.min.css')}}">
  <link rel="stylesheet" href="{{asset('lte/bower_components/Ionicons/css/ionicons.min.css')}}">
  <link rel="stylesheet" href="{{asset('lte/dist/css/AdminLTE.min.css')}}">
  <link rel="stylesheet" href="{{asset('lte/dist/css/skins/_all-skins.min.css')}}">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <style>
    .badge-info { background-color: #00c0ef; color: #fff; }
    .badge-warning { background-color: #f39c12; color: #fff; }
    .badge-danger { background-color: #dd4b39; color: #fff; }
    .badge-success { background-color: #00a65a; color: #fff; }
    .badge-security { background-color: #605ca8; color: #fff; }
  </style>
</head>
<body class="hold-transition skin-blue fixed sidebar-mini">
<div class="wrapper">
  @include('layouts/header')
  @include('layouts/leftside')

  <div class="content-wrapper"><br>
    <section class="content-header">
      <h1>
        <i class="fa fa-shield text-primary"></i> Security & Activity Monitoring
        <small>Real-time audit log, threat detection & zero-leak tracking</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Security Monitoring</li>
      </ol>
    </section>

    <section class="content">
      <!-- Metric Cards -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>{{ $stats['total_today'] }}</h3>
              <p>Events Logged Today</p>
            </div>
            <div class="icon">
              <i class="fa fa-list-alt"></i>
            </div>
            <a href="{{ route('security.activities') }}" class="small-box-footer">View Audit Explorer <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>{{ $stats['failed_logins_today'] }}</h3>
              <p>Failed Logins (Today)</p>
            </div>
            <div class="icon">
              <i class="fa fa-lock"></i>
            </div>
            <a href="{{ route('security.activities', ['action' => 'login_failed']) }}" class="small-box-footer">Inspect Failed Logins <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-red">
            <div class="inner">
              <h3>{{ $stats['suspicious_today'] }}</h3>
              <p>Suspicious Anomalies</p>
            </div>
            <div class="icon">
              <i class="fa fa-exclamation-triangle"></i>
            </div>
            <a href="{{ route('security.activities', ['suspicious_only' => 1]) }}" class="small-box-footer">Review Suspicious <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <div class="small-box {{ $stats['open_alerts_count'] > 0 ? 'bg-purple' : 'bg-green' }}">
            <div class="inner">
              <h3>{{ $stats['open_alerts_count'] }}</h3>
              <p>Open Security Incidents</p>
            </div>
            <div class="icon">
              <i class="fa fa-shield"></i>
            </div>
            <a href="{{ route('security.alerts') }}" class="small-box-footer">Manage Incidents <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div>

      <!-- Quick Action Buttons -->
      <div class="row" style="margin-bottom: 15px;">
        <div class="col-md-12">
          <div class="btn-group">
            <a href="{{ route('security.activities') }}" class="btn btn-default"><i class="fa fa-search"></i> Activity Explorer</a>
            <a href="{{ route('security.alerts') }}" class="btn btn-default"><i class="fa fa-bell"></i> Security Alerts ({{ $stats['open_alerts_count'] }})</a>
            <a href="{{ route('security.health') }}" class="btn btn-default"><i class="fa fa-heartbeat"></i> System Health & Log Storage</a>
          </div>
        </div>
      </div>

      <div class="row">
        <!-- Open Security Alerts -->
        <div class="col-md-6">
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-bell text-danger"></i> Open Security Alerts</h3>
              <div class="box-tools pull-right">
                <a href="{{ route('security.alerts') }}" class="btn btn-box-tool"><i class="fa fa-external-link"></i> View All</a>
              </div>
            </div>
            <div class="box-body no-padding">
              @if($recentAlerts->isEmpty())
                <div class="p-4 text-center" style="padding: 30px;">
                  <i class="fa fa-check-circle text-success" style="font-size: 32px;"></i>
                  <p class="text-muted" style="margin-top: 10px;">No open security alerts. All systems normal.</p>
                </div>
              @else
                <table class="table table-striped table-hover">
                  <thead>
                    <tr>
                      <th>Severity</th>
                      <th>Title</th>
                      <th>Target / IP</th>
                      <th>Time</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($recentAlerts as $alert)
                      <tr>
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
                        <td><strong>{{ $alert->title }}</strong></td>
                        <td><code>{{ $alert->ip_address ?? 'N/A' }}</code></td>
                        <td><small class="text-muted">{{ $alert->created_at->diffForHumans() }}</small></td>
                        <td>
                          <a href="{{ route('security.alerts') }}" class="btn btn-xs btn-primary"><i class="fa fa-wrench"></i> Triage</a>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              @endif
            </div>
          </div>
        </div>

        <!-- Recent Audit Activities -->
        <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-clock-o text-primary"></i> Recent Activity Stream</h3>
              <div class="box-tools pull-right">
                <a href="{{ route('security.activities') }}" class="btn btn-box-tool"><i class="fa fa-external-link"></i> Full Log</a>
              </div>
            </div>
            <div class="box-body no-padding">
              <table class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th>Time</th>
                    <th>Action</th>
                    <th>User</th>
                    <th>Description</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($recentActivities as $act)
                    <tr class="{{ $act->is_suspicious ? 'danger' : '' }}">
                      <td><small class="text-muted">{{ $act->created_at->format('H:i:s') }}</small></td>
                      <td>
                        @if($act->action === 'login')
                          <span class="label label-success">LOGIN</span>
                        @elseif($act->action === 'login_failed')
                          <span class="label label-warning">LOGIN_FAIL</span>
                        @elseif($act->action === 'created')
                          <span class="label label-info">CREATE</span>
                        @elseif($act->action === 'updated')
                          <span class="label label-primary">UPDATE</span>
                        @elseif($act->action === 'deleted')
                          <span class="label label-danger">DELETE</span>
                        @elseif($act->action === 'access_denied')
                          <span class="label label-danger">FORBIDDEN</span>
                        @else
                          <span class="label label-default">{{ strtoupper($act->action) }}</span>
                        @endif
                      </td>
                      <td><strong>{{ $act->user?->username ?? 'Guest' }}</strong></td>
                      <td>
                        <span title="{{ $act->description }}">{{ \Illuminate\Support\Str::limit($act->description, 35) }}</span>
                        @if($act->is_suspicious)
                          <span class="label label-danger pull-right">SUSPICIOUS</span>
                        @endif
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="4" class="text-center text-muted">No recent activity recorded yet.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Top Failed IPs Watchlist -->
      @if($topFailedIps->isNotEmpty())
        <div class="row">
          <div class="col-md-12">
            <div class="box box-warning">
              <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-ban text-warning"></i> Watchlist: Top Failed Login IP Addresses (Last 7 Days)</h3>
              </div>
              <div class="box-body no-padding">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>IP Address</th>
                      <th>Failed Login Count</th>
                      <th>Risk Level</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($topFailedIps as $item)
                      <tr>
                        <td><code>{{ $item->ip_address }}</code></td>
                        <td><span class="badge {{ $item->count >= 5 ? 'bg-red' : 'bg-yellow' }}">{{ $item->count }}</span></td>
                        <td>
                          @if($item->count >= 10)
                            <span class="label label-danger">CRITICAL ABUSE RISK</span>
                          @elseif($item->count >= 5)
                            <span class="label label-warning">BRUTE FORCE SUSPECT</span>
                          @else
                            <span class="label label-info">MONITORING</span>
                          @endif
                        </td>
                        <td>
                          <a href="{{ route('security.activities', ['search' => $item->ip_address]) }}" class="btn btn-xs btn-default"><i class="fa fa-search"></i> Inspect Activity</a>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      @endif

    </section>
  </div>
</div>

<script src="{{asset('lte/bower_components/jquery/dist/jquery.min.js')}}"></script>
<script src="{{asset('lte/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<script src="{{asset('lte/dist/js/adminlte.min.js')}}"></script>
</body>
</html>
