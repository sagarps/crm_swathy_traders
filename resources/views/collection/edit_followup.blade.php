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
          Collection Follow Up
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{route('collection.followups.index')}}"><i class="fa fa-dashboard"></i>Collection Follow Up </a></li>
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
      @if(Session::has('success')) 
          <div class="box-header with-border" id="saveTarget">
              <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{Session::get('success')}}...!!
              </div>
          </div>
      @endif
      

      <div class="modal modal-default fade" id="modal-log">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Followup Log</h4>
            </div>

            <form class="form-horizontal form" action="" method="POST">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="modal-body">
              <div class="form-group">
                <div class="col-md-12">
                  <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Slno</th>
                      <th>Bill No</th>
                      <th>Bill Date</th>
                      <th>Bill Amount</th>
                      <th>Outstanding</th>
                      <th>Due Date</th>
                      <th>Remarks</th>
                    </tr>
                  </thead>
                  <tbody>
                    
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
                    <!-- this is for product  -->
                    <!-- <li><a href="#tab_3-3" data-toggle="tab">Quotation</a></li> -->
                    <li><a href="#tab_1-1" data-toggle="tab">Followup Tab</a></li>
                    <li class="active" ><a href="#tab_2-2" data-toggle="tab">Collection Follow Up</a></li>
                    <li class="pull-left header"><i class="fa fa-th"></i></li>
                  </ul>
                  <div class="tab-content">

                    <div class="tab-pane"  id="tab_1-1">
                      <div class="box-header">
                        <h3 class="box-title">Form</h3>
                      </div>

                      <div class="box">
                        <div class="box-body">
                        <form action="{{route('collection.followup.update')}}" method="post">
                        <input type="hidden" name="customer_id" value="{{$customer->id}}">
                        <input type="hidden" name="from" value="{{$from}}">
                        <input type="hidden" name="to" value="{{$to}}">
                        <input type="hidden" name="folotype" value="{{$folotype}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-12">
                          <div class="col-md-5">
                            <div class="form-group">
                              <label class="control-label">Remarks<i style="color: red;">*</i></label>
                              <div>
                                <input type="text" name="followup_remark" id="followup_remark" value="" required class="form-control">
                              </div>
                            </div>
                          </div>
                          <div class="col-md-1">
                          </div>
                          <div class="col-md-5">
                            <div class="form-group">
                              <label class="control-label">Comminted Date<i style="color: red;">*</i></label>
                              <div>
                                <input type="text" name="commited_date" id="commited_date" value="{{$next_commit_day->format('d-m-Y')}}" required class="form-control datepicker">
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="col-md-5">
                            <div class="form-group">
                              <label class="control-label">Followup Type<i style="color: red;">*</i></label>
                              <div>
                                <select class="form-control" name="followup_type" id="followup_type" required>
                                  <option value="COMMITED_DATE" @if($customer->followup_type == "COMMITED_DATE") selected @endif>COMMITED DATE</option>
                                  <option value="MONDAY" @if($customer->followup_type == "MONDAY") selected @endif>MONDAY</option>
                                  <option value="TUESDAY" @if($customer->followup_type == "TUESDAY") selected @endif>TUESDAY</option>
                                  <option value="WEDNESDAY" @if($customer->followup_type == "WEDNESDAY") selected @endif>WEDNESDAY</option>
                                  <option value="THURSDAY" @if($customer->followup_type == "THURSDAY") selected @endif>THURSDAY</option>
                                  <option value="FRIDAY" @if($customer->followup_type == "FRIDAY") selected @endif>FRIDAY</option>
                                  <option value="SATURDAY" @if($customer->followup_type == "SATURDAY") selected @endif>SATURDAY</option>
                                  <option value="SUNDAY" @if($customer->followup_type == "SUNDAY") selected @endif>SUNDAY</option>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-1">
                          </div>
                          <div class="col-md-5">
                            <div class="form-group">
                              <label class="control-label">Critical</label>
                              <div>
                                <input type="checkbox" name="is_critical" id="is_critical" value="1" @if($customer->is_critical == 1) checked @endif>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="box-footer">
                            <div class="pull-right">
                              <button type="submit" class="btn btn-info">Save</button>
                            </div>
                        </div>
                        </form>          
                        </div>
                        
                        <div class="box-body">
                        <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                          <th>Slno</th>
                          <th>Bill No</th>
                          <th>Bill Date</th>
                          <th>Bill Amount</th>
                          <th>Outstanding</th>
                          <th>Due Date</th>
                          <th>Due Days</th>
                          <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="probody">
                          @foreach($tally_bills as $tally_bill)
                          <tr>
                            <td>{{$loop->iteration}}</td>  
                            <td>{{$tally_bill->bill_no}}</td>
                            <td>{{date('d-m-Y',strtotime($tally_bill->bill_date))}}</td>
                            <td>{{$tally_bill->bill_amount}}</td>
                            <td>{{$tally_bill->receivable}}</td>
                            <td>{{date('d-m-Y',strtotime($tally_bill->bill_due_date))}}</td>
                            <td>{{ now()->diffInDays($tally_bill->bill_due_date)}}</td>
                            <td><button class="btn btn-danger btn-sm delete" id="{{$tally_bill->bill_id}}">Delete</button></td>
                          </tr>
                          @endforeach
                        </tbody>
                        </table>
                        <div class="box-body">

                          <div class="col-md-12">
                            <div class="col-md-5">
                              <input type="hidden" name="cust_id" id="cust_id" value="{{$customer->id}}">  
                              <input type="hidden" name="enq_item_id" id="enq_item_id" value="">
                              <div class="form-group">
                                <label class="control-label">Bill No<i style="color: red;">*</i></label>
                                <div>
                                  <input type="text" name="bill_no" id="bill_no" value="" required class="form-control">
                                </div>
                              </div>
                            </div>
                            <div class="col-md-1">
                            </div>
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Bill Date<i style="color: red;">*</i></label>
                                
                                <div>
                                  <input type="text" name="bill_date" id="bill_date" value="" required class="form-control datepicker">
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-12">
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Bill Amount<i style="color: red;">*</i></label>
                                <div>
                                  <input type="text" name="bill_amount" id="bill_amount" value="" required class="form-control">
                                </div>
                              </div>
                            </div>
                            <div class="col-md-1">
                              <label></label>
                              <div>
                                
                              </div>
                            </div>
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Outstanding<i style="color: red;">*</i></label>
                                <div>
                                  <input type="number" min="0" step="0.01" name="outstanding" id="outstanding" required min="1" step="0.01" class="form-control">
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
                            <input class="pull-right btn btn-primary" type="button" name="prosave" id="prosave" value="Save Bill">
                            </div>
                          </div>

                        </div>
                        
                      </div>
                      
                      </div>
                    </div>
                    <!-- /.tab-pane -->

                    <!-- Add functon type modal -->
                    <div class="modal modal-default fade" id="modal-customer_type">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Add Customer Type</h4>
                          </div>

                          <form class="form-horizontal form" action="" method="">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="col-md-12">
                                <div class="col-md-5">
                                  <div class="form-group">
                                    <label class="control-label">Customer Type<i style="color: red">*</i></label>
                                    <div>
                                    <input type="text" name="ncustomertype" id="ncustomertype" class="form-control" autocomplete="off" required>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-md-1">
                                </div>

                            </div>
                          <div class="modal-footer">
                          <button type="submit" class="btn btn-info" id="add_customer_type">Add</button>

                            <button type="button" class="btn btn-info pull-right" data-dismiss="modal">Close</button>
                          </div>
                        </form>
                        </div>
                      </div>
                    </div>

                  <!-- add function type modal ends -->                    

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

                  
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="tab_2-2">
                      <div class="box-header">
                        <h3 class="box-title">Customer Form</h3><br>
                        <h5> </h5>

                      </div>
                        <!-- form start -->
                        @if (count($errors) > 0)
                          <div class = "alert alert-danger">
                                <ul>
                                  @foreach ($errors->all() as $error)
                                      <li>{{ $error }}</li>
                                  @endforeach
                                </ul>
                            </div>
                          @endif
                      <form class="form-horizontal" action="" method="GET" autocomplete="off">
                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}"> 
                        <input type="hidden" name="edit_id" value="">
                        <input type="hidden" name="from" id="from" value="{{$from}}">
                        <input type="hidden" name="to" id="to" value="{{$to}}">
                        <div class="box-body">

                          <!-- <div class="col-md-12">
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
                                
                              </div>
                            </div>
                          </div> -->

                          <div class="col-md-12">
                            <div class="col-md-2">
                              <div class="form-group">
                                <label class="control-label">Customer Name<i style="color: red">*</i></label>
                                <div>
                                  <label class="control-label">{{$customer->customer_name}}   </label>
                                  
                                </div>
                              </div>
                            </div>
                            <div class="col-md-2">
                              <div class="form-group">
                                <label class="control-label">Phone<i style="color: red">*</i></label>
                                <div>
                                  <label class="control-label">{{$customer->customer_phone}}   </label>
                                  
                                </div>
                              </div>
                            </div>
                            <div class="col-md-2">
                              <div class="form-group">
                                <label class="control-label">Alternative Phone</label>
                                <div>
                                  <label class="control-label">{{$customer->alternate_phone}}   </label>
                                  
                                </div>
                              </div>
                            </div>
                            <div class="col-md-2">
                              <div class="form-group">
                                <label class="control-label">Address</label>
                                <div>
                                <label class="control-label">{{$customer->address}}   </label>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-2">
                              <div class="form-group">
                                <label class="control-label">Place<i style="color: red">*</i></label>
                                <div>
                                  <label class="control-label">{{$customer->place->place}}   </label>
                                </div>
                              </div>
                            </div>
                          </div>
                       <div class="col-md-12">
                         <div class="col-md-2">
                           <div class="form-group">
                            <label class="control-label">Outstanding<i style="color: red">*</i></label>
                             <div>
                              <label class="control-label">{{$customer->tallyBillOutstandings->sum('receivable')}}</label>
                             </div>
                          </div>
                        </div>
                        <div class="col-md-2">
                          <div class="form-group">
                            <label class="control-label">Followup Type<i style="color: red">*</i></label>
                            <div>
                              <label class="control-label">{{$customer->followup_type}}</label>
                            </div>
                          </div> 
                        </div>
                       </div>
                          
                        
                      <div class="col-md-12">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label class="control-label">Followup Logs</label>
                            <div style="height: 200px; overflow-y: scroll;">
                              <table class="table table-bordered table-striped">
                                <thead>
                                  <tr>
                                    <th>Slno</th>
                                    <th>Followup Date</th>
                                    <th>Followup Time</th>
                                    <th>Employee</th>
                                    <th>Next Commit Date</th>
                                    <th>Remarks</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  @foreach($followup_logs as $log)
                                  <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{date('d-m-Y',strtotime($log->followup_date))}}</td>
                                    <td>{{date('H:i:s',strtotime($log->followup_time))}}</td>
                                    <td>{{$log->employee->name}}</td>
                                    <td>{{date('d-m-Y',strtotime($log->next_commit_date))}}</td>
                                    <td>{{$log->remarks}}</td>
                                  </tr>
                                  @endforeach
                                </tbody>
                              </table>
                            </div>
                      </div>
                      
                          
                      
                            
                        </div>
                      </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                          <div class="col-md-2">
                            <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-log">Log</button> -->
                          </div>
                          <div class="pull-right">
                            
                            <!-- <button type="submit" class="btn btn-info">Save</button> -->
                            
                          </div>
                        </div>
                        <!-- /.box-footer -->

                      </form>
                    </div>

                    <!-- /.tab-pane -->
                    

                   
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
  // alert($('input[name="enqtype"]:checked').val());
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
        $('#employee').hide();
        document.getElementById("staff_name").required = false;
      }
      else {
        $('#employee').show();

      }
   
    
    var status =$('#status').val() ;
    if(!(status == 3)) {
      $('#remarks').val("");
    }
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
  });

  $(document).on('change','#status',function(){
    if($(this).find('option:selected').text() == 'FOLLOW UP'){
        $('#flw').show();
        $('#del').hide();
    }
    else if($(this).find('option:selected').text() == 'SALE CLOSE'){
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
                $("#place").val(data.place_id);
                $("#customer_type").val(data.customer_type_id);
                $("#address").val(data.address);
            }
            else{
                // $("#name").val('');
                // $("#phone").val('');
                // $("#alt_phone").val(data.alternate_phone);
                // $("#place").val(data.place);
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
      $.get("{{url('categort/products')}}", {cat}, function(data) {
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
      $.get("{{url('product/units')}}", {pro}, function(data) {
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
      $.get("{{url('unit/price')}}", {unit,pro}, function(data) {
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
    var bill_no  = $('#bill_no').val();
    var bill_date = $('#bill_date').val();
    var bill_amount = $('#bill_amount').val();
    var outstanding  = $('#outstanding').val();
    var remarks = $('#proremarks').val();
    var cust_id = $('#cust_id').val();

    if(bill_no == ''){
      alert("Enter Bill No.");
      return 0;
    }
    if(bill_date == ''){
      alert("Enter Bill Date.");
      return 0;
    }
    if(bill_amount == ''){
      alert("Enter Bill Amount.");
      return 0;
    }
    if(outstanding == ''){
      alert("Enter Outstanding Amount.");
      return 0;
    }

    $.post("{{url('collection/bill/save')}}", {bill_no,bill_date,bill_amount,outstanding,cust_id}, function(data) {
      if(data != ''){
        var len = parseInt($('#probody tr').length)+1;
        if(data.remarks == null){
          data.remarks = '';
        }
        if(data){
          $('#probody').append('<tr id="row'+data.id+'"><td>'+len+'</td><td>'+bill_no+'<input type="hidden" class="product_id" name="product_id" value="'+data.id+'"></td><td>'+bill_date+'</td><td>'+bill_amount+'</td><td>'+outstanding+'<input type="hidden" class="cust_id" name="cust_id" value="'+cust_id+'"></td><td>'+data.due_date+'</td><td>'+data.due_days+'</td><td><button type="button" class="fa fa-trash btn btn-danger delete" id="'+data.id+'"></button></td></tr>');
        }
        else{
          $('#row'+enq_item_id).remove();
          $('#probody').append('<tr id="row'+data.id+'"><td>'+len+'</td><td>'+bill_no+'<input type="hidden" class="product_id" name="product_id" value="'+data.id+'"></td><td>'+bill_date+'</td><td>'+bill_amount+'</td><td>'+outstanding+'<input type="hidden" class="cust_id" name="cust_id" value="'+cust_id+'"></td><td>'+data.due_date+'</td><td>'+data.due_days+'</td><td><button type="button" class="fa fa-trash btn btn-danger delete" id="'+data.id+'"></button></td></tr>');
          var i = 1;
          $('#probody td:first-child').each(function() {
            $(this).text(i); 
            i++;
          });
        }
      }
      $('.calculate').trigger('blur');
    });
    $('#bill_no').val('');
    $('#bill_date').val('');
    $('#bill_amount').val('');
    $('#outstanding').val('');
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
    $.get("{{url('product/edit')}}", {id}, function(data) {
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
      $.post("{{url('collection/bill/delete')}}", {id}, function(data) {
        alert("Bill deleted.");
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
    $.get("{{url('quotation/check')}}", {qno}, function(data) {
      if(data > 0){
        alert("Quotation number already used");
        $('#quot_no').val('');
      }
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
          $('#place').append('<option value="'+data+'" selected>'+place+'</option>');
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
          $('#func_type').append('<option value="'+data+'" selected>'+nfunction+'</option>');
        }
      });
    }
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

    $.get("{{url('quotation/store')}}", {quot_no,quot_date,quot_price,quot_enq_id}, function(data) {
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
    }
    else {
      $('#associate').show();
      $('#employee').show();

      document.getElementById("inf_type").required = true;
      document.getElementById("inf_name").required = true;
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

  $(document).on('click','#add_customer_type',function(e){
    e.preventDefault();
    e.stopPropagation();
    var ctype = $('#ncustomertype').val();
    if(ctype =="") {
      alert("Enter Type of Customer Type")
    }
    else {
      $.get("{{url('add/customertype')}}", {ctype}, function(data) {
        $('#ncustomertype').val("");
        if(data == 0) {
          alert("Customer Type already exists")
        }
        else if(data > 0) {
          alert("Customer Type added");
          $('#modal-customer_type').modal('hide');
          $('#customer_type').append('<option value="'+data+'" selected>'+ctype.toUpperCase()+'</option>');
        }
      });
    }
  });  

</script>
</html>
