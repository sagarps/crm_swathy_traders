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

  <link rel="stylesheet" href="{{asset('lte/dist/css/style.css')}}">


  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">


  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="{{asset('lte/plugins/iCheck/all.css')}}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{asset('lte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">
  
  
</head>
<body class="hold-transition skin-blue fixed sidebar-mini">
<div class="wrapper">
  
  @include('layouts/header')
  <!-- Left side column. contains the logo and sidebar -->
  @include('layouts/leftside')
   <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"><br>
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Complaint Management
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i>Complaint Management</a></li>
          <li class="active">index</li>
        </ol>
      </section>

             @if(Session::has('save')) 
             <div class="box-header with-border" id="saveTarget">
              <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 Complaint Saved Successfully...!!
              </div>
              </div>
             @endif
             
             @if(Session::has('update')) 
             <div class="box-header with-border" id="updateTarget">
               <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 Complaint updated Successfully...!!
               </div>
            </div>
             @endif

             @if(Session::has('number')) 
             <div class="box-header with-border" id="updateTarget">
               <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 Phone number Allready Used...!!
               </div>
            </div>
             @endif

      
      <!-- Main content -->
      <section class="content">
         <div class="box">
            <div class="box-header">
                <form action="{{route('complaint.index')}}" method="get">
              <div class="form-group">
                <div class="form-group col-sm-5">
                  <label class="col-sm-2 control-label">From<font color="red">*</font></label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control datepicker" id="from" name="from" placeholder="Enter" required="" value="{{date('d-m-Y',strtotime($from))}}">
                    <br>
                  </div>
                </div>
                
                <div class="form-group col-sm-5">
                  <label class="col-sm-2 control-label">To<font color="red">*</font></label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control datepicker" id="to" name="to" placeholder="Enter" required="" value="{{date('d-m-Y',strtotime($to))}}">
                    <br>
                  </div>
                </div>
                <div class="form-group col-sm-2">
                  <input type="submit" class="btn btn-primary pull-right">
                </div>
              </div>
              </form>
            </div>
          </div>

         <div class="box">
            <div class="box-header">
              <h3 class="box-title">Complaint List</h3>
              <u><small style="float: right;margin-right: 15px; font-size: 14px;">
               <a href="{{route('complaint.create')}}">
               <i class="fa fa-fw fa-plus"> </i>New Complaint</a></small></u>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead >
                  <tr>
                       <th>Sl No</th>
                       <th>Complaint No</th>
                       <th>Customer</th>
                       <th>Customer Number</th>
                       <th>Complaint Type</th>
                       <th>Complaint Status</th>
                       <th>Date</th>
                       <th>Edit</th>
                 </tr>
                </thead>
                <tbody>
                 <?php $c = 1; ?>
                 @foreach($complaint as $cmp)
                <tr> 
                     <td>{{$c++}}</td>
                     <td>{{$cmp->order_no}}</td>
                     <td>{{$cmp->customer->customer_name ?? ''}}</td>
                     <td>{{$cmp->customer->customer_phone ?? ''}}</td>
                     <td>{{$cmp->type->type_name ?? ''}}</td>
                     <td>{{$cmp->status->status_name ?? ''}}</td>
                     <td>{{date('d-m-Y',strtotime($cmp->complaint_date))}}</td>
                     <td class="center">
                      @if($cmp->status->status_name == 'DELIVERED')
                        <a href="{{route('complaint.view',$cmp->id)}}"><i class="fa fa-fw fa-list" style="color: blue;"></i></a></td>
                      @else
                        <a href="{{route('complaint.edit',$cmp->id)}}"><i class="fa fa-fw fa-pencil" style="color: blue;"></i></a></td>
                      @endif
                </tr>
                @endforeach
                </tbody>
              </table>
            </div>
          </section>
        </div>
      </section>
    </div>

  
  @include('layouts/footertag')

<!-- jQuery 3 -->
<script src="{{asset('lte/bower_components/jquery/dist/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('lte/bower_components/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="{{asset('lte/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- Morris.js charts -->
<script src="{{asset('lte/bower_components/raphael/raphael.min.js')}}"></script>
<script src="{{asset('lte/bower_components/morris.js/morris.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{asset('lte/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js')}}"></script>
<!-- jvectormap -->
<script src="{{asset('lte/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
<script src="{{asset('lte/plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{asset('lte/bower_components/jquery-knob/dist/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{asset('lte/bower_components/moment/min/moment.min.js')}}"></script>
<script src="{{asset('lte/bower_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<!-- datepicker -->
<script src="{{asset('lte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{asset('lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>
<!-- Slimscroll -->
<script src="{{asset('lte/bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
<!-- FastClick -->
<script src="{{asset('lte/bower_components/fastclick/lib/fastclick.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('lte/dist/js/adminlte.min.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{asset('lte/dist/js/pages/dashboard.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('lte/dist/js/demo.js')}}"></script>

  <!-- iCheck 1.0.1 -->
<script src="{{asset('lte/plugins/iCheck/icheck.min.js')}}"></script>

<!-- DataTables -->
<script src="{{asset('lte/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('lte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
</body>

<script type="text/javascript">
 $(function () {
    $('#example1').DataTable()
    
  })

  $('.datepicker').datepicker({
      autoclose: true,
      format: 'dd-mm-yyyy'
    })

  $(document).ready(function(){
   // Date Object
    $("#from").datepicker().on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#to').datepicker('setStartDate', minDate);
    });

    $("#to").datepicker().on('changeDate', function (selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('#from').datepicker('setEndDate', maxDate);
    });
              
    var minDate = $("#from").val();
    $('#to').datepicker('setStartDate', minDate);

    var maxDate = $("#to").val();
    $('#from').datepicker('setEndDate', maxDate);

 });
</script>
</html>
