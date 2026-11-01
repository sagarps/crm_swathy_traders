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
          filename: 'enquiry_report.xls',
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
            <i class=""></i> Associate Performance List
            <small class="pull-right">Date Range: {{date('d-m-Y',strtotime($from))}} - {{date('d-m-Y',strtotime($to))}}</small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->

      <div class="row invoice-info">
        {{-- @if($status != NULL)
        <div class="col-sm-3 invoice-col">
          <strong> Status   : {{$status->status_name}} </strong>
        </div>
        @endif
        @if($employe != NULL)
        <div class="col-sm-3 invoice-col">
          <strong> Employee   : {{$employe->name}} </strong>
        </div>
        @endif --}}

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
              <th>Associate Name</th>
              <th>Associate Type</th>
              <th>Total Lead</th>
              <th>Convert</th>
              <th>Lost</th>
              <th> Follow-up</th>
              <th> Conversion(%)</th>
              <th>FreeBeeis Issued</th>
              <th>FreeBiee Value</th>
              <th>Purchase Value</th>
              <th>Staff</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($datas  as $data)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data->associate_name }}</td>
                    <td>{{ $data->associate_type->influencer_type }}</td>
                    <td>{{ $enquiry->totlead($from,$to,$data->id) }}</td>
                    <td>{{ $enquiry->totCnvrt($from,$to,$data->id) }}</td>
                    <td>{{ $enquiry->totLost($from,$to,$data->id) }}</td>
                    <td>{{ $enquiry->totfollowup($from,$to,$data->id) }}</td>
                    <td>{{ $enquiry->convertPer($from,$to,$data->id) }}</td>
                    <td>{{ $enquiry->Totfeebee($from,$to,$data->id) }}</td>
                    <td>{{ $enquiry->feebeeVal($from,$to,$data->id) }}</td>
                    <td>{{ $enquiry->totPurchValue($from,$to,$data->id) }}</td>
                    <td>
                        @foreach( $enquiry->staffs($from,$to,$data->id) as $info)
                        {{ $loop->first ? '' : ', ' }}
                     {{ $info }}
                        @endforeach
                    </td>
                </tr>
                @endforeach

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


