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
          Product Management
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{route('product.index')}}"><i class="fa fa-dashboard"></i> products</a></li>
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

      <!-- Main content -->
      <section class="content">
        
        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <section class="col-lg-12">
            <div class="row">
      
          <div class="col-md-12">
          <!-- Custom Tabs (Pulled to the right) -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs pull-right">
              <li @if($tab == 1) class="active" @endif><a href="#tab_1-1" data-toggle="tab">Possible Units</a></li>
              <li @if($tab == 0) class="active" @endif><a href="#tab_2-2" data-toggle="tab">Products</a></li>
              <li class="pull-left header"><i class="fa fa-th"></i></li>
            </ul>
            <div class="tab-content">

              <div @if($tab == 1) class="tab-pane active" @else class="tab-pane" @endif id="tab_1-1">
                @if($tab == 1)
                <div class="box-header with-border">
                  <div class="alert alert-danger">
                    <strong style="font-size: 18px;"> Please Enter Product Details.</strong>
                  </div>
                </div>
                @endif
                <div class="box-header">
                  <h3 class="box-title">Other Possible Units</h3>
                </div>

                <form class="form-horizontal">
                  <div class="box-body">
                    @if($possible != null) 
                    <div class="form-group">
                      <label for="inputcategory" class="col-md-2 control-label">Product Name</label>
                      <div class="col-md-4">
                        <label for="inputcategory" class=" control-label">{{$prd->name}} </label>
                      </div>
                    </div>
                    @endif

                    <div class="form-group">
                      <label for="inputhead" class="col-sm-1 control-label">Unit<font color="red">*</font></label>
                      <div class="col-sm-3">
                        <select class="form-control" name="other_unit" id="other_unit">
                          <option value="">Select</option>
                          @if($uom)
                            @foreach($uom as $unit)
                              <option value="{{$unit->id}}">{{$unit->unit}}</option>
                            @endforeach
                          @endif 
                        </select>
                      </div>
                      <label for="inputhead" class="col-sm-1 control-label">Value<font color="red">*</font></label>
                      <div class="col-sm-2">
                        <input type="number" class="form-control" min="0" step="0.1" name="base" id="base" @if($product_id == 0) disabled="" @endif>
                      </div>

                      <label for="inputhead" class="col-sm-1 control-label">Sqr. Ft.</label>
                      <div class="col-sm-2">
                        <input type="number" class="form-control" min="0" step="0.1" name="sqr_ft" id="sqr_ft" @if($product_id == 0) disabled="" @endif>
                      </div>
                      <label>/{{$prd_unit}}</label>
                      <button type="button" class="btn btn-info" id="add" style="margin-left: 70px;">+ADD</button>
                    </div>
                  </div>

                </form>
                <br>
                <?php $i = 0; ?>
                <form class="form-horizontal" action="{{route('otherUnits.insert')}}" method="POST" id="unitForm">
                  <input type="hidden" name="product_id" id="product_id" value="{{$prd->id}}">
                  <table class="table table-bordered table-striped unit_table">
                    <thead>    
                      <tr>
                        <th>Sl No.</th>
                        <th>Unit</th>
                        <th>Base Value</th>
                        <th>Sqr. Ft.</th>
                        <th>Action</th>
                      </tr>
                    </thead>

                    <tbody>
                      @foreach($possible_units as $poss)
                        <tr>
                          <td>{{$i++}}</td>
                          <td>{{$poss->unit->unit}}</td>
                          <td>{{$poss->base_value}}</td>
                          <td>{{$poss->square_feet}}</td>
                          <td><button type="button" class="btn-danger delete" id="{{$poss->id}}"><i class="fa fa-trash"></i></button></td>
                        </tr>
                      @endforeach
                    </tbody>     
                  </table>
                  <button type="button" class="btn btn-success" id="unitConfirm">Confirm</button>
                  <a  class="btn btn-danger pull-right" href="{{route('product.index')}}">Close</a>
                </form>
              </div>
              <!-- /.tab-pane -->

              
              <!-- /.tab-pane -->
              <div @if($tab == 0) class="tab-pane active" @else class="tab-pane" @endif id="tab_2-2">
                <div class="box-header">
                  <h3 class="box-title">New Product</h3>
                </div>
                  <!-- form start -->
                <form class="form-horizontal" action="{{route('product.insert')}}" method="POST" enctype="multipart/form-data">
                  <input type="hidden" name="_token" value="{{{ csrf_token() }}}">
                  <div class="box-body">
                  <br>
                    <div class="form-group" style="margin-left: -207px;">
                      <label for="inputcategory" class="col-md-3 control-label">Product Name<i style="color: red">*</i></label>
                      <div class="col-md-6">
                        <input type="text" class="form-control" name="product" placeholder="Enter" required>
                      </div>
                    </div>
                  
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="col-md-3 control-label">Category<i style="color: red">*</i></label>
                        <div class="col-md-6">
                          <select class="form-control" name="category" id="category_id" required style="width: 100%;">
                            <option value="">Select</option>
                            @foreach($category as $cat)
                            <option value="{{$cat->id}}" >{{$cat->category_name}}</option>
                            @endforeach 
                          </select>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="col-md-3 control-label">Sub-Category</label>
                        <div class="col-md-6">
                          <select class="form-control" name="sub" id="sub" style="width: 100%;">
                            <option value="">Select</option> 
                          </select>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="col-md-3 control-label">Group</label>
                        <div class="col-md-6">
                          <select class="form-control" name="group" id="grp" style="width: 100%;">
                            <option value="">Select</option> 
                          </select>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="col-md-3 control-label">Sub-Group</label>
                        <div class="col-md-6">
                          <select class="form-control" name="subgroup" id="subgrp" style="width: 100%;">
                            <option value="">Select</option>
                          </select>
                        </div>
                      </div>

                      <div class="form-group" id="div_brand">
                        <label class="col-md-3 control-label">Brand</label>
                        <div class="col-md-6">
                          <select class="form-control" name="brand" id="brand" style="width: 100%;">
                            <option value="">Select</option>
                    
                          </select>
                        </div>
                      </div>
                      
                      <div class="form-group" id="div_size" style="display: none;">
                        <label class="col-md-3 control-label">Size</label>
                        <div class="col-md-6">
                          <select class="form-control" name="size" id="size" style="width: 100%;">
                            <option value="">Select</option>

                          </select>
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="inputcategory" class="col-md-3 control-label">Product Code</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control" name="code" placeholder="Enter">
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="inputcategory" class="col-md-3 control-label">Specification</label>
                        <div class="col-md-6">
                          <input type="text" min="0" step="any" class="form-control" name="specification" placeholder="Enter">
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="inputcategory" class="col-md-3 control-label">Base Unit<i style="color: red">*</i></label>
                        <div class="col-md-6">
                          <select class="form-control" name="unit" required="">
                            <option value="">---select---</option>
                            @if($uom)
                              @foreach($uom as $unit)
                                <option value="{{$unit->id}}">{{$unit->unit}}</option>
                              @endforeach
                            @endif 
                          </select>
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-md-6" style="margin-left: 275px;">
                          <label>
                          <input type="checkbox" id="check" class="flat-red" name="active" value="Y" checked>
                        Is Active
                          </label>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="col-md-3 control-label">Upload Image:</label>
                        <div class="col-md-6">
                          <img src="" id="photo" alt="" width="150" height="200">
                          <input type="file" name="img" id="photo" accept="image/jpeg, image/png" onchange="readURL(this)">
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <!-- /.box-body -->
                  <div class="box-footer">
                    <div class="pull-left">
                      <button type="reset" class="btn btn-default">Refresh</button>
                      <button type="submit" class="btn btn-info">Save</button>
                    </div>
                  </div>
                  <!-- /.box-footer -->
                </form>
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
</html>

<script type="text/javascript">
  $("#category_id").change(function() {
    $("#sub").empty();
    $("#grp").empty();
    $("#subgrp").empty();
   // $('#div_brand').hide();
    $('#div_size').hide();
    $('#brand').val('');
    $('#size').val('');
    var cat = $(this).val();
    $.get("{{url('get/subCategory')}}",
      {option : cat},
      function(data){
        $("#s2id_sub span").html("");
        $('#sub').append("<option value=''>" + "select" + "</option>");
        $.each(data[0], function(index, element){
          $('#sub').append("<option value='"+ element.id +"'>" + element.sub_category + "</option>");
        });
        $('#brand').empty();
        $('#brand').append("<option value=''>" + "select" + "</option>");
        $.each(data[1], function(index1, element1){
          $('#brand').append("<option value='"+ element1.id +"'>" + element1.brand + "</option>");
        });    
            
    });
    if (cat == 1) {
       // $('#div_brand').show();
        $('#div_size').show();
        var prd_id = null;
        $.get("{{url('get/brand')}}",
          {option : cat, prd_id:prd_id},
          function(data1){ console.log(data1);
            if (data1[1]) {
              $('#size').empty();
              $('#size').append("<option value=''>" + "select" + "</option>");
              $.each(data1[1], function(index2, element2){
                $('#size').append("<option value='"+ element2.id +"'>" + element2.size + "</option>");
              });    
            }

        });  
    }
  });


  $("#sub").change(function() {
    $("#grp").empty();
    $("#subgrp").empty();
    var sub = $(this).val();
    var cat = $("#category_id").val();
    $.get("{{url('get/group/products')}}",
      {cat : cat, sub : sub},
      function(data){
        $("#s2id_grp span").html("");
        $('#grp').append("<option value=''>" + "select" + "</option>");
        $.each(data, function(index, element){
          $('#grp').append("<option value='"+ element.id +"'>" + element.group_name + "</option>");
        });
      });
  });

  $("#grp").change(function() {
    $("#subgrp").empty();
    var grp = $(this).val();
    $.get("{{url('get/subgroup/products')}}",
      {option: grp},
      function(data){
        $("#s2id_subgrp span").html("");
        $('#subgrp').append("<option value=''>" + "select" + "</option>");
        $.each(data, function(index, element){
          $('#subgrp').append("<option value='"+ element.id +"'>" + element.subgroup_name + "</option>");
        });
      });
  });

  // upload image

  function readURL(input){
    if(input.files){
      var reader = new FileReader();
      document.getElementById('photo').style.display = 'block';
      reader.onload = function(e){
        $('#photo').attr('src', e.target.result).width(150).height(200);
      };
      reader.readAsDataURL(input.files[0]);
    }
  }

  //add button

  $("#add").click(function(){
    var unit_id = $("#other_unit").val();
    var unit = $("#other_unit option:selected").text();
    var base_value = $("#base").val();
    var sqr_ft = $("#sqr_ft").val();

    if(unit_id == ""){
      alert("Kindly select a unit");
    }
    else{
      $(".unit_table tr:last").after('<tr><td></td><td><input type="hidden" name="unit[]" value="'+unit_id+'">'+unit+'</td><td><input type="hidden" name="base_value[]" value="'+base_value+'">'+base_value+'</td><td><input type="hidden" name="sqr_fts[]" value="'+sqr_ft+'">'+sqr_ft+'</td><td><button type="button" class="btn-danger delete"><i class="fa fa-trash"></i></button></td></tr>')

      var i = 1;
      $('.unit_table td:first-child').each(function() {
        $(this).text(i); 
        i++;  
      });
      $("#other_unit").val('');
      $("#base").val('');
      $("#sqr_ft").val('');
    }

  });

  //delete

  $(document).on("click",".delete", function() {
    if(confirm("Do you want to delete!")) {
      if($(this).attr("id")){
        $.get("{{url('delete/otherUnit')}}",{option: $(this).attr("id")}, function(data){});
      }
      $(this).closest("tr").remove();
      var i = 1;
      $('.unit_table td:first-child').each(function() {
        $(this).text(i); 
        i++;
      });
    }
  });


  //unit form

  $("#unitConfirm").click(function(){
    $("#unitForm").submit();
    $(this).attr("disabled","disabled");
  });

 //add packing

  

  
</script>