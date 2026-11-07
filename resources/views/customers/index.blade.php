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
  <style>
     @media only screen 
and (min-device-width: 800px) 
and (max-device-height: 1280px) 
and (min-resolution: 192dpi)
and (-webkit-device-pixel-ratio:2)
and (orientation : portrait) 

{
    table {
        display: block;
        overflow-x: auto;
    }
}

@media only screen 
and (min-device-width: 1280px) 
and (max-device-height: 800px) 
and (min-resolution: 192dpi)
and (-webkit-device-pixel-ratio:2)
and (orientation : landscape) 

{
    table {
        display: block;
        overflow-x: auto;
    }
}

    </style>
  
  
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
          Customers
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{route('customers.index')}}"><i class="fa fa-dashboard"></i>Customers</a></li>
          <li class="active">List</li>
        </ol>
      </section>

      @if(Session::has('number')) 
          <div class="box-header with-border">
              <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                Number Already Exist...
              </div>
          </div>
      @endif

      @if(Session::has('save')) 
          <div class="box-header with-border" id="saveTarget">
              <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 Saved Successfully...!!
              </div>
          </div>
      @endif
             
      @if(Session::has('update')) 
          <div class="box-header with-border" id="updateTarget">
              <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 Updated Successfully...!!
              </div>
          </div>
      @endif

      <div class="modal modal-default fade" id="modal-log">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Enquiry Log</h4>
            </div>

            <form class="form-horizontal form" action="{{route('designaton.update')}}" method="POST">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="modal-body">
              <div class="form-group">
                <div class="col-md-12">
                  <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Status</th>
                      <th>Date</th>
                      <th>Remarks</th>
                    </tr>
                  </thead>
                  <tbody id="tbody">
                  </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-info pull-right" data-dismiss="modal">Close</button>
            </div>
          </form>
          </div>
        </div>
      </div>

    
      <!-- Main content -->
      <section class="content">
         <div class="box">
            <div class="box-header">
              <h3 class="box-title">Customer List</h3>
              <u><small style="float: right;margin-right: 15px; font-size: 14px;">
               <a href="{{route('customers.create')}}">
               <i class="fa fa-fw fa-plus"> </i>New Customer</a></small></u>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead >
                <tr>
                    <th>Sl No.</th>
                    <th>Customer Name</th>
                    <th>Contact No</th>
                    <th>Address</th>
                    <th>Place</th>
                    <th>Type</th>
                    <th>Classification</th>    
                    <th>Edit</th>
                </tr>
                </thead>
                <tbody>
                 <?php $c = 1; ?>
                 @if($customers != NULL)
                 @foreach($customers as $customer)
                  <tr>
                      <td>{{$c++}}</td>
                      <td>{{$customer->customer_name}}</td>
                      <td>{{$customer->customer_phone}}</td>
                      <td>{{$customer->address}}</td>
                      <td>{{$customer->place->place ?? ''}}</td>
                      <td>{{$customer->customerType->customer_type_name ?? ''}}</td>
                      <td>
                        @if($customer->classification)
                          <span class="label label-info">{{$customer->classification->name}}</span>
                        @else
                          <span class="text-muted">-</span>
                        @endif
                      </td>
                      <td><a href="{{route('customers.edit',[$customer->id])}}"><i class="fa fa-fw fa-pencil" style="color: blue;"></i></a></td>
                  </tr>
                  @endforeach
                  @endif
                </tbody>
              </table>
            </div>
           
          </section>
          <!-- /.Left col -->
          
        </div>
        <!-- /.row (main row) -->

      </section>
      <!-- /.content -->
    </div>
  <!-- /.content-wrapper -->
  
  @include('layouts/footertag')
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>

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
<!-- bootstrap datepicker -->
  <script src="{{asset('lte/bower_components/moment/min/moment.min.js')}}"></script>
  <script src="{{asset('lte/bower_components/bootstrap-daterangepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('lte/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('lte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
 <!-- DataTables -->
 <link rel="stylesheet" href="{{asset('lte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">
</body>
<script type="text/javascript">
  $('.datepicker').datepicker({
      autoclose: true,
      format: 'dd-mm-yyyy'
    })
</script>

<script type="text/javascript">
 $(function () {
    $('#example1').DataTable()
    
  })

 $(document).on('change','.change', function() {

    if($('#status').val() != '' && $('#from').val() != '' && $('#to').val() != ''){
       var text    = $('#status').find('option:selected').text();
       var from    = $('#from').val();
       var to      = $('#to').val();

       ////////////////////

       var from  = from.split("-").reverse().join("-");
       var to    = to.split("-").reverse().join("-");

       ////////////////////
     
       text        = text.replace(/\s+/g, '');
       text        = '/'+text+'/'+from+'/'+to;
       var APP_URL = {!! json_encode(url('/')) !!}
       var url     = APP_URL+text;
       $('#submit').attr('href',url);
    } 
    else{
      $('#submit').attr('href','');
    }
 });

 $(document).on('click','.log', function() {
    var id = $(this).attr('id');
    $('#tbody').empty();
    $.get("{{url('enquiry/log')}}", {id}, function(data) {
        if(parseInt(data.length) > 0){
            $.each(data,function(index,element){
                var a = $.datepicker.formatDate( "dd-mm-yy", new Date(element.status_date));
                if(element.status_remaks == null){
                  element.status_remaks = '';
                }
                $('#tbody').append('<tr><td>'+element.status_name+'</td><td>'+a+'</td><td>'+element.status_remaks+'</td></tr>')
            });
        }
    });
 });

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
