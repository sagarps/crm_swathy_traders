<!DOCTYPE html>
<html>
<head>
  @include('layouts/title')
  
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="{{asset('lte/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('lte/bower_components/select2/dist/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{asset('lte/bower_components/font-awesome/css/font-awesome.min.css')}}">
  <link rel="stylesheet" href="{{asset('lte/bower_components/Ionicons/css/ionicons.min.css')}}">
  <link rel="stylesheet" href="{{asset('lte/dist/css/AdminLTE.min.css')}}">
  <link rel="stylesheet" href="{{asset('lte/dist/css/skins/_all-skins.min.css')}}">
  <link rel="stylesheet" href="{{asset('lte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('lte/dist/css/style.css')}}">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue fixed sidebar-mini">
<div class="wrapper">
  
  @include('layouts/header')
  @include('layouts/leftside')

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Customer Classification List</h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Masters</a></li>
        <li class="active">Customer Classification</li>
      </ol>
    </section>

    @if(Session::has('save')) 
      <div class="box-header with-border">
        <div class="alert alert-success alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          {{ Session::get('save') }}
        </div>
      </div>
    @endif

    @if(Session::has('update')) 
      <div class="box-header with-border">
        <div class="alert alert-info alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          {{ Session::get('update') }}
        </div>
      </div>
    @endif

    @if(Session::has('delete')) 
      <div class="box-header with-border">
        <div class="alert alert-success alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          {{ Session::get('delete') }}
        </div>
      </div>
    @endif

    @if(Session::has('error')) 
      <div class="box-header with-border">
        <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          {{ Session::get('error') }}
        </div>
      </div>
    @endif

    <section class="content">
      <div class="row">
        <section class="col-lg-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Customer Classifications</h3>
              <u style="float: right; margin-right: 15px">
                <i class="fa fa-fw fa-plus" style="color: blue;"></i><a href="{{route('customer_classifications.create')}}" style="font-size: 16px;">Add</a>
              </u>
            </div>

            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th style="width: 10%;">Sl No.</th>
                    <th>Classification Name</th>
                    <th style="width: 15%;">Customers Count</th>
                    <th style="width: 10%; text-align: center;">Edit</th>
                    <th style="width: 10%; text-align: center;">Delete</th>
                  </tr>
                </thead>

                <tbody>
                  <?php $c = 1; ?>
                  @foreach($classifications as $item)
                  <tr> 
                    <td>{{$c++}}</td>
                    <td><strong>{{$item->name}}</strong></td>
                    <td><span class="badge bg-blue">{{$item->customers_count}}</span></td>
                    <td class="text-center">
                      <a href="{{route('customer_classifications.edit', $item->id)}}" title="Edit">
                        <i class="fa fa-fw fa-pencil" style="color: blue;"></i>
                      </a>
                    </td>
                    <td class="text-center">
                      @if($item->customers_count == 0)
                        <a href="{{route('customer_classifications.destroy', $item->id)}}" 
                           onclick="return confirm('Are you sure you want to delete this classification?');" 
                           title="Delete">
                          <i class="fa fa-fw fa-trash" style="color: red;"></i>
                        </a>
                      @else
                        <span title="Cannot delete: customers assigned"><i class="fa fa-fw fa-trash" style="color: #ccc; cursor: not-allowed;"></i></span>
                      @endif
                    </td>
                  </tr>
                  @endforeach                        
                </tbody>
              </table>
            </div>
          </div>
        </section>
      </div>
    </section>
  </div>
  
  @include('layouts/footertag')
  <div class="control-sidebar-bg"></div>
</div>

<script src="{{asset('lte/bower_components/jquery/dist/jquery.min.js')}}"></script>
<script src="{{asset('lte/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<script src="{{asset('lte/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('lte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
<script src="{{asset('lte/dist/js/adminlte.min.js')}}"></script>

<script type="text/javascript">
 $(function () {
    $('#example1').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    });
 });
</script>
</body>
</html>

