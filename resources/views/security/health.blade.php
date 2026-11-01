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
        <i class="fa fa-heartbeat text-success"></i> System Health & Log Storage Monitoring
        <small>Database performance, disk capacity, and Monolog file storage status</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('security.dashboard')}}"><i class="fa fa-shield"></i> Security</a></li>
        <li class="active">System Health</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <!-- DB Status -->
        <div class="col-md-4">
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-database"></i> Database Health</h3>
            </div>
            <div class="box-body">
              <p><strong>Status:</strong> <span class="label label-success">{{ $dbStatus }}</span></p>
              <p><strong>Query Ping Latency:</strong> <span class="badge bg-green">{{ $dbLatency }} ms</span></p>
              <p><strong>Connection:</strong> <code>{{ config('database.default') }}</code></p>
              <p><strong>Host:</strong> <code>{{ config('database.connections.mysql.host') }}</code></p>
            </div>
          </div>
        </div>

        <!-- Disk Usage -->
        <div class="col-md-4">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-hdd-o"></i> Disk Space Capacity</h3>
            </div>
            <div class="box-body">
              <p><strong>Free Storage:</strong> {{ $freeDisk }} GB of {{ $totalDisk }} GB</p>
              <div class="progress progress-sm active">
                <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" style="width: {{ $usedPercent }}%">
                  <span class="sr-only">{{ $usedPercent }}% Used</span>
                </div>
              </div>
              <p class="text-muted"><small>{{ $usedPercent }}% disk capacity utilized</small></p>
            </div>
          </div>
        </div>

        <!-- Environment Info -->
        <div class="col-md-4">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-cogs"></i> Runtime Environment</h3>
            </div>
            <div class="box-body">
              <p><strong>Laravel Version:</strong> <code>{{ app()->version() }}</code></p>
              <p><strong>PHP Version:</strong> <code>{{ PHP_VERSION }}</code></p>
              <p><strong>Environment:</strong> <code>{{ app()->environment() }}</code></p>
              <p><strong>Debug Mode:</strong> <code>{{ config('app.debug') ? 'ENABLED' : 'DISABLED' }}</code></p>
            </div>
          </div>
        </div>
      </div>

      <!-- Log Files Inspection -->
      <div class="row">
        <div class="col-md-8">
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-file-text-o"></i> Monolog Disk Files (`storage/logs/`)</h3>
            </div>
            <div class="box-body table-responsive no-padding">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Log Filename</th>
                    <th>Size</th>
                    <th>Last Written</th>
                    <th>Purpose</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($logFiles as $file)
                    <tr>
                      <td><i class="fa fa-file-code-o text-muted"></i> <strong>{{ $file['name'] }}</strong></td>
                      <td><code>{{ $file['size'] }}</code></td>
                      <td><small>{{ $file['modified'] }}</small></td>
                      <td>
                        @if(str_contains($file['name'], 'security'))
                          <span class="label label-purple" style="background:#605ca8;color:#fff;">Security Anomaly Trail</span>
                        @elseif(str_contains($file['name'], 'audit'))
                          <span class="label label-info">Compliance Audit Trail</span>
                        @else
                          <span class="label label-default">General Application Log</span>
                        @endif
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="4" class="text-center text-muted">No log files found in storage/logs/</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- 24-Hour Scan Diagnostics -->
        <div class="col-md-4">
          <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-dashboard"></i> 24h Telemetry Summary</h3>
            </div>
            <div class="box-body no-padding">
              <table class="table table-condensed">
                <tbody>
                  <tr>
                    <td>Total Activity Events:</td>
                    <td><span class="badge bg-blue">{{ $scanStats['total_events'] }}</span></td>
                  </tr>
                  <tr>
                    <td>Successful Logins:</td>
                    <td><span class="badge bg-green">{{ $scanStats['successful_logins'] }}</span></td>
                  </tr>
                  <tr>
                    <td>Failed Logins:</td>
                    <td><span class="badge bg-yellow">{{ $scanStats['failed_logins'] }}</span></td>
                  </tr>
                  <tr>
                    <td>Flagged Anomalies:</td>
                    <td><span class="badge bg-red">{{ $scanStats['suspicious_events'] }}</span></td>
                  </tr>
                  <tr>
                    <td>Active Security Incidents:</td>
                    <td><span class="badge bg-purple" style="background:#605ca8;">{{ $scanStats['open_alerts'] }}</span></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<script src="{{asset('lte/bower_components/jquery/dist/jquery.min.js')}}"></script>
<script src="{{asset('lte/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<script src="{{asset('lte/dist/js/adminlte.min.js')}}"></script>
</body>
</html>
