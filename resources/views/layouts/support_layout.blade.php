<!DOCTYPE html>
<html>
<head>
  @include('layouts/title')
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{ asset('lte/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('lte/bower_components/select2/dist/css/select2.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('lte/bower_components/font-awesome/css/font-awesome.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('lte/bower_components/Ionicons/css/ionicons.min.css') }}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('lte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
  <!-- Date Picker -->
  <link rel="stylesheet" href="{{ asset('lte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ asset('lte/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('lte/dist/css/AdminLTE.min.css') }}">
  <link rel="stylesheet" href="{{ asset('lte/dist/css/skins/_all-skins.min.css') }}">
  <link rel="stylesheet" href="{{ asset('lte/dist/css/style.css') }}">

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <style>
    .badge-status {
      font-size: 11px;
      padding: 4px 8px;
      border-radius: 3px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .timeline > li > .timeline-item {
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
      border-radius: 4px;
    }
    .table-ticket-hover tbody tr:hover {
      background-color: #f7fafc;
    }
  </style>
  @yield('styles')
</head>
<body class="hold-transition skin-blue fixed sidebar-mini">
<div class="wrapper">

  @include('layouts/header')
  @include('layouts/leftside')

  <div class="content-wrapper">
    <!-- Alert Messages -->
    <div style="padding: 15px 15px 0 15px;">
      @if(session('success'))
        <div class="alert alert-success alert-dismissible" style="margin-bottom: 10px;">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <h4><i class="icon fa fa-check"></i> Success!</h4>
          {{ session('success') }}
        </div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger alert-dismissible" style="margin-bottom: 10px;">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <h4><i class="icon fa fa-ban"></i> Error!</h4>
          {{ session('error') }}
        </div>
      @endif

      @if($errors->any())
        <div class="alert alert-danger alert-dismissible" style="margin-bottom: 10px;">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <h4><i class="icon fa fa-ban"></i> Please correct the following errors:</h4>
          <ul style="margin-bottom: 0;">
            @foreach($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif
    </div>

    @yield('content')
  </div>

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Support System</b> v1.0
    </div>
    <strong>&copy; {{ date('Y') }} Customer Support & Change Request Management System.</strong>
  </footer>
</div>

@yield('modals')

<!-- jQuery 3 -->
<script src="{{ asset('lte/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('lte/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('lte/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('lte/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('lte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<!-- datepicker -->
<script src="{{ asset('lte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<!-- SlimScroll -->
<script src="{{ asset('lte/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('lte/bower_components/fastclick/lib/fastclick.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('lte/dist/js/adminlte.min.js') }}"></script>

<script>
  $(function () {
    $('.select2').select2();
    $('.datepicker').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd',
      todayHighlight: true
    });
  });
</script>
@yield('scripts')
</body>
</html>

