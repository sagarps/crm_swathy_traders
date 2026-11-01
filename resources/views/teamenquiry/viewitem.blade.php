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
          Team Enquiry
        </h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo url($text) ?>"><i class="fa fa-dashboard"></i>Team Enquiry </a></li>
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
 <!-- add place modal  -->

                    <div class="modal modal-default fade" id="modal-place">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Add Place</h4>
                          </div>

                          <form class="form-horizontal form" action="" method="">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="col-md-12">
                                <div class="col-md-5">
                                  <div class="form-group">
                                    <label class="control-label">Place<i style="color: red">*</i></label>
                                    <div>
                                    <input type="text" name="nplace" id="nplace" class="form-control" autocomplete="off" required>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-md-1">
                                </div>
                                
                            </div>
                          <div class="modal-footer">
                          <button type="submit" class="btn btn-info" id="add">Add</button>

                            <button type="button" class="btn btn-info pull-right" data-dismiss="modal">Close</button>
                          </div>
                        </form>
                        </div>
                      </div>
                    </div>
                  <!-- add place modal ends -->




                      <!-- Add functon type modal -->
                      <div class="modal modal-default fade" id="modal-function">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Add Function</h4>
                          </div>

                          <form class="form-horizontal form" action="" method="">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="col-md-12">
                                <div class="col-md-5">
                                  <div class="form-group">
                                    <label class="control-label">Function Type<i style="color: red">*</i></label>
                                    <div>
                                    <input type="text" name="nfunction" id="nfunction" class="form-control" autocomplete="off" required>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-md-1">
                                </div>
                                
                            </div>
                          <div class="modal-footer">
                          <button type="submit" class="btn btn-info" id="add_function">Add</button>

                            <button type="button" class="btn btn-info pull-right" data-dismiss="modal">Close</button>
                          </div>
                        </form>
                        </div>
                      </div>
                    </div>

                  <!-- add function type modal ends -->
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
                    <!-- <li><a href="#tab_1-1" data-toggle="tab">Products</a></li> -->
                    <li class="active" ><a href="#tab_2-2" data-toggle="tab">Enquiry Form</a></li>
                    <li class="pull-left header"><i class="fa fa-th"></i></li>
                  </ul>
                  <div class="tab-content">

                    <div class="tab-pane"  id="tab_1-1">
                      <div class="box-header">
                        <h3 class="box-title">Product List</h3>
                      </div>

                      <div class="box">
                        <form action="{{route('enquiry.save')}}" method="post">
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
                                  </tr>
                                @endforeach
                              @endif
                            </tbody>
                            <tbody>
                              <tr>
                                <td colspan="6"><label class="pull-right">Total :</label></td>
                                <td colspan="1"><label id="labtotal">{{$enquiry->total}}</label><input type="hidden" name="total" value="{{$enquiry->total}}" id="total" class="calculate" required></td>
                              </tr>
                              <tr>
                                <td colspan="6"><label class="pull-right">Discount :</label></td>
                                <td colspan="1">{{$enquiry->discount_value}}</td>
                              </tr>
                              <tr>
                                <td colspan="6"><label class="pull-right">Sub Total :</label></td>
                                <td colspan="1"><label id="labsubtotal">{{$enquiry->sub_total}}</label><input type="hidden" name="subtotal" value="{{$enquiry->sub_total}}" id="subtotal" class="calculate"></td>
                              </tr>
                              <tr>
                                <td colspan="6"><label class="pull-right">Tax % : &nbsp {{$enquiry->tax_per}}</label></td>
                                <td colspan="1"><label id="labtaxamt">{{$enquiry->tax_amount}}</label></td>
                              </tr>
                              <tr>
                                <td colspan="6"><label class="pull-right">Grand Total :</label></td>
                                <td colspan="1"><label id="labgrandtot">{{$enquiry->grand_total}}</label><input type="hidden" name="grandtot" value="{{$enquiry->grand_total}}" id="grandtot" class="calculate"></td>
                              </tr>
                            </tbody>
                          </table>
                              <div class="box-footer">
                                  <div class="pull-right">
                                  </div>
                              </div>
                        </div>
                        </form>
                      </div>
                    </div>
                    <!-- /.tab-pane -->

                    
                    <!-- /.tab-pane -->
                    <div) class="tab-pane active" id="tab_2-2">
                      <div class="box-header">
                        <h3 class="box-title">Enquiry Form</h3>
                        <h5>Enquiry No: {{$enquiry->enq_no}}</h5>

                      </div>
                        <!-- form start -->
                      <form class="form-horizontal" action="{{route('enquiry.update')}}" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}"> 
                        <input type="hidden" name="edit_id" value="{{$enquiry->id}}">
                        <input type="hidden" name="from" id="from" value="{{$from}}">
                        <input type="hidden" name="to" id="to" value="{{$to}}">
                        <input type="hidden" name="index" id="index" value="{{$index}}">

                        <div class="box-body">

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
                              </div>
                            </div>
                            <div class="col-md-5">
                              <div class="form-group">
                                <br>
                                @foreach($type as $typ)
                                  <label>
                                    <input type="radio" name="enqtype" id="enqtype" value="{{$typ->id}}" @if($enquiry->enq_type == $typ->id) checked @endif>
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
                                  <input type="number" name="phone" id="phone" class="form-control" autocomplete="off" value="{{$enquiry->customer->customer_phone}}" required >
                                </div>
                              </div>
                            </div>
                          </div>
                          
                          <div class="col-md-12">
                          <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Alternative Phone</label>
                                <div>
                                  <input type="number" name="alt_phone" id="alt_phone" class="form-control" autocomplete="off" value="{{$enquiry->customer->alternate_phone}}">
                                </div>
                              </div>
                            </div>
                            <div class="col-md-1">
                            </div>
                           
                            <div class="col-md-5">
                          <div class="form-group">
                            <label class="control-label">Coupon No</label>
                            <div>
                            <input type="text" name="coupon_no" id="coupon_no" class="form-control" value="{{$enquiry->coupon_no}}">
                            </div>
                          </div>
                        </div>
                            
                          </div>
                          <div class="col-md-12">
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Lead Stage<i style="color: red">*</i></label>
                                <div>
                                  <select class="form-control" name="lead" id="lead" required >
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
                                <label class="control-label">Lead Status<i style="color: red">*</i></label>
                                <div>
                                  <select class="form-control" name="status" id="status" required >
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
                          {{$enquiry->place_id}}
                          <div class="col-md-12">
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Place<i style="color: red">*</i></label>
                                <div>
                                <select class="form-control" name="place" id="place" required>
                                    <option value="">--Select--</option>
                                    @foreach($places as $plc)
                                    @if($plc->id == $place_id) 
                                    <option value="{{$plc->id}}" selected>{{$plc->place}}</option>
                                    @else
                                      <option value="{{$plc->id}}">{{$plc->place}}</option>
                                      @endif
                                                    @endforeach
                                  </select>                                 </div>
                              </div>
                            </div>
                            <div class="col-md-1">
                              <div>
                              <br>
                              <i class="fa fa-plus-circle" aria-hidden="true" style="color:#3c8dbc;font-size:30px;position:relative;margin-top: 9px;" data-toggle="modal" data-target="#modal-place"></i>
                              </div>
                            </div>
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Function Date<i style="color: red">*</i></label>
                                <div>
                                  <input type="text" name="func_date" id="func_date" class="form-control datepicker" autocomplete="off" value="{{$enquiry->function_date}}" required >
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-12">
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Gender<i style="color: red">*</i></label>
                                <div>
                                <br>
                                <label>
                                    <input type="radio" name="gen" value="Male" @if($enquiry->gender == "Male") checked @endif > Male
                                    <input type="radio" name="gen" value="Female" @if($enquiry->gender == "Female") checked @endif > Female
                                </label>
                              </div>
                              </div>
                            </div>
                            <div class="col-md-1">
                            </div>

                        
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Function Type<i style="color: red">*</i></label>
                                <div>
                                <select class="form-control" name="func_type" id="func_type" required >
                                    <option value="">--Select--</option>
                                    @foreach($func_type as $type)
                                    @if($type->id == $enquiry->function_type)
                                        <option value="{{$type->id}}" selected>{{$type->function_type}}</option>
                                      @else
                                      <option value="{{$type->id}}">{{$type->function_type}}</option>
                                      @endif
                                    @endforeach
                                  </select>  
                              </div>
                              </div>
                            </div>
                            <div class="col-md-1">
                              <div>
                              <br>
                              <i class="fa fa-plus-circle" aria-hidden="true" style="color:#3c8dbc;font-size:30px;position:relative;margin-top: 9px;" data-toggle="modal" data-target="#modal-function"></i>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-12" id="associate" style="display:none">
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Influencer Type<i style="color: red">*</i></label>
                                <div>
                                <select class="form-control" name="inf_type" id="inf_type">
                                    <option value="">--Select--</option>
                                    @foreach($inf_type as $type)
                                    @if($type->id == $enquiry->influencer_type)
                                        <option value="{{$type->id}}" selected>{{$type->influencer_type}}</option>
                                      @else
                                      <option value="{{$type->id}}">{{$type->influencer_type}}</option>
                                      @endif                                    @endforeach
                                  </select>  
                                 </div>
                              </div>
                            </div>
                            <div class="col-md-1">
                            </div>
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Influencer Name<i style="color: red">*</i></label>
                                <div>
                                <select class="form-control" name="inf_name" id="inf_name">
                                    <option value="">--Select--</option>
                                @foreach($associates as $ass)
                                    @if($ass->id == $enquiry->associate_id)
                                        <option value="{{$ass->id}}" selected>{{$ass->associate_name}}</option>
                                      @else
                                      <option value="{{$ass->id}}">{{$ass->associate_name}}</option>
                                      @endif 
                                      @endforeach   
                                      </select>  
            
                                 </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-12" style="display:none" id="reference">
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Reference Name<i style="color: red">*</i></label>
                                <div>
                                <input type="text" name="ref_name" id="ref_name" class="form-control" value="{{$enquiry->reference_name}}">
                                 </div>
                              </div>
                            </div>
                            <div class="col-md-1">
                            </div>
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Reference Phone<i style="color: red">*</i></label>
                                <div>
                                <input type="number" name="ref_phone" id="ref_phone" class="form-control" value="{{$enquiry->reference_phone}}">
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-12" style="display:none" id="purchase">
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Purchase Date<i style="color: red">*</i></label>
                                <div>
                                <input type="text" name="purchase_date" id="purchase_date" class="form-control datepicker" value="{{date('d-m-Y', strtotime($enquiry->purchase_date))}}" required>  
                                                               </div>
                              </div>
                            </div>
                            <div class="col-md-1">
                            </div>
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Purchase Value<i style="color: red">*</i></label>
                                <div>
                                <input type="number" step="any" name="purchase_value" id="purchase_value" class="form-control" value="{{$enquiry->purchase_value}}">                                </div>
                              </div>
                            </div>
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Bill No<i style="color: red">*</i></label>
                                <div>
                                <input type="text" name="bill_no" id="bill_no" class="form-control" value="{{$enquiry->bill_no}}">                                </div>
                              </div>
                            </div>
                          </div>
  
                          <div class="col-md-5">
                              <div class="form-group" id="employee">
                                <label class="control-label">Staff Name<i style="color: red">*</i></label>
                                <div>
                                <select class="form-control" name="staff_name" id="staff_name">
                                <option value="">--Select--</option>
                                @foreach($employee as $emp)
                                      @if($emp->id == $enquiry->staff_id)
                                        <option value="{{$emp->id}}" selected>{{$emp->name}}</option>
                                      @else
                                        <option value="{{$emp->id}}">{{$emp->name}}</option>
                                      @endif
                                    @endforeach                 
                                    </select>              

                                 </div>
                            </div>
                       
                      </div>
                      <div class="col-md-12">

                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Remarks</label>
                                <div>
                                <textarea cols="67" rows="2" name="remarks" id="remarks" class="form-control">{{$remarks}}</textarea>                                </div>
                              </div>
                            </div>
                            <div class="col-md-1">
                            </div>
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Address</label><i style="color: red">*</i>
                                <div>
                                <textarea cols="67" rows="2" name="address" class="form-control" required>{{$enquiry->address}}</textarea>                                 </div>
                              </div>
                            </div>
                            <div class="col-md-1">
                            </div>
                           
                          </div>
                          
                            <div class="col-md-5">
                              <div class="form-group" id="flw" style="display: none;">
                                <label class="control-label">Followup Date<i style="color: red">*</i></label>
                                <div>
                                  <input type="date" name="flwdate" id="flwdate" class="form-control" @if($flwdate != null) value="{{$flwdate}}" @else value="{{date('Y-m-d')}}" @endif required >
                                </div>
                              </div>
                              <div class="form-group" id="del" style="display: none;">
                                <label class="control-label">Delivery Date<i style="color: red">*</i></label>
                                <div>
                                  <input type="date" name="deliverydate" id="deliverydate" class="form-control" @if($dlvdate != null) value="{{$dlvdate}}" @else value="{{date('Y-m-d')}}" @endif required >
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

                          @if($enquiry->user_id == Auth::user()->id && !($enquiry->enquiry_status_id ==3 || $enquiry->enquiry_status_id ==4 || $enquiry->enquiry_status_id ==5))

                          <button type="submit" class="btn btn-info" id="update">Update</button>
                          @endif

                          <a href="<?php echo url($text) ?>" class="btn btn-default" id="back">Back</a>
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
    if($("input[name='enqtype']:checked").val() == 9) {
      $('#reference').show();
      document.getElementById("ref_name").required = true;
      document.getElementById("ref_phone").required = true;
    }
    else {
      $('#reference').hide();
      document.getElementById("ref_name").required = false;
      document.getElementById("ref_phone").required = false;
    }
    if($("input[name='enqtype']:checked").val() == 6 ||$("input[name='enqtype']:checked").val() == 7 || $("input[name='enqtype']:checked").val() == 9 ) {
     
      $('#associate').hide();
      document.getElementById("inf_type").required = false;
      document.getElementById("inf_name").required = false;
    }
    else {
      $('#associate').show();
      document.getElementById("inf_type").required = true;
      document.getElementById("inf_name").required = true;
    }
    if($("input[name='enqtype']:checked").val() == 6) {
      $('#employee').hide();
     
      document.getElementById("staff_name").required = false;
    }
    else {
      $('#employee').show();

      document.getElementById("staff_name").required = true;
    }
    
    var status =$('#status').val() ;
    if(status == 4 || status == 5) {
        document.getElementById("remarks").required = true;

      }
      else {
        document.getElementById("remarks").required = false;

      }
    if($('#status').find('option:selected').text() == 'FOLLOW UP' || $('#status').find('option:selected').text() == 'SALE CLOSE'){
        $("#status").trigger("change");
    }
    if($('#status').find('option:selected').text() == 'SALE CLOSE') {
      $('#purchase').show();
        document.getElementById("purchase_date").required = true;
        document.getElementById("purchase_value").required = true;
        document.getElementById("bill_no").required = true;

    }
    else {
      $('#purchase').hide();
        document.getElementById("purchase_date").required = false;
        document.getElementById("purchase_value").required = false;
        document.getElementById("bill_no").required = false;

    }
    
    var d     = new Date();
    var month = d.getMonth()+1;
    var day   = d.getDate();

    var output = day + '-' +
      (month<10 ? '0' : '') + month + '-' +
      (day<10 ? '0' : '') + d.getFullYear();
    $('.check').datepicker('setStartDate', output);
  });

  $(document).on('change','#status',function(){
    if($(this).find('option:selected').text() == 'FOLLOW UP'){
        $('#flw').show();
        $('#del').hide();
    }
    else{
      $('#flw').hide();
        $('#del').hide();
    }
   if($(this).find('option:selected').text() == 'SALE CLOSE'){
        $('#del').show();
        $('#flw').hide();
        $('#purchase').show();
        document.getElementById("purchase_date").required = true;
        document.getElementById("purchase_value").required = true;
        document.getElementById("bill_no").required = true;
    }
    else{
      $('#flw').hide();
        $('#del').hide();
        $('#purchase').hide();
        document.getElementById("purchase_date").required = false;
        document.getElementById("purchase_value").required = false;
        document.getElementById("bill_no").required = false;
    }
  });

  $(document).on('input','#search',function(){
      var itemList = $("#searchlist");
      itemList.empty();
      var name = $(this).val();
      if(name.length >= 3){     
          $.get("{{url('customer/list')}}", {term:name}, function(resNames) {
              itemList.empty();
              if(resNames.length) {
                  $.each(resNames, function(index,element) {
                    var info = element.customer_name+'/'+element.customer_phone;
                      if(element.alternate_phone != null) {
                        info = info +'/'+element.alternate_phone;
                      }
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
        $.get("{{url('check/phone')}}", {num}, function(data) {
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
        $.get("{{url('customer/show')}}", {value}, function(data) {
          if(data.length != 0){
                $("#name").val(data.customer_name);
                $("#phone").val(data.customer_phone);
                $("#alt_phone").val(data.alternate_phone);
                $("#place").val(data.place);
            }
            else{
                $("#name").val('');
                $("#phone").val('');
                $("#alt_phone").val(data.alternate_phone);
                $("#place").val(data.place);
            }
        });
    }
  });

  $(document).on('change','#inf_type',function(){
    $('#inf_name').empty();
    $('#inf_name').append('<option value="">--Select--</option');
    var inf = $(this).val();
    $.get("{{url('associate/show')}}", {inf}, function(data) {
      $.each(data,function(index,element){
          $('#inf_name').append('<option value="'+element.id+'">'+element.associate_name+'</option');
        }); 
        });
  });

  $(document).on('click','#add',function(e){
  e.preventDefault();
    e.stopPropagation();
    var place = $('#nplace').val();
    if(place =="") {
      alert("Enter Place")
    }
    else {   
      $.get("{{url('add/place')}}", {place}, function(data) {
        $('#nplace').val(""); 
        if(data == 0) {
          alert("Place already exists")
        }
        else if(data > 0) {
          alert("Place added");
          $('#modal-place').modal('hide');
          $('#place').append('<option value="'+data+'" selected>'+place.toUpperCase()+'</option>');
        }
      });
    }
  });

  $(document).on('click','#add_function',function(e){
    e.preventDefault();
    e.stopPropagation();
    var nfunction = $('#nfunction').val(); 
    if(nfunction =="") {
      alert("Enter Function Type")
    }
    else {    
      $.get("{{url('add/function')}}", {nfunction}, function(data) {
        $('#nfunction').val(""); 
        if(data == 0) {
          alert("Function type already exists")
        }
        else if(data > 0) {
          alert("Function type added");
          $('#modal-function').modal('hide');
          $('#func_type').append('<option value="'+data+'" selected>'+nfunction.toUpperCase()+'</option>');
        }
      });
    }
  });
   
 
  $("input[name=enqtype]:radio").change(function () {
    var ref = $("input[name='enqtype']:checked").val();
    // alert(ref);
    if(ref == 9) {
      $('#reference').show();
      document.getElementById("inf_type").required = false;
      document.getElementById("inf_name").required = false;
      document.getElementById("ref_name").required = true;
      document.getElementById("ref_phone").required = true;
    }
    else {
      $('#reference').hide();
      document.getElementById("inf_type").required = true;
      document.getElementById("inf_name").required = true;
      document.getElementById("ref_name").required = false;
      document.getElementById("ref_phone").required =false;
    }
    if(ref == 6) {
      $('#associate').hide();
      $('#employee').hide();
      document.getElementById("inf_type").required = false;
      document.getElementById("inf_name").required = false;
      document.getElementById("staff_name").required = false;
    }
    else {
      $('#associate').show();
      $('#employee').show();

      document.getElementById("inf_type").required = true;
      document.getElementById("inf_name").required = true;
      document.getElementById("staff_name").required = true;
    }
    if(ref == 6 || ref == 7 || ref == 9) {
      $('#associate').hide();
      document.getElementById("inf_type").required = false;
      document.getElementById("inf_name").required = false;
    }
    else {
      $('#associate').show();
      document.getElementById("inf_type").required = true;
      document.getElementById("inf_name").required = true;
    }

  });


  $(document).on('change','#status',function(){
      var status = $(this).val();
      if(status == 4 || status == 5) {
        document.getElementById("remarks").required = true;

      }
      else {
        document.getElementById("remarks").required = false;

      }
     

  });

</script>
</html>
