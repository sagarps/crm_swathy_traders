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

  <!-- Select2 -->
  <link rel="stylesheet" href="{{asset('lte/bower_components/select2/dist/css/select2.min.css')}}">
  
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
          Product Categorization
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{route('product.category.index')}}"><i class="fa fa-dashboard"></i> categorization</a></li>
          <li class="active">create</li>
        </ol>
      </section>

            @if(Session::has('exist')) 
              <div class="box-header with-border">
                <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
               Already Exist...
                </div>
              </div>
             @endif

             @if(Session::has('save')) 
             <div class="box-header with-border">
              <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                Saved Successfully.
              </div>
              </div>
             @endif
             
             @if(Session::has('Update')) 
             <div class="box-header with-border">
               <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                Updated Successfully.
               </div>
            </div>
             @endif


      <!--add category-->
      <div class="modal modal-default fade" id="modal-info">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Add Category</h4>
            </div>

            <form class="form-horizontal form" action="{{route('product.category.store')}}" method="POST">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="modal-body">
              <div class="form-group">
                <label for="inputcategory" class="col-sm-3 control-label">Category<font color="red">*</font></label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" name="category" id="cat_popup" placeholder="Enter" autofocus="" required="">
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-info pull-left" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-info">Save</button>
            </div>
          </form>
          </div>
            <!-- /.modal-content -->
        </div>
          <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

    <!-- /edit category -->
      <div class="modal modal-default fade" id="edit-modal-info">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Edit Category</h4>
            </div>

            <form class="form-horizontal form" action="{{route('product.category.update')}}" method="POST">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="modal-body">
              <div class="form-group">
                <input type="hidden" class="form-control" name="category_id" id="category_id" value="">
                <label for="inputcategory" class="col-sm-3 control-label">Category<font color="red">*</font></label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="category" name="category" placeholder="Enter" autofocus="" required="">
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-info pull-left" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-info">Update</button>
            </div>
          </form>
          </div>
            <!-- /.modal-content -->
        </div>
          <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

      <!--add sub category-->
      <div class="modal modal-default fade" id="modal-danger">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Sub-category</h4>
              </div>
              <form class="form-horizontal form" action="{{route('sub_category.store')}}" method="POST">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <div class="modal-body">
                <div class="form-group">
                  <label for="inputcategory" class="col-sm-3 control-label">Category<font color="red">*</font></label>
                  <div class="col-sm-6">
                    <select class="form-control closes" name="cat_id" id="subcat_idpop" required="">
                      <option value="">select</option>
                      @foreach($categories as $cat)
                      <option value="{{$cat->id}}">{{$cat->category_name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label for="inputcategory" class="col-sm-3 control-label">Sub-Category<font color="red">*</font></label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control closes" name="sub_category" id="subcat_pop" placeholder="Enter" required="">
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger pull-left clsbtn" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-danger">Save</button>
              </div>
            </form>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <!--edit sub category-->
      <div class="modal modal-default fade" id="edit-modal-danger">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Sub-category</h4>
              </div>
              <form class="form-horizontal form" action="{{route('sub_category.update')}}" method="POST">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <div class="modal-body">
                <div class="form-group">
                  <input type="hidden" class="form-control" name="sub_id" id="sub_id" value="">
                  <label for="inputcategory" class="col-sm-3 control-label">Category<font color="red">*</font></label>
                  <div class="col-sm-6">
                    <select class="form-control closes" name="cat_id" id="cat_id" required="">
                      <option value="">select</option>
                      @foreach($categories as $cat)
                      <option value="{{$cat->id}}">{{$cat->category_name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label for="inputcategory" class="col-sm-3 control-label">Sub-Category<font color="red">*</font></label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control closes" name="sub_category" id="sub_cat" placeholder="Enter" required="">
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger pull-left clsbtn" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-danger">Update</button>
              </div>
            </form>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->


        <!--add group-->
      <div class="modal modal-default fade" id="modal-warning">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Group</h4>
              </div>
              <form class="form-horizontal form" action="{{route('group.store')}}" method="POST">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <div class="modal-body">

                <div class="form-group">
                  <label for="inputcategory" class="col-sm-3 control-label">Group<font color="red">*</font></label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control closes" name="group" id="group_popup" placeholder="Enter" required="">
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-warning pull-left clsbtn" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-warning">Save</button>
              </div>
            </form>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <!--edit group-->
      <div class="modal modal-default fade" id="edit-modal-warning">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Group</h4>
              </div>
              <form class="form-horizontal form" action="{{route('group.update')}}" method="POST">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" name="group_id" id="group_id">
              <div class="modal-body">
                <div class="form-group">
                  <label for="inputcategory" class="col-sm-3 control-label">Group<font color="red">*</font></label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control closes" name="group" id="group" placeholder="Enter" required="">
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-warning pull-left clsbtn" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-warning">Save</button>
              </div>
            </form>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <!--add sub group-->
      <div class="modal modal-default fade" id="modal-success">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Sub-group</h4>
              </div>
              <form class="form-horizontal form" action="{{route('sub_group.store')}}" method="POST">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <div class="modal-body">
                <div class="form-group">
                  <label for="inputcategory" class="col-sm-3 control-label">Group<font color="red">*</font></label>
                  <div class="col-sm-6">
                    <select class="form-control closes" name="grp_id" id="subgroup_idpop" required="" style="width: 100%;">
                      <option value="">select</option>
                      @foreach($groups as $gr)
                      <option value="{{$gr->id}}">{{$gr->group_name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label for="inputcategory" class="col-sm-3 control-label">Sub-group<font color="red">*</font></label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control closes" name="sub_group" id="subgroup_pop" placeholder="Enter" required="">
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-success pull-left clsbtn" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Save</button>
              </div>
            </form>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <!--edit sub group-->
      <div class="modal modal-default fade" id="edit-modal-success">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Sub-group</h4>
              </div>
              <form class="form-horizontal form" action="{{route('sub_group.update')}}" method="POST">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" name="subgroup_id" id="subgroup_id">
              <div class="modal-body">
                <div class="form-group">
                  <label for="inputcategory" class="col-sm-3 control-label">Group<font color="red">*</font></label>
                  <div class="col-sm-6">
                    <select class="form-control closes" name="grp_id" id="grp_id" required="" style="width: 100%;">
                      <option value="">select</option>
                      @foreach($groups as $gr)
                      <option value="{{$gr->id}}">{{$gr->group_name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label for="inputcategory" class="col-sm-3 control-label">Sub-group<font color="red">*</font></label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control closes" name="sub_group" id="subgrp" placeholder="Enter" required="">
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-success pull-left clsbtn" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Save</button>
              </div>
            </form>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

      <!-- Main content -->
      <section class="content">
        
        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <section class="col-lg-12 ">
            <div class="row">
      
          <div class="col-md-12">
          <!-- Custom Tabs (Pulled to the right) -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs pull-right">
              <li><a href="#tab_3-3" data-toggle="tab">Sub-Group</a></li>
              <li><a href="#tab_1-1" data-toggle="tab">Group</a></li>
              <li><a href="#tab_2-2" data-toggle="tab">Sub-Category</a></li>
              <li class="active"><a href="#tab_3-2" data-toggle="tab">Category</a></li>
              <li class="pull-left header"><i class="fa fa-th"></i></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane" id="tab_3-3">
                <div class="box-header">
                  <h3 class="box-title">Sub Groups</h3>
                  <button type="button" class="btn btn-success pull-right" data-toggle="modal" id="subgrouppop" data-target="#modal-success">
                      +Add
                    </button>
                </div>

                <table class="table table-bordered table-striped example1">
                  <thead>
                        
                    <tr>
                      <th>Sl No.</th>
                      <th>Group</th>
                      <th>Sub Group</th>
                      <th>Edit</th>
                    </tr>

                  </thead>

                  <tbody>
                    <?php $c = 1; ?>
                    @if($groups != NULL)
                    @foreach($sub_groups as $subgrp)
                    <tr>
                      <td>{{$c++}}</td>
                      <td>{{$subgrp->group->group_name}}</td>
                      <td>{{$subgrp->subgroup_name}}</td>
                      <td><button type="button" onclick="jsEditSubGroup({{$subgrp->id}})" class="btn btn-success" data-toggle="modal" data-target="#edit-modal-success"><i class="fa fa-fw fa-pencil"></i></button></td>
                    </tr>
                    @endforeach
                    @endif
                  </tbody>
                        
                </table>
              </div>
              <!-- /.tab-pane -->

              <div class="tab-pane" id="tab_1-1">
                <div class="box-header">
                  <h3 class="box-title">Groups</h3>
                  <button type="button" class="btn btn-warning pull-right" id="grouppop" data-toggle="modal" data-target="#modal-warning">
                      +Add
                    </button>
                </div>

                <table class="table table-bordered table-striped example1">
                  <thead>
                        
                    <tr>
                      <th>Sl No.</th>
                      <th>Group</th>
                      <th>Edit</th>
                    </tr>

                  </thead>

                  <tbody>
                    <?php $c = 1; ?>
                    @if($groups != NULL)
                    @foreach($groups as $grp)
                    <tr>
                      <td>{{$c++}}</td>
                      <td>{{$grp->group_name}}</td>
                      <td><button type="button" onclick="jsEditGroup({{$grp->id}})" class="btn btn-warning" data-toggle="modal" data-target="#edit-modal-warning"><i class="fa fa-fw fa-pencil"></i></button></td>
                    </tr>
                    @endforeach
                    @endif
                  </tbody>
                        
                </table>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2-2">
                <div class="box-header">
                  <h3 class="box-title">Sub-Categories</h3>
                  <button type="button" class="btn btn-danger pull-right" data-toggle="modal" id="subcatpop" data-target="#modal-danger">
                      +Add
                    </button>
                </div>
                  <table class="table table-bordered table-striped example1">
                        <thead>
                        
                        <tr>
                          <th>Sl No.</th>
                          <th>Category</th>
                          <th>Sub-Category</th>                          
                          <th>Edit</th>
                        </tr>

                        </thead>

                        <tbody>
                         <?php $c = 1; ?>
                         @if($sub_cat != NULL)
                         @foreach($sub_cat as $sub)
                          <tr>
                          <td>{{$c++}}</td>
                          <td>{{$sub->category->category_name}}</td>
                          <td>{{$sub->sub_category}}</td>
                         <td><button type="button" onclick="jsEditSubCategory({{$sub->id}})" class="btn btn-danger" data-toggle="modal" data-target="#edit-modal-danger"><i class="fa fa-fw fa-pencil"></i></button></td>
                          </tr>
                          @endforeach
                          @endif
                        </tbody>
                        
                      </table>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane active" id="tab_3-2">
                <div class="box-header">
                  <h3 class="box-title">Categories</h3>
                  <button type="button" class="btn btn-info pull-right" data-toggle="modal" id="catpop" data-target="#modal-info">
                      +Add
                    </button>
                </div>
                  <table class="table table-bordered table-striped example1">
                    <thead> 
                        <tr>
                          <th>Sl No.</th>
                          <th>Category</th>
                          <th>#</th>
                        </tr>

                        </thead>

                        <tbody>
                         <?php $c = 1; ?>
                         @if($categories != NULL)
                         @foreach($categories as $category)
                          <tr>
                          <td>{{$c++}}</td>
                          <td>{{$category->category_name}}</td>
                          <td><button type="button" onclick="jsEditCategory({{$category->id}})" class="btn btn-info" data-toggle="modal" data-target="#edit-modal-info"><i class="fa fa-fw fa-pencil"></i></button></td>
                          </tr>
                          @endforeach
                          @endif
                        </tbody>                      
                      </table>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <!-- END CUSTOM TABS -->
          </section>
          <!-- /.Left col -->
          
        </div>
        <!-- /.row (main row) -->

      </section>
      <!-- /.content -->
    </div>
  <!-- /.content-wrapper -->
  
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

<script src="{{asset('lte/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('lte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('lte/bower_components/select2/dist/js/select2.full.min.js')}}"></script>

  <script type="text/javascript">
    //Initialize Select2 Elements
    $('.select2').select2()
    //Date picker
      $('.datepicker').datepicker({
      autoclose: true
    })
  </script>
</body>

<script>
  $(function () {
    $('.example1').DataTable()
    
  });

  function jsEditCategory(value)
  { 
    document.body.scrollTop = document.documentElement.scrollTop = 0;
    $('#category').focus();
     $.get("{{ url('product/category/edit')}}", 
        { option: value }, 
          function(data) {
            $('#category_id').val(data.id);
            $('#category').val(data.category_name);
        });
  }

  function jsEditSubCategory(value)
  { 
    document.body.scrollTop = document.documentElement.scrollTop = 0;
    $('#cat_id').focus();
     $.get("{{ url('sub_category/edit')}}", 
        { option: value }, 
          function(data) {
            $('#sub_id').val(data.id);
            $('#sub_cat').val(data.sub_category);
            $('#cat_id').val(data.category_id);
        });
  }

  $(".clsbtn").click(function() {
    $('.closes').val('');
  });

  $("#cat_id_g").change(function() {
    var cat = $(this).val();
    $.get("{{ url('sub_category/get')}}", 
        { option: cat }, 
          function(data) {
            $('#sub_cat_g').empty();
            $('#sub_cat_g').append("<option value=''>" + "Select" + "</option>");
            $.each(data, function(index, element){
              $('#sub_cat_g').append("<option value='"+ element.id +"'>" + element.sub_category + "</option>");
            });
        });
  });

 /* $("#edit_cat_g").change(function() {
    var cat = $(this).val();
    $.get("{{ url('sub_category/get')}}", 
        { option: cat }, 
          function(data) {
            $('#edit_sub_g').empty();
            $('#edit_sub_g').append("<option value=''>" + "Select" + "</option>");
            $.each(data, function(index, element){
              $('#edit_sub_g').append("<option value='"+ element.id +"'>" + element.sub_category + "</option>");
            });
        });
  });*/

  function jsEditGroup(value)
  { 
    document.body.scrollTop = document.documentElement.scrollTop = 0;
    $('#cat_id_g').focus();
     $.get("{{ url('group/edit')}}", 
        { option: value }, 
          function(data) {
            console.log(data);
            $('#group_id').val(data[0].id);
            $('#group').val(data[0].group_name);
        });
  }

  function jsEditSubGroup(value)
  {
    document.body.scrollTop = document.documentElement.scrollTop = 0;
    $('#grp_id').focus();
     $.get("{{ url('subgroup/edit')}}", 
        { option: value }, 
          function(data) {
            console.log(data);
            $('#subgroup_id').val(data[0].id);
            $('#grp_id').val(data[0].group_id);
            $('#subgrp').val(data[0].subgroup_name);
            $('#grp_id').append("<option value='"+data[0].group_id+"' selected=''>" + data[0].group_name + "</option>");
            $.each(data[1], function(index, element){
              $('#grp_id').append("<option value='"+ element.id +"'>" + element.group_name + "</option>");
            });
        });
  }
  
  $(document).on("click","#catpop", function() {
      $('#cat_popup').val('');
  });
  
  $(document).on("click","#subcatpop", function() {
      $('#subcat_idpop').val('');
      $('#subcat_pop').val('');
  });

  $(document).on("click","#grouppop", function() {
      $('#group_popup').val('');
  });

  $(document).on("click","#subgrouppop", function() {
      $('#subgroup_idpop').val('');
      $('#subgroup_pop').val('');
  });

</script>

</html>
