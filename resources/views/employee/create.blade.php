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
  
</head>
<body class="hold-transition skin-blue fixed sidebar-mini">
<div class="wrapper">
  
  @include('layouts/header')
  <!-- Left side column. contains the logo and sidebar -->
  @include('layouts/leftside')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          New Employee 
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{route('employee.index')}}"><i class="fa fa-dashboard"></i> Employee Management</a></li>
          <li class="active">create</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
       
         <form class="form-horizontal" onsubmit="return submitForm(this);" action="{{route('employee.store')}}" method="POST">
         <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <!-- Horizontal Form -->
          <div class="box box-info">
             <div class="box-header with-border">
               <h3 class="box-title"></h3>
             </div>
            
              <div class="box-body">
                <div class="form-group">
                  <div>
                    <div class="col-sm-6">
                      <label class="control-label">Name <font color="red">*</font></label>
                      <input type="text" name="name" class="form-control" placeholder="Enter" required="" autocomplete="off">
                    </div>
                    <div class="col-sm-6">
                      <label class="control-label">Designation<font color="red">*</font></label>
                      <select name="designation" id="designation" class="form-control" required="">
                        <option value="">Select</option>
                          @foreach($designation as $desc)
                            <option value="{{$desc->id}}">{{$desc->designation}}</option>
                          @endforeach  
                      </select>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div>
                    <div class="col-sm-6">
                      <label class="control-label">Contact <font color="red">*</font></label>
                      <input type="number" name="contact" class="form-control" placeholder="Enter" required="">
                    </div>
                    <div class="col-sm-6">
                      <label class="control-label">Date OF Birth<font color="red">*</font></label>
                      <input type="text" class="form-control datepicker" name="dob" id="dob" value="{{date('d-m-Y')}}"  required="">
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div>
                    <div class="col-sm-6">
                      <label class="control-label">Address <font color="red">*</font></label>
                      <textarea class="form-control" name="address" required rows="4"></textarea>
                    </div>
                    <div class="col-sm-6">
                      <label class="control-label">Office<font color="red">*</font></label>
                      <select name="office" id="office" class="form-control" required="">
                        <option value="">Select</option>
                          @foreach($office as $off)
                            <option value="{{$off->id}}">{{$off->office_name}}</option>
                          @endforeach  
                      </select>
                      <br>
                      <label for="inputactive" class="control-label"><input type="checkbox" name="Isactive" id="Isactive" value="Y" checked="">Is Active </label>

                    </div>
                  </div>
                </div>
  
             </div>
          </div>
      <!-- /.box -->
      
              <div class="box-footer">
                <button type="reset" class="btn btn-default pull-right" >Refresh</button>
                <button type="submit" class="btn btn-info pull-right" style="margin-right: 10px;">Save</button>
              </div> 
         </div>
         </form>
      </section>
    </div>
  @include('layouts/footertag')
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
<!-- AdminLTE for demo purposes -->
<script src="{{asset('lte/dist/js/demo.js')}}"></script>

  <!-- iCheck 1.0.1 -->
 <script src="{{asset('lte/plugins/iCheck/icheck.min.js')}}"></script>
 <!-- bootstrap datepicker -->
 <script src="{{asset('lte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>


</body>
<script type="text/javascript">
  $('.datepicker').datepicker({
      autoclose: true,
      format: 'dd-mm-yyyy'
    })
</script>
<script type="text/javascript">

  function submitForm(data) {
    return confirm('Do you really want to submit?');
  }
  
  $(document).ready(function(){

    var d     = new Date();
    var month = d.getMonth()+1;
    var day   = d.getDate();

    var output = day + '-' +
      (month<10 ? '0' : '') + month + '-' +
      (day<10 ? '0' : '') + d.getFullYear();
    $('#dob').datepicker('setEndDate', output);

    $("#staffType").change(function(){

     var type = $("#staffType").val();
     
     $.get("{{ url('visibile/employee/type')}}", 
        { option: type }, 
          function(data) {
          if(data != 0)
          { 
            $(".star").show();
            $('#licenseno').attr('required',true);
            $('#validFrom').attr('required',true);
            $('#validto').attr('required',true);
            $('#lic_type').attr('required',true);
          }
          else
          { 
            $(".star").hide();
            $('#licenseno').attr('required',false);
            $('#validFrom').attr('required',false);
            $('#validto').attr('required',false);
            $('#lic_type').attr('required',false);
          }
        });

    });

  });

</script>

</html>
