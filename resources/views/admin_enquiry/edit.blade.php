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
          Enquiry
        </h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo url($text) ?>"><i class="fa fa-dashboard"></i>Enquiry </a></li>
          <li class="active">List</li>
        </ol>
      </section>

      @if(Session::has('save')) 
          <div class="box-header with-border" id="saveTarget">
              <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 Saved Successfully...!!
              </div>
          </div>
      @endif

      @if(Session::has('update')) 
          <div class="box-header with-border" id="saveTarget">
              <div class="alert alert-success alert-dismissible">
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

            <form class="form-horizontal form" action="" method="POST">
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
                  <tbody>
                    @foreach($enq_log as $log)
                    <tr>
                      <td>{{$log->status->status_name}}</td>
                      <td>{{date('d-m-Y',strtotime($log->status_date))}}</td>
                      <td>{{$log->status_remaks}}</td>
                    </tr>
                    @endforeach
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
        
        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <section class="col-lg-12">
            <div class="row">
      
              <div class="col-md-12">
                <!-- Custom Tabs (Pulled to the right) -->
                <div class="nav-tabs-custom">
                  <ul class="nav nav-tabs pull-right">
                    <li><a href="#tab_3-3" data-toggle="tab">Quotation</a></li>
                    <li><a href="#tab_1-1" data-toggle="tab">Products</a></li>
                    <li class="active" ><a href="#tab_2-2" data-toggle="tab">Enquiry Form</a></li>
                    <li class="pull-left header"><i class="fa fa-th"></i></li>
                  </ul>
                  <div class="tab-content">

                    <div class="tab-pane"  id="tab_1-1">
                      <div class="box-header">
                        <h3 class="box-title">Product List</h3>
                      </div>

                        <div class="box-body">

                          <div class="col-md-12">
                            <div class="col-md-5">
                              <input type="hidden" name="enq_id" id="enq_id" value="{{$enquiry->id}}">
                              <input type="hidden" name="enq_item_id" id="enq_item_id" value="">
                              <div class="form-group">
                                <label class="control-label">Category<i style="color: red;">*</i></label>
                                <div>
                                  <select class="form-control select2" name="category" id="category" required style="width: 100%;">
                                    <option  value="">--Select--</option>
                                    @foreach($category as $cat)
                                      <option  value="{{$cat->id}}">{{$cat->category_name}}</option>
                                    @endforeach
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-1">
                            </div>
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Product<i style="color: red;">*</i></label>
                                <input type="hidden" name="edit_product_id" id="edit_product_id" value="">
                                <div>
                                  <select class="form-control select2" name="product" id="product" required style="width: 100%;">
                                    <option  value="">--Select--</option>
                                  </select>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-12">
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Unit<i style="color: red;">*</i></label>
                                <div>
                                  <select class="form-control" name="unit" id="unit" required>
                                    <option  value="">--Select--</option>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-1">
                              <label></label>
                              <div>
                                <input type="hidden" name="unitpice" id="unitpice" value="" class="form-control">
                              </div>
                            </div>
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Quantity<i style="color: red;">*</i></label>
                                <div>
                                  <input type="number" name="quantity" id="quantity" required min="1" step="0.01" class="form-control">
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-12">
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Remarks</label>
                                <div>
                                  <textarea id="proremarks" name="proremarks" cols="64" rows="2"></textarea>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-1">
                            </div>
                            <div class="col-md-5">
                              <div class="form-group">
                              </div>
                            <br>
                            <input class="pull-right btn btn-primary" type="button" name="prosave" id="prosave" value="Save">
                            </div>
                          </div>

                        </div>

                      <div class="box">
                        <form action="{{route('admin.enquiry.save')}}" method="post">
                        <input type="hidden" name="enquiry_id" value="{{$enquiry->id}}">
                        <input type="hidden" name="from" value="{{$from}}">
                        <input type="hidden" name="to" value="{{$to}}">
                        <div class="box-body">
                          <table class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                <th>Sl No.</th>
                                <th>Product</th>
                                <th>Remarks</th>
                                <th>Qty</th>
                                <th>Unit</th>
                                <th>Price</th>
                                <th>Total</th>
                                <th>Edit</th>
                                <th>Delete</th>
                              </tr>
                            </thead>
                            <tbody id="probody">
                              <?php $c=0; $total=0;?>
                              @if($enqpro)
                                @foreach($enqpro as $epro)
                                  <tr id="row{{$epro->id}}">
                                    <td>{{++$c}}</td>
                                    <td>
                                      {{$epro->name}}
                                      <input type="hidden" class="product_id" name="product_id" value="{{$epro->product_id}}">
                                    </td>
                                    <td>{{$epro->remarks}}</td>
                                    <td>{{$epro->qty}}</td>
                                    <td>
                                      <input type="hidden" class="unit_id" name="unit_id" value="{{$epro->unit_id}}">
                                      {{$epro->unit}}
                                    </td>
                                    <td>{{$epro->unit_price}}</td>
                                    <td>
                                      {{$epro->total}}
                                      <input type="hidden" name="total" class="total" value="{{$epro->total}}">
                                    </td>
                                    <td><button type="button" class="fa fa-pencil btn btn-primary edit" id="{{$epro->id}}"></button></td>
                                    <td><button type="button" class="fa fa-trash btn btn-danger delete" id="{{$epro->id}}"></button></td>
                                  </tr>
                                @endforeach
                              @endif
                            </tbody>
                            <tbody>
                              <tr>
                                <td colspan="6"><label class="pull-right">Total :</label></td>
                                <td colspan="3"><label id="labtotal">{{$enquiry->total}}</label><input type="hidden" name="total" value="{{$enquiry->total}}" id="total" class="calculate" required></td>
                              </tr>
                              <tr>
                                <td colspan="6"><label class="pull-right">Discount :</label></td>
                                <td colspan="3"><input type="number" name="discount" id="discount" min="0" step="0.01" class="form-control calculate" value="{{$enquiry->discount_value}}" style="width: 30%;"></td>
                              </tr>
                              <tr>
                                <td colspan="6"><label class="pull-right">Sub Total :</label></td>
                                <td colspan="3"><label id="labsubtotal">{{$enquiry->sub_total}}</label><input type="hidden" name="subtotal" value="{{$enquiry->sub_total}}" id="subtotal" class="calculate"></td>
                              </tr>
                              <tr>
                                <td colspan="6"><input type="number" id="tax" name="tax" min="0" step="0.01" class="form-control pull-right calculate" style="width: 15%;" value="{{$enquiry->tax_per}}"><label class="pull-right">Tax % : &nbsp </label></td>
                                <td colspan="3"><label id="labtaxamt">{{$enquiry->tax_amount}}</label><input type="hidden" name="taxamt" value="{{$enquiry->tax_amount}}" id="taxamt" class="calculate"></td>
                              </tr>
                              <tr>
                                <td colspan="6"><label class="pull-right">Grand Total :</label></td>
                                <td colspan="3"><label id="labgrandtot">{{$enquiry->grand_total}}</label><input type="hidden" name="grandtot" value="{{$enquiry->grand_total}}" id="grandtot" class="calculate"></td>
                              </tr>
                            </tbody>
                          </table>
                              <div class="box-footer">
                                  <div class="pull-right">
                                    <button type="submit" class="btn btn-info">Save</button>
                                  </div>
                              </div>
                        </div>
                        </form>
                      </div>
                    </div>
                    <!-- /.tab-pane -->

                    
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="tab_2-2">
                      <div class="box-header">
                        <h3 class="box-title">Enquiry Form</h3>
                      </div>
                        <!-- form start -->
                      <form class="form-horizontal" action="{{route('admin.enquiry.update')}}" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}"> 
                        <input type="hidden" name="edit_id" value="{{$enquiry->id}}">
                        <input type="hidden" name="from" id="from" value="{{$from}}">
                        <input type="hidden" name="to" id="to" value="{{$to}}">
                        <div class="box-body">

                          <div class="col-md-12">
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Staff Name<font style="color: red;">*</font></label>
                                <div>
                                  <select class="form-control" name="staff" id="staff" required="true">
                                    <option value="">--Select--</option>
                                    @if($staff)
                                      @foreach($staff as $stf)
                                        <option value="{{$stf->id}}" @if($stf->id == $enquiry->user_id) selected @endif>{{$stf->name}}</option>
                                      @endforeach
                                    @endif
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                            </div>
                          </div>

                          <div class="col-md-12">
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Customer Search</label>
                                <div>
                                  <input class="form-control" type="text" name="search" id="search" class="form-control customer_number" list="searchlist"  placeholder="Name / Mobile" autofocus="true" autocomplete="off">
                                    <datalist id="searchlist">
                                      <option></option>
                                    </datalist>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-1">
                              <div>
                              <br>
                              <a onclick="myFunction()" class="btn btn-default fa fa-copy" style="margin-top: 9px;"></a>
                              </div>
                            </div>
                            <div class="col-md-5">
                              <div class="form-group">
                                <br>
                                @foreach($type as $typ)
                                  <label>
                                    <input type="radio" name="enqtype" value="{{$typ->id}}" @if($enquiry->enq_type == $typ->id) checked @endif>
                                    {{$typ->name}}
                                  </label>
                                  &nbsp
                                @endforeach
                              </div>
                            </div>
                          </div>

                          <div class="col-md-12">
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Customer Name<i style="color: red">*</i></label>
                                <div>
                                  <input type="text" name="name" id="name" class="form-control" autocomplete="off" value="{{$enquiry->customer->customer_name}}" required>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-1">
                            </div>
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Phone<i style="color: red">*</i></label>
                                <div>
                                  <input type="number" name="phone" id="phone" class="form-control" autocomplete="off" value="{{$enquiry->customer->customer_phone}}" required>
                                </div>
                              </div>
                            </div>
                          </div>
                          
                          <div class="col-md-12">
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Sales Lead<i style="color: red">*</i></label>
                                <div>
                                  <select class="form-control" name="lead" id="lead" required>
                                    <option value="">--Select--</option>
                                    @foreach($lead_type as $ltype)
                                      @if($ltype->id == $enquiry->lead_type_id)
                                        <option value="{{$ltype->id}}" selected>{{$ltype->name}}</option>
                                      @else
                                        <option value="{{$ltype->id}}">{{$ltype->name}}</option>
                                      @endif
                                    @endforeach
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-1">
                            </div>
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Status<i style="color: red">*</i></label>
                                <div>
                                  <select class="form-control" name="status" id="status" required>
                                    <option value="">--Select--</option>
                                    @foreach($status as $stat)
                                      @if($stat->id == $enquiry->enquiry_status_id)
                                        <option value="{{$stat->id}}" selected>{{$stat->status_name}}</option>
                                      @else
                                        <option value="{{$stat->id}}">{{$stat->status_name}}</option>
                                      @endif
                                    @endforeach
                                  </select>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-12">
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Remarks</label>
                                <div>
                                  <textarea cols="67" rows="2" name="remarks"></textarea>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-1">
                            </div>
                            <div class="col-md-5">
                              <div class="form-group" id="flw" style="display: none;">
                                <label class="control-label">Followup Date<i style="color: red">*</i></label>
                                <div>
                                  <input type="text" name="flwdate" id="flwdate" class="form-control datepicker check" @if($flwdate != null) value="{{date('d-m-yy',strtotime($flwdate))}}" @else value="{{date('d-m-yy')}}" @endif required>
                                </div>
                              </div>
                              <div class="form-group" id="del" style="display: none;">
                                <label class="control-label">Delivery Date<i style="color: red">*</i></label>
                                <div>
                                  <input type="text" name="deliverydate" id="deliverydate" class="form-control datepicker check" @if($dlvdate != null) value="{{date('d-m-yy',strtotime($dlvdate))}}" @else value="{{date('d-m-yy')}}" @endif required>
                                </div>
                              </div>
                            </div>
                          </div>

                        </div>
                        
                        <!-- /.box-body -->
                        <div class="box-footer">
                          <div class="col-md-2">
                            <button type="reset" class="btn btn-primary" data-toggle="modal" data-target="#modal-log">Log</button>
                          </div>
                          <div class="pull-right">
                            <a href="<?php echo url($text) ?>" class="btn btn-default" id="back">Back</a>
                            <button type="submit" class="btn btn-info">Save</button>
                          </div>
                        </div>
                        <!-- /.box-footer -->
                      </form>
                    </div>
                    <!-- /.tab-pane -->
                    
                    <div class="tab-pane"  id="tab_3-3">
                      <div class="box-header">
                        <h3 class="box-title">Quotation List</h3>
                      </div>

                        <div class="box-body">

                          <div class="col-md-12">
                            <div class="col-md-5">
                              <input type="hidden" name="quot_enq_id" id="quot_enq_id" value="{{$enquiry->id}}">
                              <input type="hidden" name="enq_item_id" id="enq_item_id" value="">
                              <div class="form-group">
                                <label class="control-label">Quotation No.<i style="color: red;">*</i></label>
                                <div>
                                  <input type="text" name="quot_no" id="quot_no" class="form-control">
                                </div>
                              </div>
                            </div>
                            <div class="col-md-1">
                            </div>
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Date<i style="color: red;">*</i></label>
                                <input type="text" name="quot_date" id="quot_date" class="form-control datepicker" value="{{date('d-m-yy')}}" required>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-12">
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Price<i style="color: red;">*</i></label>
                                <div>
                                  <input class="form-control" type="number" name="quot_price" id="quot_price" step="0.01">
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <br>
                                <input class="pull-right btn btn-primary" type="button" name="quotsave" id="quotsave" value="Save">
                            </div>
                            
                          </div>
                        </div>
                        
                        <div class="box"> 
                        <div class="box-body">
                          <table class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                <th>Sl No.</th>
                                <th>Quotation No.</th>
                                <th>Date</th>
                                <th>Price</th>
                              </tr>
                            </thead>
                            <tbody id="qutbody">
                              <?php $c=0; $total=0;?>
                              @if($quotation)
                                @foreach($quotation as $quo_t)
                                  <tr>
                                    <td>{{++$c}}</td>
                                    <td>{{$quo_t->quotation_no}}</td>
                                    <td>{{date('d-m-Y',strtotime($quo_t->date))}}</td>
                                    <td>{{$quo_t->price}}</td>
                                  </tr>
                                @endforeach
                              @endif
                            </tbody>
                          </table>
                        </div>
                      </div>

                 
                    </div>

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
<!-- Select2 -->
<script src="{{asset('lte/bower_components/select2/dist/js/select2.full.min.js')}}"></script>
</body>

<script type="text/javascript">
  $('.datepicker').datepicker({
      autoclose: true,
      format: 'dd-mm-yyyy'
    })
</script>

<script type="text/javascript">
  $('.select2').select2();
   
  $(function () {
      $('#example1').DataTable()
    
  })

  $(document).ready(function(){
    var d     = new Date();
    var month = d.getMonth()+1;
    var day   = d.getDate();

    var output = day + '-' +
      (month<10 ? '0' : '') + month + '-' +
      (day<10 ? '0' : '') + d.getFullYear();
    $('.check').datepicker('setStartDate', output);
  });

  function myFunction() {
    /* Get the text field */
    var copyText = document.getElementById("search");

    /* Select the text field */
    copyText.select();
    copyText.setSelectionRange(0, 99999); /*For mobile devices*/

    /* Copy the text inside the text field */
    document.execCommand("copy");

    /* Alert the copied text */
    alert("Copied the text: " + copyText.value);
    
  }

  $(document).ready(function(){
    if($('#status').find('option:selected').text() == 'FOLLOW UP' || $('#status').find('option:selected').text() == 'SALE CLOSE'){
        $("#status").trigger("change");
    }
  });

  $(document).on('change','#status',function(){
    if($(this).find('option:selected').text() == 'FOLLOW UP'){
        $('#flw').show();
        $('#del').hide();
    }
    else if($(this).find('option:selected').text() == 'SALE CLOSE'){
        $('#del').show();
        $('#flw').hide();
    }
    else{
        $('#flw').hide();
        $('#del').hide();
    }
  });

  $(document).on('input','#search',function(){
      var itemList = $("#searchlist");
      itemList.empty();
      var name = $(this).val();
      if(name.length >= 3){     
          $.get("{{url('admin/customer/list')}}", {term:name}, function(resNames) {
              itemList.empty();
              if(resNames.length) {
                  $.each(resNames, function(index,element) {
                      var info = element.customer_name+'/'+element.customer_phone;
                      var opt  = $("<option></option>").attr("value", info);
                      itemList.append(opt);
                  });
              }     
          },"json");
      }

      $("#search").trigger("change");
  });

  $(document).on('input','#phone',function(){
      var num = $(this).val();
      if(num.length > 9){
        $.get("{{url('admin/check/phone')}}", {num}, function(data) {
            if(data != ''){
                alert("Number allready used by another customer.");
                $('#phone').val('');
            }
        });
      }
  });

  $(document).on('change','#search',function(){
    if($(this).val().length >= 3){
        var value = $(this).val();
        $.get("{{url('admin/customer/show')}}", {value}, function(data) {
            if(data.length != 0){
                $("#name").val(data.customer_name);
                $("#phone").val(data.customer_phone);
            }
            else{
                $("#name").val('');
                $("#phone").val('');
            }
        });
    }
  });
  
  $(document).on('change','#category',function(){
    var cat = $(this).val();
    $('#product').empty();
    $('#product').append('<option value="">--Select--</option');
    $('#product').val('');
    $('#unit').empty();
    $('#unit').append('<option value="">--Select--</option');
    $('#unit').val('');
    $('#unitpice').val('');
    $('#quantity').val('');
    $('#proremarks').val('');
    if(cat != ''){
      $.get("{{url('admin/categort/products')}}", {cat}, function(data) {
        $.each(data,function(index,element){
          $('#product').append('<option value="'+element.id+'">'+element.name+'</option');
        });
        $('#product').select2();
      });
    }
  });

  $(document).on('change','#product',function(){
    var pro = $(this).val();
    $('#unit').empty();
    $('#unit').val('');
    $('#unit').append('<option value="">--Select--</option');
    $('#unitpice').val('');
    $('#quantity').val('');
    $('#proremarks').val('');
    if(pro != ''){
      $.get("{{url('admin/product/units')}}", {pro}, function(data) {
        $.each(data,function(index,element){
          $('#unit').append('<option value="'+element.unit_id+'">'+element.unit+'</option');
        });
      });
    }
  });

  $(document).on('change','#unit',function(){
    var unit            = $(this).val();
    var pro             = $('#product').val();
    var enq_item_id     = $('#enq_item_id').val();
    var edit_product_id = $('#edit_product_id').val();
    var flag            = 0;

    $('#unitpice').val('');

    /*$('#probody tr').each(function() {
      if($(this).find('td:nth-child(2) .product_id').val() == pro && $(this).find('td:nth-child(5) .unit_id').val() == unit){
        flag++;
      }
    });*/

    if(enq_item_id == ''){
      $('#probody tr').each(function() {
        if($(this).find('td:nth-child(2) .product_id').val() == pro && $(this).find('td:nth-child(5) .unit_id').val() == unit){
          alert("Product with same unit allready selected.");
          $('#unit').val('');
        }
      });
    }
    else{
      if(edit_product_id == pro){
          $('#probody tr').each(function() {
            if($(this).find('td:nth-child(2) .product_id').val() == pro && $(this).find('td:nth-child(5) .unit_id').val() == unit){
              flag++;
            }
          });
          if(flag > 2){
            alert("Product with same unit allready selected.");
            $('#unit').val('');
          }
      }
      else{
        $('#probody tr').each(function() {
          if($(this).find('td:nth-child(2) .product_id').val() == pro && $(this).find('td:nth-child(5) .unit_id').val() == unit){
            alert("Product with same unit allready selected.");
            $('#unit').val('');
          }
        });
      }
      $('#edit_product_id').val('');
    }

    if(unit != ''){
      $.get("{{url('admin/unit/price')}}", {unit,pro}, function(data) {
        console.log(data);
        if(data != ''){
          $('#unitpice').val(data.price);
        }
        else{
          $('#unitpice').val(0); 
        }
      });
    }
  });

  $(document).on('click','#prosave',function(){
    var cat         = $('#category').val();
    var pro         = $('#product').val();
    var unit        = $('#unit').val();
    var qty         = $('#quantity').val();
    var remarks     = $('#proremarks').val();
    var enq_id      = $('#enq_id').val();
    var unitpice    = $('#unitpice').val();
    var enq_item_id = $('#enq_item_id').val();

    if(cat == ''){
      alert("Select Category.");
      return 0;
    }
    if(pro == ''){
      alert("Select Product.");
      return 0;
    }
    if(unit == ''){
      alert("Select Unit.");
      return 0;
    }
    if(qty == ''){
      alert("Enter Quantity.");
      return 0;
    }

    $.get("{{url('admin/product/store')}}", {cat,pro,unit,qty,remarks,enq_id,unitpice,enq_item_id}, function(data) {
      if(data != ''){
        var len = parseInt($('#probody tr').length)+1;
        if(data.remarks == null){
          data.remarks = '';
        }
        if(enq_item_id == ''){
          $('#probody').append('<tr id="row'+data.id+'"><td>'+len+'</td><td>'+data.name+'<input type="hidden" class="product_id" name="product_id" value="'+data.product_id+'"></td><td>'+data.remarks+'</td><td>'+data.qty+'</td><td>'+data.unit+'<input type="hidden" class="unit_id" name="unit_id" value="'+data.unit_id+'"></td><td>'+data.unit_price+'</td><td>'+data.total+'<input type="hidden" name="total" class="total" value="'+data.total+'"></td><td><button type="button" class="fa fa-pencil btn btn-primary edit" id="'+data.id+'"></button></td><td><button type="button" class="fa fa-trash btn btn-danger delete" id="'+data.id+'"></button></td></tr>');
        }
        else{
          $('#row'+enq_item_id).remove();
          
          $('#probody').append('<tr id="row'+data.id+'"><td>'+len+'</td><td>'+data.name+'<input type="hidden" class="product_id" name="product_id" value="'+data.product_id+'"></td><td>'+data.remarks+'</td><td>'+data.qty+'</td><td>'+data.unit+'<input type="hidden" class="unit_id" name="unit_id" value="'+data.unit_id+'"></td><td>'+data.unit_price+'</td><td>'+data.total+'<input type="hidden" name="total" class="total" value="'+data.total+'"></td><td><button type="button" class="fa fa-pencil btn btn-primary edit" id="'+data.id+'"></button></td><td><button type="button" class="fa fa-trash btn btn-danger delete" id="'+data.id+'"></button></td></tr>');

          var i = 1;
          $('#probody td:first-child').each(function() {
            $(this).text(i); 
            i++;
          });
        }
      }
      $('.calculate').trigger('blur');
    });
    $('#product').val('').select2();
    $('#unit').empty();
    $('#unit').append('<option value="">--Select--</option');
    $('#unit').val('');
    $('#quantity').val('');
    $('#proremarks').val('');
    $('#enq_item_id').val('');
  });

  $(document).on('click','.edit',function(){
    /*$(this).closest('tr').remove();
    var i = 1;
    $('#probody td:first-child').each(function() {
      $(this).text(i); 
      i++;
    });*/
    var id = $(this).attr('id');
    $('#enq_item_id').val(id);
    $.get("{{url('admin/product/edit')}}", {id}, function(data) {
      $('#category').val(data[1]).select2().focus();
      $('#product').empty();
      $('#product').append('<option value="">--Select--</option');
      $('#unit').empty();
      $('#unit').append('<option value="">--Select--</option');
      $('#unitpice').val(data[0].unit_price);
      $('#quantity').val(data[0].qty);
      $('#proremarks').val(data[0].remarks);
      $('#edit_product_id').val(data[0].product_id);

      if(data[2] != ''){
        $.each(data[2],function(index,element){
          if(element.id == data[0].product_id){
            $('#product').append('<option value="'+element.id+'" selected>'+element.name+'</option');
          }
          else{
            $('#product').append('<option value="'+element.id+'">'+element.name+'</option');
          }
        });
        $('#product').select2();
      }
      if(data[3] != ''){
        $.each(data[3],function(index,element){
          if(element.unit_id == data[0].unit_id){
            $('#unit').append('<option value="'+element.unit_id+'" selected>'+element.unit+'</option');
          }
          else{
            $('#unit').append('<option value="'+element.unit_id+'">'+element.unit+'</option');
          }
        });
      }
    });
  });

  $(document).on('click','.delete',function(){
    if(confirm("Do you want to delete?") == true){
      $(this).closest('tr').remove();
      var i = 1;
      $('#probody td:first-child').each(function() {
        $(this).text(i); 
        i++;
      });
      var id = $(this).attr('id');
      $.get("{{url('admin/product/delete')}}", {id}, function(data) {
        alert("Product deleted.");
      });
      $('.calculate').trigger('blur');
    }
  });

  $(document).on('blur','.calculate',function(){
      var tot = 0;
      $('.total').each(function(){
        tot = parseFloat(tot) + parseFloat($(this).val());
      });
      $('#labtotal').text(tot);
      $('#total').val(tot);

      var discount = $('#discount').val();
      if(discount != ''){
        if(parseFloat(tot) < parseFloat(discount)){
          alert("Discount amount greater than total.");
          $('#discount').val('');
          /*$('.calculate').trigger('blur');*/
          return 0;
        }
      }
      else{
        discount = 0;
      }
      var subtotal = parseFloat(tot) - parseFloat(discount);
      $('#labsubtotal').text(subtotal);
      $('#subtotal').val(subtotal);
      var tax = $('#tax').val();
      tax_amt = 0;
      if(tax != ''){
        var tax_amt = parseFloat(subtotal)*parseFloat(tax)/100;
        tax_amt = tax_amt.toFixed(2);
      }
      $('#labtaxamt').text(tax_amt);
      $('#taxamt').val(tax_amt);
      var grandtot = parseFloat(subtotal)+parseFloat(tax_amt);
      $('#labgrandtot').text(grandtot);
      $('#grandtot').val(grandtot);
  });

  $(document).on('blur','#quot_no',function(){
    var qno = $(this).val();
    $.get("{{url('admin/quotation/check')}}", {qno}, function(data) {
      if(data > 0){
        alert("Quotation number already used");
        $('#quot_no').val('');
      }
    });
  });   
  
  $(document).on('click','#quotsave',function(){
    var quot_no     = $('#quot_no').val();
    var quot_date   = $('#quot_date').val();
    var quot_price  = $('#quot_price').val();
    var quot_enq_id = $('#quot_enq_id').val();

    if(quot_no == ''){
      alert("Enter quotation No.");
      return 0;
    }

    if(quot_date == ''){
      alert("Enter quotation date.");
      return 0;
    }

    if(quot_price == ''){
      alert("Enter quotation price.");
      return 0;
    }

    $.get("{{url('admin/quotation/store')}}", {quot_no,quot_date,quot_price,quot_enq_id}, function(data) {
      if(data != ''){
        $('#qutbody').empty();
        var len = 1;
        $.each(data,function(index,element){
          var qdate = $.datepicker.formatDate( "dd-mm-yy", new Date(element.date));
          $('#qutbody').append('<tr><td>'+len+'</td><td>'+element.quotation_no+'</td><td>'+qdate+'</td><td>'+element.price+'</td></tr>');
          len++;
        });
      }
    });
    $('#quot_no').val('');
    $('#quot_date').val('');
    $('#quot_price').val('');
  });

</script>
</script>
</html>
