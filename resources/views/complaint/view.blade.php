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
          Complaint Management
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{route('complaint.index')}}"><i class="fa fa-dashboard"></i>Complaint Management</a></li>
          <li class="active">View</li>
        </ol>
      </section>

      <div class="modal modal-default fade" id="modal-log">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Complaint Log</h4>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Sl No</th>
                      <th>Status</th>
                      <th>Action</th>
                      <th>Date</th>
                    </tr>
                  </thead>
                  <tbody id="body">
                    <?php $c=1; ?>
                    @if($com_log)
                      @foreach($com_log as $log)
                        <tr>
                          <td>{{$c++}}</td>
                          <td>{{$log->status->status_name}}</td>
                          <td>{{$log->action_taken}}</td>
                          <td>{{date('d-m-Y',strtotime($log->created_at))}}</td>
                        </tr>
                      @endforeach
                    @endif
                  </tbody>
                </table>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-info pull-left" id="close" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-info pull-right" id="save_type">Save</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Main content -->
      <section class="content">
        <form action="" method="post">
         <div class="box">
            <div class="box-header">
              <h3 class="box-title">Complaint View</h3>
              <input type="hidden" name="edit_id" value="{{$complaint->id}}">
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              
              <div class="col-md-5">
                <div class="form-group">
                  <label class="control-label">Complaint No</label>
                  <input type="text" class="form-control" name="complaint_no" value="{{$complaint->order_no}}" readonly>
                </div>
                
              </div>
              <div class="col-md-1">
                <br>
                <!-- <a onclick="myFunction()" class="btn btn-default fa fa-copy" style="margin-top: 9px;margin-left: -25px;"></a> -->
              </div>
              <div class="col-md-5">
                <div class="form-group">
                <!--   <label class="control-label">Customer Search</label> -->
                <br>
                <br>
                  <div>
                    <br><br>
                   <!--  <input class="form-control" type="text" name="search" id="search" class="form-control customer_number" list="searchlist"  placeholder="Name / Mobile" autofocus="true" autocomplete="off">
                    <datalist id="searchlist">
                      <option></option>
                    </datalist> -->
                  </div>
                </div>
              </div>

              <div class="col-md-5">
                <div class="form-group">
                  <label class="control-label">Customer Name <font style="color: red;">*</font></label>
                  <div>
                    <input type="text" name="name" id="name" class="form-control" autocomplete="off" value="{{$complaint->customer->customer_name ?? ''}}" required readonly>
                  </div>
                </div>
              </div>
              <div class="col-md-1">
              </div>
              <div class="col-md-5">
                <div class="form-group">
                  <label class="control-label">Phone Number <font style="color: red;">*</font></label>
                  <div>
                    <input type="number" name="phone" id="phone" class="form-control" autocomplete="off" value="{{$complaint->customer->customer_phone ?? ''}}" required readonly>
                  </div>
                </div>
              </div>

              <div class="col-md-5">
                <div class="form-group">
                  <label class="control-label">Address <font style="color: red;">*</font></label>
                  <div>
                    <textarea class="form-control" name="address" id="address" required readonly>{{$complaint->customer->address ?? ''}}</textarea>
                  </div>
                </div>
              </div>
              <div class="col-md-1">
              </div>
              <div class="col-md-5">
                <div class="form-group">
                  <label class="control-label">Date <font style="color: red;">*</font></label>
                  <div>
                    <input type="text" class="form-control datepicker" id="date" name="date" placeholder="Enter" required="" value="{{date('d-m-Y',strtotime($complaint->complaint_date))}}" readonly>
                  </div>
                </div>
              </div>

              <div class="col-md-11">
                <div class="form-group">
                  <label class="control-label">Complaint Details <font style="color: red;">*</font></label>
                  <div>
                    <textarea class="form-control" name="details" id="details" required readonly>{{$complaint->complaint_details}}</textarea>
                  </div>
                </div>
              </div>
              
              <div class="col-md-5">
                <div class="form-group">
                  <label class="control-label">Complaint Type <font style="color: red;">*</font></label>
                  <div>
                    <select class="form-control " name="type" id="type" required style="width: 100%;" readonly>
                      <option value="">--Select--</option>
                      @if($type != null)
                        @foreach($type as $typ)
                        @if($complaint->complaint_type_id == $typ->id)
                          <option value="{{$typ->id}}"  selected >{{$typ->type_name}}</option>
                        @endif
                        @endforeach
                      @endif
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-1">
                <br>
              <!--   <button class="btn btn-info" type="button" id="typpopup" style="margin-left: -25px;margin-top: 5px;" style="color: blue;" data-toggle="modal" data-target="#modal-type"><i class="fa fa-plus"></i> Type</button> -->
              </div>
              <div class="col-md-5">
                <div class="form-group">
                  <label class="control-label">Complaint Status <font style="color: red;">*</font></label>
                  <div>
                    <select class="form-control" name="status" required readonly>
                      <option value="">--Select--</option>
                      @if($status != null)
                        @foreach($status as $stat)
                        @if($stat->id == $comp_stat->complaint_status_id)
                          <option value="{{$stat->id}}"  selected >{{$stat->status_name}}</option>
                        @endif
                        @endforeach
                      @endif
                    </select>
                  </div>
                </div>
              </div>

              

            </div>

            <div class="box-footer">
              <div class="col-sm-11">
              <button type="button" name="log" id="log" class="btn btn-success log" data-toggle="modal" data-target="#modal-log">Log</button>
             <!--  <input class="btn btn-primary pull-right" type="submit" name="submit" value="Submit"> -->
              </div>
            </div>

         </div>
        </form>
      </section>

    </div>

  
  @include('layouts/footertag')

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

  <!-- iCheck 1.0.1 -->
<script src="{{asset('lte/plugins/iCheck/icheck.min.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('lte/bower_components/select2/dist/js/select2.full.min.js')}}"></script>

<!-- DataTables -->
<script src="{{asset('lte/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('lte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
</body>

<script type="text/javascript">
 $(function () {
    $('#example1').DataTable()
    $('.select2').select2();
    
  })

 $('.datepicker').datepicker({
      autoclose: true,
      format: 'dd-mm-yyyy'
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

$(document).on('input','#search',function(){
      var itemList = $("#searchlist");
      itemList.empty();
      var name = $(this).val();
      if(name.length >= 3){     
          $.get("{{url('complaint/customer/list')}}", {term:name}, function(resNames) {
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

  $(document).on('change','#search',function(){
    if($(this).val().length >= 3){
        var value = $(this).val();
        $.get("{{url('complaint/customer/show')}}", {value}, function(data) {
            if(data.length != 0){
                $("#name").val(data.customer_name);
                $("#phone").val(data.customer_phone);
                $("#address").val(data.address);
            }
            else{
                $("#name").val('');
                $("#phone").val('');
                $("#address").val(''); 
            }
        });
    }
  });

  $(document).on('click','#save_type',function(){
    var type = $('#new_type').val();
    if(type != ''){
      $.get("{{url('complaint/type/save')}}", {type}, function(data) {
        if(data == 0){
          alert("Type name allready exist.");
        }
        else{
          $('#type').append('<option value="'+data.id+'" selected>'+data.type_name+'</option>');
        }
        $('#close').trigger('click');
      });
    }
    else{
      alert("Enter new type name.");
    }
  });

  $(document).on('click','#typpopup',function(){
    $('#new_type').val('');
  });

</script>
</html>
