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
          Add Freebie Issue
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{route('freebie.issue')}}"><i class="fa fa-dashboard"></i>Freebie</a></li>
          <li class="active">Issue</li>
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
                    <li class="active" ><a href="#tab_2-2" data-toggle="tab">Add Freebie Issue Form</a></li>
                    <li class="pull-left header"><i class="fa fa-th"></i></li>
                  </ul>
                  <div class="tab-content">
                    <div class="pull-right">
                      </div>
                    <div class="tab-pane"  id="tab_1-1">
                     
                      <div class="box-header with-border">
                        <div class="alert alert-danger">
                          <strong style="font-size: 18px;"> Please Enter Freebie issue Details.</strong>
                        </div>
                      </div>
             
                      <br>
                    </div>

                    <div class="tab-pane"  id="tab_3-3">
                     
                      <div class="box-header with-border">
                        <div class="alert alert-danger">
                          <strong style="font-size: 18px;"> Please Enter Freebie Issue Details.</strong>
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
                    <div class="modal modal-default fade" id="modal-freebie">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Add Freebie Type</h4>
                          </div>

                          <form class="form-horizontal form" action="" method="">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="col-md-12">
                                <div class="col-md-5">
                                  <div class="form-group">
                                    <label class="control-label">Freebie Type<i style="color: red">*</i></label>
                                    <div>
                                    <input type="text" name="nfreebie" id="nfreebie" class="form-control" autocomplete="off" required>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-md-1">
                                </div>
                                
                            </div>
                          <div class="modal-footer">
                          <button type="submit" class="btn btn-info" id="add_freebie">Add</button>

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
                        <h3 class="box-title">Freebie Form</h3>
                      </div>
                        <!-- form start -->
                        
                        <form class="form-horizontal" action="{{route('create.freebie_issue')}}" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}"> 
                        <div class="box-body">

                         
                        <div class="col-md-12">
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Associate Search</label>
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
                          </div>

                          <div class="col-md-12">
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Type Of Association<i style="color: red">*</i></label>
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
                                <label class="control-label">Name Of Associate<i style="color: red">*</i></label>
                                <div>
                                <select class="form-control" name="inf_name" id="inf_name" required>
                                    <option value="">--Select--</option>
                                    @foreach($ass_names as $names)
                                      <option value="{{$names->id}}">{{$names->associate_name}}</option>
                                    @endforeach
                                  </select>    
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-12">
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Contact Person<i style="color: red">*</i></label>
                                <div>
                                  <input type="text" name="cont_person" id="cont_person" class="form-control" autocomplete="off" required>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-1">
                            </div>
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Contact Number<i style="color: red">*</i></label>
                                <div>
                                  <input type="number" name="cont_no" id="cont_no" class="form-control" autocomplete="off" required>
                                </div>
                              </div>
                            </div>
                          </div>  

                              <div class="col-md-12">
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Place</label><i style="color: red">*</i>
                                <div>
                                <select class="form-control" name="place" id="place" required>
                                    <option value="">--Select--</option>
                                    @foreach($places as $place)
                                      <option value="{{$place->id}}">{{$place->place}}</option>
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
                                <label class="control-label">Address</label><i style="color: red">*</i>
                                <div>
                                <textarea cols="67" rows="2" name="address" id="address" class="form-control" required></textarea>
                                </div>
                              </div>
                            </div>
                          </div>

                          
                          <div class="col-md-12">
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Freebie Type</label><i style="color: red">*</i>
                                <div>
                                <select class="form-control" name="freebie_type" id="freebie_type" required>
                                    <option value="">--Select--</option>
                                    @foreach($freebie_types as $types)
                                      <option value="{{$types->id}}">{{$types->freebie_type}}</option>
                                    @endforeach
                                  </select>     
                               </div>
                              </div>
                            </div>
                            <div class="col-md-1">
                              <div>
                              <br>
                              <i class="fa fa-plus-circle" aria-hidden="true" style="color:#3c8dbc;font-size:30px;position:relative;margin-top: 9px;" data-toggle="modal" data-target="#modal-freebie"></i>
                              </div>
                            </div>
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Freebie Value</label><i style="color: red">*</i>
                                <div>
                                <input type="number" name="free_value" id="free_value" class="form-control" autocomplete="off" required>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-12">
                          <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Freebie Issue Date<i style="color: red">*</i></label>
                                <div>
                                  <input type="text" name="free_date" id="free_date" class="form-control datepicker check"  autocomplete="off" required value="{{date('d-m-Y')}}">
                                </div>
                              </div>
                            </div>
                            <div class="col-md-1">
                            </div>
                            <div class="col-md-5">
                              <div class="form-group">
                                <label class="control-label">Barcode<i style="color: red">*</i></label>
                                <div>
                                  <input type="text" name="barcode" id="barcode" class="form-control"  autocomplete="off" required value="">
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        
                        <!-- /.box-body -->
                        <div class="box-footer">
                          <div class="col-md-2">
                          <!--   <button type="reset" class="btn btn-primary">Log</button> -->
                          </div>
                          <div class="pull-right">
                          <button type="submit" class="btn btn-info" id="save">Save</button>

                            <a href="" class="btn btn-default" id="refresh">Refresh</a>
                          </div>
                          <a id="view" class="btn btn-info">View last 5 freebie issued</a>
                        </div>
                        <!-- /.box-footer -->
                      </form>
                    </div>
                    <!-- /.tab-pane -->
                       <!-- Main content -->

      <section class="content"id="freebie_issue" style="visibility:hidden">
         <div class="box">

         <div class="box">
            <div class="box-header">
              <h3 class="box-title">Freebie Issue</h3>
              
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead >
                <tr>
                    <th>Sl No.</th>
                    <th>Freebie Issue Type</th>
                    <th>Freebie Issue Value</th>
                    <th>Freebie Issue Date</th>         
                </tr>
                </thead>
                <tbody id="freebie">
             
                </tbody>
              </table>
            </div>
           
          </section>
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

  
          <!-- /.Left col -->
          
       
  
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
  $('.datepicker').datepicker({
      autoclose: true,
      format: 'dd-mm-yyyy'
    })
</script>

<script type="text/javascript">
  // $(function () {
  //     $('#example1').DataTable()
    
  // })

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

  $('#refresh').click(function() {
    location.reload();
});

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
          $.get("{{url('associate/list')}}", {term:name}, function(resNames) {
              itemList.empty();
              if(resNames.length) {
                  $.each(resNames, function(index,element) {
                      var info = element.associate_name+'/'+element.contact_no;
                      var opt  = $("<option></option>").attr("value", info);
                      itemList.append(opt);
                  });
              }     
          },"json");
      }
      $("#search").trigger("change");
  });

  $(document).on('change','#search',function(){
    $('#save').prop('disabled',false);

    if($(this).val().length >= 3){
        var value = $(this).val();
        $.get("{{url('associate/showDetails')}}", {value}, function(data) {
            if(data.length != 0){
                $("#inf_type").val(data.type);
                $("#cont_no").val(data.contact_no);
                $("#cont_person").val(data.contact_person);
                $("#place").val(data.place_id);
                $("#inf_name").val(data.id);
                $("#address").val(data.address);
                var ass_id  = $('#inf_name').val();
                var freebie_date  = $('#free_date').val();
                $.get("{{url('check/freebie/issues')}}", {ass_id,freebie_date}, function(data) {
                  if(data == 0) {
                    alert("Freebie can not be issued!");
                            $('#save').prop('disabled',true);
                            window.stop();
                  }
                  else {
                    alert("Freebie can be issued!");
                    $('#save').prop('disabled',false);
                  }
                  
                });

            }
            else{
                $("#inf_name").val('');
                $("#cont_no").val('');
            }
        });
    }
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


  $(document).on('click','#add_freebie',function(e){
    e.preventDefault();
    e.stopPropagation();
    var freebie = $('#nfreebie').val();
    if(freebie =="") {
      alert("Enter Freebie Type")
    }   
    else {
      $.get("{{url('add/freebie')}}", {freebie}, function(data) {
        $('#nfreebie').val("");
        if(data == 0) {
          alert("Freebie type already exists")
        }
        else if(data > 0) {
          alert("Freebie type added");
          $('#modal-freebie').modal('hide');
          $('#freebie_type').append('<option value="'+data+'" selected>'+freebie.toUpperCase()+'</option>');
        }
      });
    }
  });
 

  $(document).on('change','#inf_name',function(){
    $('#save').prop('disabled',false);

    var ass_id  = $('#inf_name').val();
    if(ass_id == "") {
      alert("Select Associate!");

    }
    else {
    var freebie_date  = $('#free_date').val();
    $.get("{{url('check/freebie/issues')}}", {ass_id,freebie_date}, function(data) {
      if(data == 0) {
        alert("Freebie can not be issued!");
                $('#save').prop('disabled',true);

      }
      else {
        alert("Freebie can be issued!");
        $('#save').prop('disabled',false);
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
  $(document).on('click','#view',function(){
    var inf = $('#inf_name').val();
    $('#freebie').empty();
     if(inf) {
            $.get("{{url('get/freebieIssues')}}", {inf}, function(data) {
              if(data.length>0) {
              var i=1;
               $.each(data,function(index,element){
                var date = element.freebie_issue_date.split("-").reverse().join("-");

                var row = '<tr><td>'+i+'</td><td>'+element.freebie_type+'</td><td>'+element.freebie_value+'</td><td>'+date+'</td>';
                   $('#freebie').append(row);
                   i++;
               }); 
               $('#freebie_issue').css("visibility","visible");
              }
              else {
                alert("No freebies are issued");
              }
           });
     }
     else {
       alert("Please select associate name");
     }
  });

</script>
</html>
