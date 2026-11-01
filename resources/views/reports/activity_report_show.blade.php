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
  <!-- jQuery 3 -->
<script src="{{asset('lte/bower_components/jquery/dist/jquery.min.js')}}"></script>
<script src="{{asset('export/tableexport-xls-bold-headers.js')}}"></script>


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
  <script>


  $(document).ready(function(){
    $(".export_excel").click(function(){

        $('#enquiry').tableExport({
          type: 'excel',
          escape: 'false',
          filename: 'activity_report.xls',
        });
    });
  });
  </script>
</head>
<body>
<div class="hidden-print text-center" style="float:left; margin:20px" >
        <a  class="btn btn-danger" href="{{URL::previous()}}">Back</a>
    </div>
<br><br><br>
  <!-- Main content -->
   <section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <i class=""></i> {{$employee->name ?? ''}} Activity List 
            <small class="pull-right">Date Range: {{date('d-m-Y',strtotime($from))}} - {{date('d-m-Y',strtotime($to))}}</small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->

      <div class="row invoice-info">
        <!-- /.col -->
      </div>
      <br>

      <!-- Table row -->
      <div class="row" style="page-break-after: always;">
        <div class="col-xs-12 table-responsive">
            <table class="table table-bordered table-striped" id="enquiry">
            <thead>
              <tr>
              <th>Sl No.</th>
              <th>Entered on</th>
              <th>Action</th>
              <th>Enquiry No</th>
              <th>Customer</th>
              <th>Customer Type</th>
              <th>Place</th>
              <th>Phone</th>
              <th>Lead Type</th>
              <th>Enquiry Date</th>
              <th>Enquiry Status</th>
              <th>Next Followup Date</th>
              <th>Purchase Date</th>
              <th>Purchase Value</th>
              <th>Employee</th>
              <th>Remarks</th>
              </tr>
            </thead>
            <tbody>
              <?php $c = 1; $pc = 0; $totalProductValue = 0; ?>
              @if($activity != NULL)
              @foreach($activity as $enq)
                <tr>
                <td>{{$c++}}</td>
                <td>{{date('d-m-Y',strtotime($enq->created_at))}}</td>
                <td>{{$enq->status->status_name ?? ''}}</td>
                <td>{{$enq->enquiry->enq_no ?? ''}}</td>
                <td>{{$enq->enquiry->customer->customer_name ?? ''}}</td>
                <td>{{$enq->enquiry->customer->customerType->customer_type_name ?? ''}}</td>
                <td>{{$enq->enquiry->customer->place->place ?? ''}}</td>
                <td>{{$enq->enquiry->customer->customer_phone ?? ''}}</td>
                <td>{{$enq->enquiry->lead->name ?? ''}}</td>
                <td>{{date('d-m-Y',strtotime($enq->enquiry->enq_date))}}</td>
                <td>{{$enq->enquiry->enqStatus->status_name ?? ''}}</td>
                <td>
                    @if($enq->status->status_name == 'FOLLOW UP')
                    {{date('d-m-Y',strtotime($enq->status_date))}}
                    @endif
                </td>
                <td>
                    @if($enq->status->status_name == 'SALE CLOSE')
                    {{date('d-m-Y',strtotime($enq->enquiry->purchase_date))}}
                    @endif
                </td>
                <td>
                    @if($enq->status->status_name == 'SALE CLOSE')
                    {{$enq->enquiry->purchase_value}}
                    @endif
                </td>
                <td>{{$enq->user->employee->name ?? ''}}</td>
                <td>{{$enq->status_remaks}}</td>
                </tr>
              @endforeach
              @endif
            </tbody>
            </table>

        </div>

        <!-- /.col -->
      </div>
      <!-- /.row -->



      <!-- this row will not appear when printing -->
      <div class="row no-print">
      <div class="hidden-print text-center" id="button">
          <button class="export_excel btn btn-success">Download Report</button>
      </div>
        <div class="col-xs-12">
          <a  target="_blank"  class="btn btn-success pull-right" onclick="window.print();"><i class="fa fa-print"></i> Print</a>
        </div>
      </div>
    </section>
    <!-- /.content -->
  <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>


