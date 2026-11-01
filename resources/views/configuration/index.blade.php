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
          Manage Config
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Config</a></li>
          <li class="active">create</li>
        </ol>
      </section>

             @if(Session::has('exist')) 
              <div class="box-header with-border">
                <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                Attribute Already Exist...!!!
                </div>
              </div>
             @endif

             @if(Session::has('save')) 
             <div class="box-header with-border">
              <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 Attribute Saved Successfully...!!
              </div>
              </div>
             @endif
             
             @if(Session::has('Update')) 
             <div class="box-header with-border">
               <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 Attribute Updated Successfully...!!
               </div>
            </div>
             @endif

             <div class="box-header with-border" style="display: none;" id="msg">
               <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 Attribute Deleted Successfully...!!
               </div>
            </div>
      
      <!-- Main content -->
      <section class="content">
        
        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <section class="col-lg-12 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="nav-tabs-custom">
              <!-- Tabs within a box -->
              
              <div class="tab-content no-padding">
               <!-- right column -->
        
             <div class="box box-info">
              <div class="box-header with-border">
                <h3 class="box-title"></h3>
              </div>
            
             <form class="form-horizontal" action="{{route('config.store')}}" method="POST">
             <input type="hidden" name="_token" value="{{ csrf_token() }}">
             <input type="hidden" name="master_id" id="master_id" value="">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputhead" class="col-sm-1 control-label">Heads<font color="red">*</font></label>
                   <div class="col-sm-4">
                    <select class="form-control" name="head" id="head" required="" >
                    <option value="">Select</option>
                    @foreach($heads as $head)
                    <option value="{{$head->id}}">{{$head->name}}</option>
                    @endforeach 
                  </select>
                  </div>
                </div>

                 <div class="form-group">
                  <label for="inputattribute" class="col-sm-1 control-label">Attributes<font color="red">*</font></label>
                   <div class="col-sm-4">
                    <input type="text" class="form-control" id="attribute" name="attribute" placeholder="Enter" required="">
                  </div>
                  <label for="inputattribute" class="col-sm-1 control-label">Sequence</label>
                   <div class="col-sm-4">
                    <input type="number" class="form-control" id="sequence" name="sequence" placeholder="Enter">
                  </div>
                    <label for="inputactive" class="control-label"><input type="checkbox" name="is_active" id="Isactive" value="Y" checked=""> Is Active </label>
                </div>
                
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="reset" onclick="document.getElementById('master_id').value=''" class="btn btn-default pull-right">Refresh</button>
                <button type="submit" class="btn btn-info pull-right" style="margin-right: 8px;">Save</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
          <!-- /.box --> 
                
          </div>
          </div>

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Config List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                
                <tr>
                  <th>Sl No.</th>
                  <th>Head</th>
                  <th>Attributes</th>
                  <th>Sequence</th>
                  <th>Status</th>
                  <th>Edit</th>
                  <th>Delete</th>
                </tr>

                </thead>

                <tbody>
                 <?php $c = 1; ?>
                 @if($masters != NULL)
                 @foreach($masters as $master)                 
                  <tr id="{{$master->id}}">
                  <td class="order">{{$c++}}</td>
                  <td>
                    {{$master->common->name}}
                  </td>
                  <td>{{$master->name}}</td>
                  <td>{{$master->seq}}</td>
                  <td>@if($master->is_active == 'Y') Active @else Inactive @endif</td>
                  <td><a class="btn" onclick="jsEditConfig({{$master->id}})"><i class="fa fa-fw fa-pencil" style="color: blue;"></i></a></td>
                  <td><a class="btn" onclick="jsDeleteConfig({{$master->id}})"><i class="fa fa-fw fa-trash" style="color: red;"></i></a></td>
                  </tr>
                  @endforeach
                  @endif
                </tbody>
                
              </table>
            </div>
          </section>
        </div>
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
</script>
<script>
  function jsEditConfig(value)
  {
    document.body.scrollTop = document.documentElement.scrollTop = 0;
    $('#attribute').focus();
     $.get("{{ url('config/edit')}}", 
        { option: value }, 
          function(data) {
            $('#master_id').val(data[0].id);
            $('#head').val(data[0].parent_id);
            $('#attribute').val(data[0].name);
            $('#sequence').val(data[0].seq);
            if(data[0].is_active == 'Y'){
              $('#Isactive').attr('checked',true);
              $('#Isactive').val('Y');
            }
            else{
              $('#Isactive').attr('checked',false);
              $('#Isactive').val('N');
            }
        });
  }


  function jsDeleteConfig(value)
  {
      var x = confirm("Are You Sure?");

      if(x == true){
      $.get("{{ url('config/delete')}}", 
        { option: value }, 
          function(data) {
            var x = $('#'+value).children().first().text();

            var next = $('#'+value).nextAll();
            $('#'+value).remove();
            next.each(function(){
              i = x++;
              $(this).children().first().text(i);

            });
            document.body.scrollTop = document.documentElement.scrollTop = 0;
            $("#msg").show();
           
        });
      }
  }
</script>


</html>
