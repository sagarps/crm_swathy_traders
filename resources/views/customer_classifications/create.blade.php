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
  <link rel="stylesheet" href="{{asset('lte/dist/css/style.css')}}">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue fixed sidebar-mini">
<div class="wrapper">
  
  @include('layouts/header')
  @include('layouts/leftside')

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Add Customer Classification</h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Masters</a></li>
        <li><a href="{{route('customer_classifications.index')}}">Customer Classification</a></li>
        <li class="active">Add</li>
      </ol>
    </section>

    @if(Session::has('exist')) 
      <div class="box-header with-border">
        <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          {{ Session::get('exist') }}
        </div>
      </div>
    @endif

    @if ($errors->any())
      <div class="box-header with-border">
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

    <section class="content">
      <div class="row">
        <section class="col-lg-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Create Customer Classification</h3>
              <a href="{{route('customer_classifications.index')}}" class="btn btn-default pull-right"><i class="fa fa-arrow-left"></i> Back to List</a>
            </div>

            <form class="form-horizontal" action="{{route('customer_classifications.store')}}" method="POST">
              @csrf
              <div class="box-body">
                <div class="form-group @error('name') has-error @enderror">
                  <label for="name" class="col-sm-2 control-label">Classification Name<span style="color: red;">*</span></label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="name" id="name" value="{{old('name')}}" placeholder="Enter classification name (e.g. VIP, Retail, Wholesale)" required autofocus>
                    @error('name')
                      <span class="help-block">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
              </div>

              <div class="box-footer">
                <div class="col-sm-offset-2 col-sm-6">
                  <button type="submit" class="btn btn-info">Save</button>
                  <button type="reset" class="btn btn-default" style="margin-left: 8px;">Refresh</button>
                  <a href="{{route('customer_classifications.index')}}" class="btn btn-default" style="margin-left: 8px;">Cancel</a>
                </div>
              </div>
            </form>
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
<script src="{{asset('lte/dist/js/adminlte.min.js')}}"></script>
</body>
</html>

