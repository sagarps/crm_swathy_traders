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
          Followups
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i>Followups </a></li>
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
          <div class="box-body">
            <div class="form-group">
            <div>
                  
                    <form class="form-horizontal form" action="{{route('search.followups')}}" method="POST">
                    <div class="col-sm-3">
                      <label class="control-label">From<font color="red">*</font></label>
                      <input type="text" class="form-control change datepicker" name="from" id="from" @if($from != null) value="{{date('d-m-Y',strtotime($from))}}" @else value="{{date('d-m-Y')}}" @endif required>
                    </div>
                    <div class="col-sm-3">
                      <label class="control-label">To<font color="red">*</font></label>
                      <input type="text" class="form-control change datepicker" name="to" id="to" @if($to != null) value="{{date('d-m-Y',strtotime($to))}}" @else value="{{date('d-m-Y')}}" @endif required>
                    </div>
                    <div class="col-sm-2">
                      <br>
                      <button type="submit" class="btn btn-info">Submit</button>
                    </div>
                    </form>
                  </div>
            </div>
          </div>
         </div>
         <div class="box">
            <div class="box-header">
              <h3 class="box-title">Followup List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead >
                <tr>
                    <th>Sl No.</th>
                    <th>Enquiry No</th>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Followup Date</th>
                    <th>Edit</th>
                    <th>Log</th>
                </tr>
                </thead>
                <tbody>
                 <?php $c = 1; ?>
                 @if($followups != NULL)
                 @foreach($followups as $follow)
                  <tr>
                      <td>{{$c++}}</td>
                      <td>{{$follow->enq_no}}</td>
                      <td>{{$follow->customer->customer_name}}</td>
                      <td>{{$follow->customer->customer_phone}}</td>
                      <td>{{date('d-m-Y',strtotime($follow->status_date))}}</td>
                      <td class="edit" data-toggle="modal" data-target="#modal-edit" id="{{$follow->id}}"><i class="fa fa-fw fa-pencil" style="color: blue;"></i></td>
                      <td class="log" data-toggle="modal" data-target="#modal-log" id="{{$follow->id}}"><i class="fa fa-fw fa-list" style="color: blue;"></i></td>
                  </tr>
                  @endforeach
                  @endif
                </tbody>
              </table>
            </div>
           
          </section>
          <!-- /.Left col -->
            <!-- follow up edit model -->

      <div class="modal modal-default fade" id="modal-edit">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Enquiry Edit</h4>
            </div>

            <form class="form-horizontal form" action="{{route('followup.update')}}" method="POST">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="modal-body">
              <div class="form-group ">
                <div class="col-md-12 mob">
                  <div class="col-md-4 col-sm-10">
                    <div class="form-group">
                      <label class="control-label">Customer Search<i style="color: red">*</i></label>
                      <div class="customer_search">
                      <input class="form-control" type="text" name="search" id="search" class="form-control customer_number" list="searchlist" placeholder="Name / Mobile" autofocus="true" autocomplete="off">
                      <datalist id="searchlist">
                      <option></option>
                      </datalist>                 
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2 col-sm-2">
                  <div class="form-group">
                  <a id="copy"onclick="myFunction()" class="btn btn-default fa fa-copy copy_search" style="margin-top: 30px;margin-left: 10px;"></a>
                  </div>
                  </div>
             
                <div class="col-md-6">
                  <div class="form-group">
                    <br><br>
                    @foreach($type as $typ)
                      <label>
                        <input type="radio" name="enqtype" id="enqtype" value="{{$typ->id}}" @if($typ->name == 'WALK IN') checked @endif>
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
                    <input type="text" name="name" id="cname" class="form-control" autocomplete="off" value="" required>                     
                    </div>
                  </div>
                </div>
                <div class="col-md-1">
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <label class="control-label">Phone<i style="color: red">*</i></label>
                    <div>
                    <input type="number" name="phone" id="cphone" class="form-control" autocomplete="off" value="" required>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                          <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Alternative Phone</label>
                                <div>
                                  <input type="number" name="alt_phone" id="alt_phone" class="form-control" autocomplete="off" value="">
                                </div>
                              </div>
                            </div>
                            <div class="col-md-1">
                            </div>
                           
                            <div class="col-md-5">
                          <div class="form-group">
                            <label class="control-label">Coupon No</label>
                            <div>
                            <input type="text" name="coupon_no" id="coupon_no" class="form-control" value="">
                            </div>
                          </div>
                        </div>
                            
                          </div>
              <div class="col-md-12">
                <div class="col-md-5">
                  <div class="form-group">
                    <label class="control-label">Lead Stage<i style="color: red">*</i></label>
                    <div>
                    <select class="form-control" name="lead" id="clead" required>
                      <option value="">--Select--</option>
                      @foreach($lead_type as $ltype)
                      <option value="{{$ltype->id}}">{{$ltype->name}}</option>
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
                    <select class="form-control" name="status" id="cstatus" required>
                    <option value="">--Select--</option>
                    @foreach($status as $stat)
                    <option value="{{$stat->id}}">{{$stat->status_name}}</option>
                    @endforeach
                    </select>       
                    </div>
                  </div>
                </div>
              </div>
                <div class="col-md-12">
                <div class="col-md-5">
                  <div class="form-group">
                    <label class="control-label">Place<i style="color: red">*</i></label>
                    <div>
                    <select class="form-control" name="place" id="place" required>
                                    <option value="">--Select--</option>
                                    @foreach($places as $plc)
                                  
                                      <option value="{{$plc->id}}">{{$plc->place}}</option>
                                                    @endforeach
                                  </select>                     </div>
                  </div>
                </div>
                <div class="col-md-1">
                <div>
                <br>
                <!-- <i class="fa fa-plus-circle" aria-hidden="true" style="color:#3c8dbc;font-size:30px;position:relative;margin-top: 9px;" data-toggle="modal" data-target="#modal-place"></i> -->
                </div>
              </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <label class="control-label">Delivery Date<i style="color: red">*</i></label>
                    <div>
                      <input type="text" name="func_date" id="func_date" class="form-control datepicker check" autocomplete="off" required>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-12">
                <div class="col-md-5">
                  <div class="form-group">
                    <!-- <label class="control-label">Gender<i style="color: red">*</i></label> -->
                    <div>
                    <br>
                    <label>
                        <!-- <input type="radio" name="gen" value="Male"> Male -->
                        <!-- <input type="radio" name="gen" value="Female"> Female -->
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
                    <select class="form-control" name="func_type" id="func_type" required>
                        <option value="">--Select--</option>
                        @foreach($func_type as $type)
                          <option value="{{$type->id}}">{{$type->function_type}}</option>
                        @endforeach
                      </select>   
                      </div>
                              </div>
                            </div>
                            <div class="col-md-1">
                              <div>
                              <br>
                              <!-- <i class="fa fa-plus-circle" aria-hidden="true" style="color:#3c8dbc;font-size:30px;position:relative;margin-top: 9px;" data-toggle="modal" data-target="#modal-function"></i> -->
                              </div>
                            </div>
                          </div>
              <div class="col-md-12" id="associate" style="display:none">
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Influencer Type<i style="color: red">*</i></label>
                                <div>
                                <select class="form-control" name="inf_type" id="inf_type" required>
                                    <option value="">--Select--</option>
                                    @foreach($inf_type as $type)
                                   
                                      <option value="{{$type->id}}">{{$type->influencer_type}}</option>
                                                                @endforeach
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

                                <select class="form-control" name="inf_name" id="inf_name" required>
                                    <option value="">--Select--</option>
                                    @foreach($associates as $ass)
                                    <option value="{{$ass->id}}">{{$ass->associate_name}}</option>
                                    @endforeach   

                                      </select>  
   
                                </div>
                              </div>
                            </div>
                          </div>


                          <div class="col-md-12" style="display:none" id="reference">
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Reference Name</label>
                                <div>
                                <input type="text" name="ref_name" id="ref_name" class="form-control" value="">                                 </div>
                              </div>
                            </div>
                            <div class="col-md-1">
                            </div>
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Reference Phone</label>
                                <div>
                                <input type="number" name="ref_phone" id="ref_phone" class="form-control" value="">                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-12" style="display:none" id="purchase">
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Purchase Date</label>
                                <div>
                                <input type="text" name="purchase_date" id="purchase_date" class="form-control datepicker" value="{{date('d-m-Y')}}" required>
                                 </div>
                              </div>
                            </div>
                            <div class="col-md-1">
                            </div>
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Purchase Value</label>
                                <div>
                                <input type="number" step="any" name="purchase_value" id="purchase_value" class="form-control" value="">
                                </div>
                              </div>
                            </div>
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Bill No</label>
                                <div>
                                <input type="text" name="bill_no" id="bill_no" class="form-control" value="">
                                </div>
                              </div>
                            </div>
                          </div>
                          @if(Auth::user()->userpermission(12))

                          <div class="col-md-12">
                            <div class="col-md-5" style="display:none" id="employee">
                              <div class="form-group">
                                <label class="control-label">Staff Name<i style="color: red">*</i></label>
                                <div>
                                <select class="form-control" name="staff_name" id="staff_name" required>
                                    <option value="">--Select--</option>
                                 @foreach($employee as $emp)
                                 <option value="{{$emp->id}}">{{$emp->name}}</option>
                                 @endforeach
                                  </select> 

                                 </div>
                              </div>
                            </div>
                      </div>
                     @endif
                      <div class="col-md-12">

                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Address</label>
                                <div>
                                <textarea cols="67" rows="2" name="address" id="address" class="form-control" required></textarea>                                </div>
                              </div>
                            </div>
                            <div class="col-md-1">
                            </div>
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Remarks</label><i style="color: red">*</i>
                                <div>
                                <textarea cols="67" rows="2" name="remarks" id="remarks" class="form-control"></textarea>  
                                                               </div>
                              </div>
                            </div>
                            <div class="col-md-1">
                            </div>
                           
                          </div>
                          <div class="col-md-12">

                <div class="col-md-5">
                
                  <div class="form-group" id="flw">
                    <label class="control-label">Followup Date<i style="color: red">*</i></label>
                    <div>
                    <input type="text" name="flwdate" id="flwdate" class="form-control datepicker check" required>
                    </div>
                  </div>
                  <div class="form-group" id="del" style="display: none;">
                                <label class="control-label">Delivery Date<i style="color: red">*</i></label>
                                <div>
                                  <input type="text" name="deliverydate" id="deliverydate" class="form-control datepicker check" value="{{date('d-m-Y')}}" required>
                                </div>
                              </div>
                </div>
              </div>
                </div>
                <div class="col-md-12">
                
                <div class="col-md-1">
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                  <!-- <label class="control-label">Followup Date<i style="color: red">*</i></label> -->
                  <div>
                  <input type="hidden" name="dlvdate" id="dlvdate" class="form-control datepicker check" required>
                  </div>
                    <div>
                    
                    </div>
                  </div>
                </div>
              </div>




              </div>
              
            <input type="hidden" name="from" value="{{$from}}">
            <input type="hidden" name="to" value="{{$to}}">
            <input type="hidden" name="_token" value="{{{ csrf_token() }}}"> 
            <input type="hidden" name="edit_id" id="edit_id" value="">
        
            <div class="modal-footer">
              <button type="button" class="btn btn-info pull-right" data-dismiss="modal" style="margin-left: 16px;">Close</button>
              <button type="submit" class="btn btn-info">Save</button>

            </div>
          </form>
          </div>
        </div>
      </div>
<!-- Follow up edit ends -->
    
          
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
 $(function () {
  $('#example1').DataTable( {
  "searching": true
} );
    $('#place').select2();
  });
  

 $(document).on('change','.change', function() {
    if($('#from').val() != '' && $('#to').val() != ''){
       var text    = "FOLLOWUP";
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

 $(document).on('click','.edit', function() {
    var id = $(this).attr('id');
    $('#tbody').empty();
    $.get("{{url('get/followups/enquiry')}}", {id}, function(data) { 
      // alert(data.function_date);
        $('#cname').val(data.customer_name);
        $('#cphone').val(data.customer_phone); 
        $('#alt_phone').val(data.alternate_phone); 
        $("#cstatus").val(data.enquiry_status_id);
        $("#clead").val(data.lead_type_id);
        $("input[name=enqtype][value=" + data.enq_type + "]").attr('checked', 'checked');
        $("#edit_id").val(data.id);
        // $("#remarks").val(data.remarks);
        // $("#place").val(data.place_id);
        // $("#func_date").val(data.function_date);
        $("input[name=gen][value=" + data.gender + "]").prop('checked', true);
        $('#func_type').val(data.function_type);
        $('#inf_type').val(data.influencer_type);
        // $('#inf_name').val(data.associate_id);

        $('#address').val(data.address);
        $('#staff_name').val(data.staff_id);
        $('#ref_name').val(data.reference_name);
        $('#ref_phone').val(data.reference_phone);

        $('#purchase_date').val(data.purchase_date);
        $('#purchaase_value').val(data.purchaase_value);
        $('#bill_no').val(data.bill_no);
        $('#coupon_no').val(data.coupon_no);

        
        if(data.enq_type == 9) {
          $('#reference').show();
          document.getElementById("ref_name").required = true;
      document.getElementById("ref_phone").required = true;
        }
        else {
          $('#reference').hide();
          document.getElementById("ref_name").required = false;
      document.getElementById("ref_phone").required = false;
        }
        if(data.enq_type == 6) {
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
      // document.getElementById("staff_name").required = true;
    }
        if(data.enquiry_status_id == 3) {
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

    if(data.enq_type == 6 ||data.enq_type == 7 || data.enq_type == 9 ) {
      $('#associate').hide();
      document.getElementById("inf_type").required = false;
      document.getElementById("inf_name").required = false;
    }
    else {
      $('#associate').show();
      document.getElementById("inf_type").required = true;
      document.getElementById("inf_name").required = true;
    }

        // $('#inf_name').val(data.influencer_name);
      var ass = data.associate_id;
        var inf = $('#inf_type').val();
        // alert(inf);
        $.get("{{url('associate/show')}}", {inf}, function(data) {
        $.each(data,function(index,element){
          if(element.id == ass) {
            $('#inf_name').append('<option value="'+element.id+'" selected>'+element.associate_name+'</option');
          }
          else {
            $('#inf_name').append('<option value="'+element.id+'">'+element.associate_name+'</option');
          }
        }); 
        }); 

        // alert("hiiiii");
        $("#place").val(data.place_id);
        $('#place').select2();
        $("#inf_name").val(data.associate_id);
        var func_date = data.function_date;
        var new_func = func_date.split("-").reverse().join("-");
        // alert(func_date);
            $("#func_date").val(new_func);
        // if(data.flawdate != null) {
        //   alert(data.flawdate);
        //     // $("#flwdate").val(data.flawdate);
        //     var day = data.flawdate;
        //     var newdate = day.split("-").reverse().join("-");
        //     $("#flwdate").val(newdate);
        // }
        // else {
          var day = new Date();
          var twoDigitMonth = ((day.getMonth().length+1) === 1)? (day.getMonth()+1) : '0' + (day.getMonth()+1);
          var currentDate = day.getFullYear() + "-" + twoDigitMonth + "-" + (day.getDate()+1);
          var fldate = currentDate.split("-").reverse().join("-");

          $("#flwdate").val(fldate);
        // }
        if(data.dlvdate != null) {
            $("#dlvdate").val(data.dlvdate);
        }
        else {
          var day = new Date();
          var twoDigitMonth = ((day.getMonth().length+1) === 1)? (day.getMonth()+1) : '0' + (day.getMonth()+1);
          var currentDate = day.getFullYear() + "-" + twoDigitMonth + "-" + day.getDate() ;
          $("#dlvdate").val(currentDate);
        }
        if(data.purchase_date != null) {
          var day = data.purchase_date;
            var newdate = day.split("-").reverse().join("-");
            $("#purchase_date").val(newdate);        }
        else {
          var day = new Date();
          var twoDigitMonth = ((day.getMonth().length+1) === 1)? (day.getMonth()+1) : '0' + (day.getMonth()+1);
          var currentDate = day.getFullYear() + "-" + twoDigitMonth + "-" + day.getDate() ;
          $("#purchase_date").val(currentDate);
        }
      

        
    });
     
 });

$(document).ready(function(){

  if($('input[name="enqtype"]:checked').val() == 9) {
      $('#reference').show();
      document.getElementById("ref_name").required = true;
      document.getElementById("ref_phone").required = true;
    }
    else {
      $('#reference').hide();
      document.getElementById("ref_name").required = false;
      document.getElementById("ref_phone").required = false;
    }
    if($('input[name="enqtype"]:checked').val() == 6 ||$('input[name="enqtype"]:checked').val() == 7 || $('input[name="enqtype"]:checked').val() == 9 ) {
      $('#associate').hide();
      document.getElementById("inf_type").required = false;
      document.getElementById("inf_name").required = false;
    }
    else {

      $('#associate').show();
      document.getElementById("inf_type").required = true;
      document.getElementById("inf_name").required = true;
    }
    if($('input[name="enqtype"]:checked').val() == 6) {
      $('#associate').hide();
      $('#employee').hide();
      document.getElementById("inf_type").required = false;
      document.getElementById("inf_name").required = false;
      // document.getElementById("staff_name").required = false;
    }
    else {
      $('#associate').show();
      $('#employee').show();

      document.getElementById("inf_type").required = true;
      document.getElementById("inf_name").required = true;
      // document.getElementById("staff_name").required = true;
    }
    var status =$('#status').val() ;

    if(status == 4 || status == 5) {
        document.getElementById("remarks").required = true;

      }
      else {
        document.getElementById("remarks").required = false;

      }
      
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
    
    var d     = new Date();
    var month = d.getMonth()+1;
    var day   = d.getDate();

    var output = day + '-' +
      (month<10 ? '0' : '') + month + '-' +
      (day<10 ? '0' : '') + d.getFullYear();
    $('.check').datepicker('setStartDate', output);
  });

  $(document).on('change','#cstatus',function(){
    if($(this).find('option:selected').text() == 'FOLLOW UP'){
        $('#flw').show();
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

  $(document).on('input','#cphone',function(){
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

  $(document).on('change','#search',function(){
    if($(this).val().length >= 3){
        var value = $(this).val();
        $.get("{{url('customer/show')}}", {value}, function(data) {
            if(data.length != 0){
                $("#cname").val(data.customer_name);
                $("#cphone").val(data.customer_phone);
                $("#place").val(data.place_id);
                $('#place').select2();
                $("#alt_phone").val(data.alternate_phone);
            }
            else{
                $("#cname").val('');
                $("#cphone").val('');
                $("#alt_phone").val(data.alternate_phone);
                $("#place").val(data.place);
                $('#place').select2();
            }
        });
    }
  });

  $(document).on('change','#inf_type',function(){
    // alert();
    $('#inf_name').empty();
    $('#inf_name').append('<option value="">--Select--</option');
    var inf = $(this).val();
    $.get("{{url('associate/show')}}", {inf}, function(data) {
      // alert(data);
      $.each(data,function(index,element){
          $('#inf_name').append('<option value="'+element.id+'">'+element.associate_name+'</option');
        }); 
        });
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
      // document.getElementById("staff_name").required = true;
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

  $(document).on('change','#cstatus',function(){
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
