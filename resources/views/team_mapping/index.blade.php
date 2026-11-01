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
  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
       <h1>
          Team Mapping
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Team Mapping</a></li>
          <li class="active">List</li>
        </ol>
      </section>


      <div class="modal modal-default fade" id="modal-edit">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Edit Team</h4>
            </div>

            <form class="form-horizontal form" action="{{route('team.mapping.update')}}" method="POST">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" name="edit_id" id="edit_id" value="">
            <div class="modal-body">
              <div class="form-group">
                <label for="inputcategory" class="col-sm-2 control-label">Lead<font color="red">*</font></label>
                <div class="col-sm-8">
                  <select class="form-control select2" name="edit_lead" id="edit_lead" style="width: 100%;" required>
                    <option value="">--Select--</option> 
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="inputcategory" class="col-sm-2 control-label">Members<font color="red">*</font></label>
                <div class="col-sm-8">
                  <select class="form-control select2" multiple="" name="edit_member[]" id="edit_member" style="width: 100%;" required>
                  </select>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-info pull-left" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-info">Save</button>
            </div>
          </form>
          </div>
        </div>
      </div>

      <div class="modal modal-default fade" id="modal-add">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Add Team</h4>
            </div>

            <form class="form-horizontal form" action="{{route('team.mapping.store')}}" method="POST">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="modal-body">
              <div class="form-group">
                <label for="inputcategory" class="col-sm-2 control-label">Lead<font color="red">*</font></label>
                <div class="col-sm-8">
                  <select class="form-control select2" name="lead" id="lead" style="width: 100%;" required>
                    <option value="">--Select--</option> 
                    @foreach($employee as $emp)
                      <option value="{{$emp->id}}">{{$emp->name}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="inputcategory" class="col-sm-2 control-label">Members<font color="red">*</font></label>
                <div class="col-sm-8">
                  <select class="form-control select2" multiple="" name="member[]" id="member" style="width: 100%;" required>
                    
                  </select>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-info pull-left" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-info">Save</button>
            </div>
          </form>
          </div>
        </div>
      </div>


      @if(Session::has('save')) 
        <div class="box-header with-border">
          <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 Team Saved Sucessfully...!!
          </div>
        </div>
      @endif

      @if(Session::has('update')) 
        <div class="box-header with-border">
          <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
               Team Updated Sucessfully...!!
          </div>
        </div>
      @endif   

      <!-- Main content -->
      <section class="content">
       
        <div class="row">
          <!-- Left col -->
          <section class="col-lg-12 ">
     

            <div class="box">
            <div class="box-header">
              <h3 class="box-title">Team Mapping List</h3>
              <u style="float: right;margin-right: 15px">
                <a class="btn btn-info" data-toggle="modal" data-target="#modal-add" id="add">ADD<i class="fa fa-fw fa-plus" style="color: blue;"></i></a>
              </u>
              <br>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Sl No.</th>
                    <th>Team Lead</th>
                    <th>Edit</th>
                    <th>Delete</th>
                  </tr>
                </thead>

                <tbody>
                  <?php $c = 1; ?>
                  @foreach($team as $desc)
                  <tr> 
                    <td>{{$c++}}</td>
                    <td>{{$desc->name}}</td>
                    <td class="center"><a class="btn btn-info edit" data-toggle="modal" data-target="#modal-edit" id="{{$desc->parent_id}}"><i class="fa fa-fw fa-pencil" style="color: blue;"></i></a></td>
                    <td><i class="btn btn-danger fa fa-trash delete" id="{{$desc->parent_id}}"></i></td>
                  </tr>
                  @endforeach                        
                </tbody>
              </table>
            </div>
          </div></section>
        </div>
      </section>
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
<!-- Select2 -->
<script src="{{asset('lte/bower_components/select2/dist/js/select2.full.min.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('lte/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('lte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
</body>

<script type="text/javascript">
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

  })

 $(function () {
    $('#example1').DataTable()
    
  })

  $(document).on("click","#add",function() {
      $('#lead').val('').select2();
      $('#member').val('').select2();
  });

  $(document).on("click",".edit",function() {
      $('#edit_id').val('');
      var parent_id  = $(this).attr('id');
      $('#edit_id').val(parent_id);
      $('#edit_lead').empty();
      $('#edit_member').empty();
      $.get("{{url('parent/list')}}", {parent_id}, function(data) {
      console.log(data);
          $.each(data[0], function(index, element){
              if(element.id == parent_id){
                  $('#edit_lead').append("<option value='"+ element.id +"' selected>" +element.name+ "</option>");
              }
              else{
                  $('#edit_lead').append("<option value='"+ element.id +"'>" +element.name+ "</option>"); 
              }
          });
          $.each(data[1], function(index, element1){
              if($.inArray(element1.id,data[2]) !== -1){
                  $('#edit_member').append("<option value='"+ element1.id +"' selected>" +element1.name+ "</option>");
              }
              else{                
                  $('#edit_member').append("<option value='"+ element1.id +"'>" +element1.name+ "</option>"); 
              }
          });
      });
  });

  $(document).on("change","#lead",function() {
      var val = $(this).val();
      $('#member').empty();
      if(val != ''){
          $.get("{{url('member/list')}}", {val}, function(data) {
              $.each(data, function(index, element){
                  $('#member').append("<option value='"+ element.id +"'>" +element.name+ "</option>");
              });
          });
      }
  });

  $(document).on("change","#edit_lead",function() {
      var val = $(this).val();
      $('#edit_member').empty();
      if(val != ''){
          $.get("{{url('edit/member/list')}}", {val}, function(data) {
              $.each(data, function(index, element){
                  $('#edit_member').append("<option value='"+ element.id +"'>" +element.name+ "</option>");
              });
          });
      }
  });

  $(document).on("click",".delete",function() {
      var val = $(this).attr('id');
      var ths = $(this);
      var x   = confirm("Are You Sure ?");
      if(x == true){
          $.get("{{url('delete/member/list')}}", {val}, function(data) {
              ths.closest("tr").remove();
              var i = 1;
              $('#example1 td:first-child').each(function() {
                  $(this).text(i); 
                  i++;  
              }); 
          });
      }
  });
  


</script>
</script>
</html>





