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
          Product Management
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{route('product.index')}}"><i class="fa fa-dashboard"></i> products</a></li>
          <li class="active">update</li>
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
              
              <li @if($update != 0) class="active" @endif><a href="#tab_1-1" data-toggle="tab">Possible Units</a></li>
              <li @if($update == 0) class="active" @endif><a href="#tab_2-2" data-toggle="tab">Products</a></li>
              <li class="pull-left header"><i class="fa fa-th"></i></li>
            </ul>
            <div class="tab-content">

              <div @if($update != 0) class="tab-pane active" @else class="tab-pane" @endif id="tab_1-1">
                
                <div class="box-header">
                  <h3 class="box-title">Other Possible Units</h3>
                </div>

                <form class="form-horizontal">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="inputcategory" class="col-md-2 control-label">Product Name</label>
                      <div class="col-md-6">
                        <label for="inputcategory" class="control-label"> {{$product->name}}</label>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputhead" class="col-sm-1 control-label">Unit<font color="red">*</font></label>
                      <div class="col-sm-2">
                        <select class="form-control" name="other_unit" id="other_unit" required="">
                          <option value="">Select</option>
                          @foreach($uom as $unit)
                          <option value="{{$unit->id}}">{{$unit->unit}}</option>
                          @endforeach 
                        </select>
                      </div>
                      <label for="inputhead" class="col-sm-1 control-label">Value<font color="red">*</font></label>
                      <div class="col-sm-3">  
                        <div class="col-sm-8">
                          <input type="number" class="form-control" min="0" step="0.1" name="base" id="base">
                        </div>
                        <div class="col-sm-4">
                          <label>/{{$base_unit->unit}}</label>
                        </div>
                      </div>

                      <label for="inputhead" class="col-sm-1 control-label">Price<font color="red">*</font></label>
                      <div class="col-sm-2">
                        <input type="number" class="form-control" min="0" step="0.1" name="unitprice" id="unitprice" >
                      </div>
                      <button type="button" class="btn btn-info" id="add" style="margin-left: 40px;">+ADD</button>

                    </div>
                  </div>

                </form>
                <br>

                <form class="form-horizontal" action="{{route('otherUnits.update',$product->id)}}" method="POST" id="unitForm">
                  <input type="hidden" name="product_id" id="product_id" value="{{$product->id}}">
                  <input type="hidden" name="update" value="UPD">
                <table class="table table-bordered table-striped unit_table">
                  <thead>    
                    <tr>
                      <th>Sl No.</th>
                      <th>Unit</th>
                      <th>Value</th>
                      <th>Price</th>
                      <th>Action</th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php $c = 1;?>
                    @foreach($possible as $poss)
                    <tr>
                      <td>{{$c++}}</td>
                      <td>{{$poss->unit['unit']}}</td>
                      <td>{{$poss->base_value}}</td>
                      <td>{{$poss->price}}</td>
                      <td>@if($poss->ref == 'O')<button type="button" class="btn-danger delete" id="{{$poss->id}}"><i class="fa fa-trash"></i></button>@endif</td>
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

              <!-- /.tab-pane -->

              <div @if($update == 0) class="tab-pane active" @else class="tab-pane" @endif id="tab_2-2">
                <div class="box-header">
                  <h3 class="box-title">Update Product</h3>
                </div>
                  <!-- form start -->
                <form class="form-horizontal" action="{{route('product.update')}}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{{ csrf_token() }}}">
                <input type="hidden" name="prd_id" value="{{$product->id}}">
                <input type="hidden" name="cat_id" id="cat_id" value="{{$product->category_id}}">
              
                  <div class="box-body">
                    <div >
                    
                    <div class="form-group">
                      <div class="col-md-6">
                      <label for="inputcategory" class="control-label">Product Name<i style="color: red">*</i></label>
                        <input type="text" class="form-control" name="product" value="{{$product->name}}" required>
                      </div>
                      <div class="col-md-6">
                      <label for="inputcategory" class="control-label">Product Code</label>
                        <input type="text" class="form-control" name="code" value="{{$product->code}}">
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-6">
                      <label class="control-label">Category<i style="color: red">*</i></label>
                        <select class="form-control select2" name="category" id="category_id" required style="width: 100%;">
                          <option value="">Select</option>
                          @foreach($category as $cat)
                          @if($product->category_id == $cat->id)
                          <option value="{{$cat->id}}" selected="">{{$cat->category_name}}</option>
                          @else
                          <option value="{{$cat->id}}">{{$cat->category_name}}</option>
                          @endif
                          @endforeach 
                        </select>
                      </div>
                      <div class="col-md-6">
                      <label class="control-label">Sub-Category</label>
                        <select class="form-control select2" name="sub" id="sub" style="width: 100%;">
                          <option value="">Select</option>
                          @foreach($sub_cat as $sub)
                          @if($product->sub_category_id == $sub->id)
                          <option value="{{$sub->id}}" selected="">{{$sub->sub_category}}</option>
                          @else
                          <option value="{{$sub->id}}">{{$sub->sub_category}}</option>
                          @endif
                          @endforeach  
                        </select>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-6">
                      <label class="control-label">Group</label>
                        <select class="form-control select2" name="group" id="grp" style="width: 100%;">
                          <option value="">Select</option>
                          @foreach($groups as $grp)
                          @if($product->group_id == $grp->id)
                          <option value="{{$grp->id}}" selected="">{{$grp->group_name}}</option>
                          @else
                          <option value="{{$grp->id}}">{{$grp->group_name}}</option>
                          @endif
                          @endforeach 
                        </select>
                      </div>
                      <div class="col-md-6">
                      <label class="control-label">Sub-Group</label>
                        <select class="form-control select2" name="subgroup" id="subgrp" style="width: 100%;">
                          <option value="">Select</option>
                          @foreach($subgroups as $subgrp)
                          @if($product->sub_group_id == $subgrp->id)
                          <option value="{{$subgrp->id}}" selected="">{{$subgrp->subgroup_name}}</option>
                          @else
                          <option value="{{$subgrp->id}}">{{$subgrp->subgroup_name}}</option>
                          @endif
                          @endforeach 
                        </select>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-6">
                      <label for="inputcategory" class="control-label">Base Unit<i style="color: red">*</i></label>
                        <select class="form-control" name="unit" required="">
                          <option value="">Select</option>
                          @foreach($uom as $unit)
                            @if($base_unit->unit_id == $unit->id)
                            <option value="{{$unit->id}}" selected="">{{$unit->unit}}</option>
                            @else
                            <option value="{{$unit->id}}">{{$unit->unit}}</option>
                            @endif
                          @endforeach 
                        </select>
                      </div>
                      <div class="col-md-6">
                          <label for="inputcategory" class="control-label">Price<i style="color: red">*</i></label>
                          <input type="number" name="price" class="form-control" required placeholder="Enter" min="1" step="0.01" value="{{$base_unit->price}}">
                      </div>
                    </div>
                    <div class="form-group">
                        <label class="pull-right">
                          <input type="checkbox" id="check" class="flat-red" name="active" value="Y" @if($product->is_active == 'Y') checked @endif>
                          Is Active
                        </label> 
                      </div>

                  </div>

                  </div>
                  
                  <!-- /.box-body -->
                  <div class="box-footer">
                    <div class="pull-right">
                      <button type="submit" class="btn btn-info">Update</button>
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
  $(document).ready(function(){
      var cat_id = $('#cat_id').val();
      if (cat_id == 1) {
        $('#div_sqft').show();
        $('#div_size').show();
      }
      else
      {
        $('#div_sqft').hide();
        $('#div_size').hide();
      }
  });

  $("#category_id").change(function() {
    $("#sub").empty();
    var cat = $(this).val(); 
    var prd_id = $('#product_id').val();
    $.get("{{url('get/subCategory')}}",
      {option : cat},
      function(data){ console.log(data);
        $('#sub').append("<option value=''>" + "select" + "</option>");
        $.each(data[0], function(index, element){
          $('#sub').append("<option value='"+ element.id +"'>" + element.sub_category + "</option>");
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

  //add button

  $("#add").click(function(){
    var unit_id    = $("#other_unit").val();
    var unit       = $("#other_unit option:selected").text();
    var base_value = $("#base").val();
    var unitprice  = $("#unitprice").val();
    var cat_id     = $('#cat_id').val();
    var flag       = 0;
    
      $('.unit_table').find('td:nth-child(2)').each(function() {
         if($(this).text() == unit){
            flag = 1;
         } 
      }); 

      if(flag == 1){
        alert("Unit Allready Selected");
        $("#other_unit").val('');
        return 0;
      }
 
      if(unit_id == ""){
        alert("Kindly select a unit");
        return 0;
      }

      if(base_value == ""){
        alert("Kindly enter a value");
        return 0;
      }

      if(unitprice == ""){
        alert("Kindly enter a price");
        return 0;
      }
    
      $(".unit_table tr:last").after('<tr><td></td><td><input class="unit" type="hidden" name="unit[]" value="'+unit_id+'">'+unit+'</td><td><input type="hidden" name="base_value[]" value="'+base_value+'">'+base_value+'</td><td><input type="hidden" name="base_price[]" value="'+unitprice+'">'+unitprice+'</td><td><button type="button" class="btn-danger delete"><i class="fa fa-trash"></i></button></td></tr>')

      var i = 1;
      $('.unit_table td:first-child').each(function() {
        $(this).text(i); 
        i++;  
      });
      $("#other_unit").val('');
      $("#base").val('');    

  });

  //delete

  $(document).on("click",".delete",function() {
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

</script>
