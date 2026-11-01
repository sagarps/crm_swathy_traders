<!DOCTYPE html>
<html>
<head>
  @include('layouts/title')

<!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{asset('lte/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{asset('lte/bower_components/select2/dist/css/select2.min.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('lte/bower_components/font-awesome/css/font-awesome.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{asset('lte/bower_components/Ionicons/css/ionicons.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('lte/dist/css/AdminLTE.min.css')}}">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{asset('lte/dist/css/skins/_all-skins.min.css')}}">
  <!-- Morris chart -->
  <link rel="stylesheet" href="{{asset('lte/bower_components/morris.js/morris.css')}}">
  <!-- jvectormap -->
  <link rel="stylesheet" href="{{asset('lte/bower_components/jvectormap/jquery-jvectormap.css')}}">
  <!-- Date Picker -->
  <link rel="stylesheet" href="{{asset('lte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{asset('lte/bower_components/bootstrap-daterangepicker/daterangepicker.css')}}">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="{{asset('lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">


  <!-- DataTables -->
  <link rel="stylesheet" href="{{asset('lte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">
</head>
<body>
<div class="hidden-print text-center" style="float:left; margin:20px" >
        <a  class="btn btn-danger" href="{{URL::previous()}}">Back</a>
    </div>
<br><br><br>
  <!-- Main content -->
   <section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <i class=""></i> Complaint List
            <small class="pull-right">Date Range: {{date('d-m-Y',strtotime($from))}} - {{date('d-m-Y',strtotime($to))}}</small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      
      <div class="row invoice-info">
        @if($status != NULL)
        <div class="col-sm-3 invoice-col">
          <strong> Status   : {{$status->status_name}} </strong>
        </div>
        @endif
        @if($type != NULL)
        <div class="col-sm-3 invoice-col">
          <strong> Type   : {{$type->type_name}} </strong>
        </div>
        @endif

        <!-- /.col -->
      </div>
      <br>
      
      <!-- Table row -->
      <div class="row" style="page-break-after: always;">
        <div class="col-xs-12 table-responsive">
          <table class="table table-bordered table-striped">
            <thead>
            <tr>
              <th>Sl No.</th>
              <th>Complaint No</th>
              <th>Customer</th>
              <th>Phone</th>
              <th>Complaint Type</th>
              <th>Status</th>
              <th>Date</th>
              <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php $c = 1;?>
            @if($complaint != NULL)
              @foreach($complaint as $cmp)
              <tr>
               <td>{{$c++}}</td>
               <td>{{$cmp->order_no}}</td>
               <td>{{$cmp->customer->customer_name ?? ''}}</td>
               <td>{{$cmp->customer->customer_phone ?? ''}}</td>
               <td>{{$cmp->type->type_name ?? ''}}</td>
               <td>{{$cmp->status->status_name ?? ''}}</td>
               <td>{{date('d-m-Y',strtotime($cmp->complaint_date))}}</td>
               <td>{{$cmp->log($cmp->id)->action_taken}}</td>
              </tr>
              @endforeach
            @endif
            </tbody>
            
          </table>
          
        </div>
        
        <!-- /.col -->
      </div>
      <!-- /.row -->

      

      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12">
          <a  target="_blank"  class="btn btn-success pull-right" onclick="window.print();"><i class="fa fa-print"></i> Print</a>
        </div>
      </div>
    </section>
    <!-- /.content -->
  <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>


