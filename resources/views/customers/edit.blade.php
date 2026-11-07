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

  <link rel="stylesheet" href="{{asset('lte/dist/css/style.css')}}">



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
          Add Customer
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{route('customers.index')}}"><i class="fa fa-dashboard"></i>Customer Master</a></li>
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
      @if(Session::has('error'))
          <div class="box-header with-border" id="saveTarget">
              <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 {{Session::get('error')}}
              </div>
          </div>
      @endif
      @if ($errors->any())
          <div class="box-header with-border" id="saveTarget">
              <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <ul>
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
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
                    <!-- <li><a href="#tab_3-3" data-toggle="tab">Quotation</a></li> -->
                    <!-- <li><a href="#tab_1-1" data-toggle="tab">Products</a></li> -->
                    <li class="active" ><a href="#tab_2-2" data-toggle="tab">Add Customer</a></li>
                    <li class="pull-left header"><i class="fa fa-th">
                      <a href="{{route('collection.followup.edit',[$customer->id,date('Y-m-d'),date('Y-m-d'),$customer->followup_type])}}" class="btn btn-primary">
                            Follow Up & Add Bill</a>
                    </i></li>
                  </ul>
                  <div class="tab-content">
                    <div class="pull-right">
                      </div>
                    <div class="tab-pane"  id="tab_1-1">

                      <div class="box-header with-border">
                        <div class="alert alert-danger">
                          <strong style="font-size: 18px;"> Please Enter Enquiry Details.</strong>
                        </div>
                      </div>

                      <br>
                    </div>

                    <div class="tab-pane"  id="tab_3-3">

                      <div class="box-header with-border">
                        <div class="alert alert-danger">
                          <strong style="font-size: 18px;"> Please Enter Enquiry Details.</strong>
                        </div>
                      </div>

                      <br>
                    </div>
                    <!-- /.tab-pane -->

                    <!-- Add place modal -->
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

                    <!-- Add customer classification modal -->
                    <div class="modal modal-default fade" id="modal-customer_classification">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Add Customer Classification</h4>
                          </div>

                          <form class="form-horizontal form" action="" method="">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="col-md-12">
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label class="control-label">Classification Name<i style="color: red">*</i></label>
                                    <div>
                                    <input type="text" name="ncustomerclassification" id="ncustomerclassification" class="form-control" autocomplete="off" required>
                                    </div>
                                  </div>
                                </div>
                            </div>
                          <div class="modal-footer">
                          <button type="submit" class="btn btn-info" id="add_customer_classification">Add</button>
                            <button type="button" class="btn btn-info pull-right" data-dismiss="modal">Close</button>
                          </div>
                        </form>
                        </div>
                      </div>
                    </div>
                  <!-- add customer classification modal ends -->


                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="tab_2-2">
                      <div class="box-header">
                        <h3 class="box-title">Customer Form</h3>
                      </div>
                        <!-- form start -->

                        <form class="form-horizontal" action="{{route('customers.update',$customer->id)}}" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}">
                        <input type="hidden" name="office_id" value="{{Auth::user()->office_id}}">
                        <div class="box-body">


                        <div class="col-md-12">
                            <div class="col-md-5">
                              <div class="form-group">
                              <label class="control-label">Customer Name<i style="color: red">*</i></label>
                                <div>
                                <input type="text" name="customer_name" id="customer_name" class="form-control" autocomplete="off" value="{{ old('customer_name', $customer->customer_name) }}" required>
                                @error('customer_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                </div>
                              </div>

                            </div>
                            <div class="col-md-1">
                              <div>
                              <br>
                              <!-- <i class="fa fa-plus-circle" aria-hidden="true" style="color:#3c8dbc;font-size:30px;position:relative;margin-top: 9px;" data-toggle="modal" data-target="#modal-associate"></i> -->
                              </div>
                            </div>
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Contact Number<i style="color: red">*</i></label>
                                <div>
                                  <input type="number" name="customer_phone" id="customer_phone" class="form-control" autocomplete="off" value="{{ old('customer_phone', $customer->customer_phone) }}" required>
                                  @error('customer_phone')
                                    <span class="text-danger">{{ $message }}</span>
                                  @enderror
                                </div>
                              </div>
                            </div>
                            
                          </div>

                          <div class="col-md-12">
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Customer Type<i style="color: red">*</i></label>
                                <div>
                                  <select class="form-control" name="customer_type_id" id="customer_type" required>
                                    <option value="">--Select--</option>
                                    @foreach($customer_types as $type)
                                      <option value="{{$type->id}}"  @if($type->id == $customer->customer_type_id) selected @endif>{{$type->customer_type_name}}</option>
                                    @endforeach
                                  </select>
                                </div>
                              </div>  

                            </div>
                            <div class="col-md-1">
                              <div>
                              <br>
                              <i class="fa fa-plus-circle" aria-hidden="true" style="color:#3c8dbc;font-size:30px;position:relative;margin-top: 9px;" data-toggle="modal" data-target="#modal-customer_type"></i>
                              </div>
                            </div>
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Alternative Number</label>
                                <div>
                                  <input type="number" name="alternate_phone" id="customer_phone" class="form-control" value="{{ old('alternate_phone',$customer->alternate_phone) }}" autocomplete="off" >
                                  @error('alternate_phone')
                                    <span class="text-danger">{{ $message }}</span>
                                  @enderror
                                </div>
                              </div>
                            </div>
                            
                          </div>  

                          <div class="col-md-12">
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Place<i style="color: red">*</i></label>
                                <div>
                                <select class="form-control select2" name="place_id" id="place" required>
                                  <option value="">--Select--</option>
                                  @foreach($places as $place)
                                  <option value="{{$place->id}}" @if($place->id == $customer->place_id) selected @endif>{{$place->place}}</option>
                                  @endforeach
                                </select>
                               </div>
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
                                <label class="control-label">Address<i style="color: red">*</i></label>
                                <div>
                                <textarea cols="67" rows="2" name="address" id="address" class="form-control">{{ old('address',$customer->address) }}</textarea>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="col-md-5">
                              <div class="form-group @error('customer_classification_id') has-error @enderror">
                                <label class="control-label">Customer Classification<i style="color: red">*</i></label>
                                <div>
                                  <select class="form-control" name="customer_classification_id" id="customer_classification_id" required>
                                    <option value="">--Select Classification--</option>
                                    @foreach($classifications as $classification)
                                      <option value="{{$classification->id}}" {{ old('customer_classification_id', $customer->customer_classification_id) == $classification->id ? 'selected' : '' }}>
                                        {{$classification->name}}
                                      </option>
                                    @endforeach
                                  </select>
                                  @error('customer_classification_id')
                                    <span class="help-block">{{ $message }}</span>
                                  @enderror
                                </div>
                              </div>
                            </div>
                            <div class="col-md-1">
                              <div>
                                <br>
                                <i class="fa fa-plus-circle" aria-hidden="true" style="color:#3c8dbc;font-size:30px;position:relative;margin-top: 9px;cursor:pointer;" data-toggle="modal" data-target="#modal-customer_classification" title="Add Customer Classification"></i>
                              </div>
                            </div>
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Followup Type<i style="color: red">*</i></label>
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
                          </div>
                          @if(Auth::user()->userpermission(23) && 'NOT' == 'APPLICABLE')
                          <div class="col-md-12">
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Freeby issue days</label>
                                <div>
                                    <input type="number" name="freeby" id="freeby" class="form-control" autocomplete="off" >
                                  </div>
                              </div>
                            </div>
                          </div>
                          @endif



                        <!-- /.box-body -->
                        <div class="box-footer">
                          <div class="col-md-2">
                            <!-- <a href="{{route('collection.followup.edit',[$customer->id,date('Y-m-d'),date('Y-m-d')])}}" class="btn btn-primary">
                            Follow Up & Add Bill</a> -->
                          </div>
                          <div class="pull-right">
                          <button type="submit" class="btn btn-info">Save</button>

                            <a href="{{route('enquiry.index')}}" class="btn btn-default">Back</a>
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
<!-- Select2 -->
<script src="{{asset('lte/bower_components/select2/dist/js/select2.full.min.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('lte/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('lte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
</body>

<script type="text/javascript">
  $('.datepicker').datepicker({
      autoclose: true,
      format: 'dd-mm-yyyy'
    })
  $("#place").select2();  
</script>

<script type="text/javascript">
  $(function () {
      $('#example1').DataTable()

  })

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
          $.get("{{url('customer/list')}}", {term:name}, function(resNames) {
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
        $.get("{{url('check/phone')}}", {num}, function(data) {
            if(data != ''){
                alert("Number allready used by another customer.");
                $('#phone').val('');
            }
        });
      }
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
          alert("Place already exists");
            $("#place").find('option').filter(function() {
            return $(this).text() === place.toUpperCase();
            }).prop('selected', true).trigger('change');
        }
        else if(data > 0) {
          alert("Place added");
          $('#modal-place').modal('hide');
          $('#place').append('<option value="'+data+'" selected>'+place.toUpperCase()+'</option>');
          $("#place").select2();
        }
      });
    }
  });


  $(document).on('click','#add_customer_type',function(e){
    e.preventDefault();
    e.stopPropagation();
    var ctype = $('#ncustomertype').val();
    if(ctype =="") {
      alert("Enter Customer Type")
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


  $(document).on('click','#add_customer_classification',function(e){
    e.preventDefault();
    e.stopPropagation();
    var cname = $('#ncustomerclassification').val();
    if(cname =="") {
      alert("Enter Customer Classification Name")
    }
    else {
      $.get("{{url('add/customerclassification')}}", {cname}, function(data) {
        $('#ncustomerclassification').val("");
        if(data == 0) {
          alert("Customer Classification already exists")
        }
        else if(data > 0) {
          alert("Customer Classification added");
          $('#modal-customer_classification').modal('hide');
          $('#customer_classification_id').append('<option value="'+data+'" selected>'+cname.toUpperCase()+'</option>');
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
                $("#place").val(data.place);

            }
            else{
                $("#name").val('');
                $("#phone").val('');
            }
        });
    }
  });

</script>
</html>
