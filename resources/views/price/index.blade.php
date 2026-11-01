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
  <div class="content-wrapper"><br>
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Price List
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{route('office.index')}}"><i class="fa fa-dashboard"></i>Office </a></li>
          <li class="active">Create</li>
        </ol>
      </section>
             
      @if(Session::has('update')) 
        <div class="box-header with-border" id="updateTarget">
          <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
               Price Updated Successfully...!!
            </div>
          </div>
      @endif

      <div class="modal modal-default fade" id="modal-price">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="head"></h4>
            </div>

              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <div class="modal-body">
                <div class="form-group">
                  <div class="col-md-12">
                    <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Sl No.</th>
                        <th>Unit</th>
                        <th>Price</th>
                      </tr>
                    </thead>
                    <tbody id="body">
                      
                    </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-info pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info pull-right" id="othsave" data-dismiss="modal">Save</button>
              </div>

          </div>
        </div>
      </div>

      <!-- Main content -->
      <section class="content">
        <form action="{{route('price.list.index')}}" method="get">
          <div class="box">
            <div class="box-body">
              <div class="col-md-6">
                <div class="col-md-2">
                  <label class="control-label">Category</label>
                </div>
                <div class="col-md-10">
                  <select class="form-control" name="category" id="category_id" style="width: 100%;">
                    <option value="">Select</option>
                    @foreach($category as $cat)
                      @if($cat_id != null && $cat_id == $cat->id)
                        <option value="{{$cat->id}}" selected>{{$cat->category_name}}</option>
                      @else
                        <option value="{{$cat->id}}">{{$cat->category_name}}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
              </div>
              <div>
                <input type="submit" name="submit" value="submit" class="btn btn-primary">
              </div>
            </div>
          </div>
        </form>
        <div class="box">
            <div class="box-header">
              <!-- <h3 class="box-title">Office List</h3> -->
            </div>
            <!-- /.box-header -->
            <form action="{{route('price.update')}}" method="post" id="form">
              <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead >
                  <tr>
                      <th>Sl No.</th>
                      <th>Product Name</th>
                      <th>Unit</th>
                      <th>Price</th>
                      <th>Other</th>
                  </tr>
                  </thead>
                  <tbody>
                   <?php $c = 1; ?> 
                   @if($products)
                      @foreach($products as $pro)
                        <tr>
                          <td>{{$c++}}</td>
                          <td>{{$pro->product->name}}</td>
                          <td>{{$pro->unit->unit}}</td>
                          <td>
                            <input type="hidden" name="posunitid[]" value="{{$pro->id}}">
                            <input type="number" name="price[]" value="{{$pro->price}}" class="form-control" min="0" step="0.01" required>
                          </td>
                          <td><button type="reset" class="btn btn-primary fa fa-list price" data-toggle="modal" data-target="#modal-price" id="{{$pro->product_id}}" pro="{{$pro->product->name}}"></button></td>
                        </tr>
                      @endforeach 
                   @endif 
                  </tbody>
                </table>
              </div>
              <div class="box-footer">
                <div class="pull-right">
                  <input type="submit" name="save" value="Save" class="btn btn-success">
                </div>
              </div>
            </form>
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
<!-- DataTables -->
<script src="{{asset('lte/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('lte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
</body>

<script type="text/javascript">
 
  $(function () {
      $('#example1').DataTable({
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
      })
  })

  $('#form').on('submit', function (e) {
      $('.input-sm').val(-1);
      $('.input-sm').trigger('change');
  });

  $(document).on('click','.price',function(){
      var id  = $(this).attr('id');
      var pro = $(this).attr('pro');
      $('#body').empty();
      $('#head').text('Product : '+pro);
      $.get("{{url('other/units')}}", {id}, function(data){
          $.each(data,function(index,elements){
              var length = parseInt($('#body tr').length)+1;
              $('#body').append('<tr><td>'+length+'</td><td>'+elements.unit+'</td><td><input type="hidden" class="otrunitid" name="otrunitid[]" value="'+elements.id+'"><input type="number" class="othprice" name="othprice[]" value="'+elements.price+'" class="form-control" min="0" step="0.01"></td></tr>');
          });
      }); 
  });

  $(document).on('click','#othsave',function(){
      var ids   = [];
      var price = [];

      $('.otrunitid').each(function () {
          ids.push($(this).val());
      });

      $('.othprice').each(function () {
          price.push($(this).val());
          if($(this).val() == ''){
            alert("Price field cannot be empty.");
            return 0;
          }
      });

      if(ids.length > 0){
          $.get("{{url('other/units/update')}}", {ids,price}, function(data){
              alert("Price Updated.");
          });
      }

  });

</script>
</script>
</html>
