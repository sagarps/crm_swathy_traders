<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SALEUP</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('lte/bower_components/font-awesome/css/font-awesome.min.css')}}">
  <link rel="stylesheet" href="{{asset('custom_theme/css/login.css')}}">

</head>
<body class="hold-transition login-page">

   <div class="login_page_container">
        <div class="login_popup lctc">
            <div class="blocks left">
                <div class="company_logo_container">
                    <img class="company_logo lctc" src="{{asset('custom_theme/images/lalshan.png')}}">
                </div>
       <!--          <div class="welcome">Welcome to</div>
                <div class="company_caption">DISTRIBUTION MANAGEMENT SYSTEM</div> -->


                <!-- <div class="segments">
                  <img class="segment_image" src="{{asset('pics/NEOS_logo.png')}}">
                  <img class="segment_image" src="{{asset('pics/Rizza_logo.png')}}">
                  <img class="segment_image" src="{{asset('pics/Casso_logo.png')}}">

                </div>
 -->
            </div>
            <div class="blocks right">
                <div class="product_logo_container">
                    <!-- <img class="product_logo lctc" src="{{asset('logo/ecare.png')}}"> -->
                                 
                </div>
                <div class="product_caption">Log in</div>
                <div class="login_form_container">
                    <form class="login_form" method="POST" action="{{ route('login') }}">
                         <div class="input_container">
                            <div class="input_icon"><i class="fa fa-user"></i></div>
                            <input type="text" name="username" placeholder="Username" required="">

                        </div>
                        <div class="input_container">
                            <div class="input_icon"><i class="fa fa-lock"></i></div>
                            <input type="password" name="password" placeholder="Password" required="">

                        </div>
                        <button class="login_button" type="submit">SUBMIT</button>
                    {{ csrf_field() }}
                    </form>

                </div>
            </div>
        </div>
    </div>




<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="{{asset('lte/bower_components/jquery/dist/jquery.min.js')}}"></script>
   
</script>
</body>
</html>
