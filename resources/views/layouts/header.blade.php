<header class="main-header">
    <!-- Logo -->
    <a href="{{route('home')}}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>S</b>aleup</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>SALEUP</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      @if(isset($trialStatus))
        @if($trialStatus['status'] == 'active')
        <a href="#" class="alert alert-warning" role="button">
          Your trial expires in <strong id="remaining-time"></strong>.
          <script>
          function updateRemainingTime() {
            var remainingSeconds = {{ $trialStatus['remaining_days'] * 24 * 60 * 60 }};
            setInterval(function() {
              if (remainingSeconds > 0) {
                remainingSeconds--;
                var days = Math.floor(remainingSeconds / (24 * 60 * 60));
                var hours = Math.floor((remainingSeconds % (24 * 60 * 60)) / (60 * 60));
                var minutes = Math.floor((remainingSeconds % (60 * 60)) / 60);
                var seconds = remainingSeconds % 60;
                document.getElementById('remaining-time').innerText = days + " days " + hours + " hours " + minutes + " minutes " + seconds + " seconds";
              } else {
                document.getElementById('remaining-time').innerText = "Expired";
              }
            }, 1000);
          }
          updateRemainingTime();
          </script>
        </a>
        </a> @elseif($trialStatus['status'] == 'expired')
            <a href="#" class="alert alert-danger" role="button">
                  Your trial expired <strong>{{ $trialStatus['expired_days'] }}</strong> days ago. Please upgrade to continue.
                  <span id="current-time"></span>
                  <script>
                  setInterval(function() {
                    document.getElementById('current-time').innerText = new Date().toLocaleTimeString();
                  }, 1000);
                  </script>
                </a>
        @endif
      @endif
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
        <!--    Notifications: style can be found in dropdown.less  -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-danger">{{$escalated_count}}</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have notifications</li>
              <li>
               <!--  inner menu: contains the actual data -->
                <ul class="menu">
                  <li>
                    <a href="">
                      <i class="fa fa-users text-aqua"></i>  
                    </a>
                  </li>
                  @if(Auth::user()->userpermission(19))
                  @if($escalated_count > 0)
                  <li>
                    <a href="{{route('escalated.followup.index')}}">
                      <i class="fa fa-users text-red"> {{$escalated_count}} enquries escalated!!!</i> 
                    </a>
                  </li>
                  @endif
                  @endif
                </ul>
              </li>
            </ul>
          </li>
          
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="" class="dropdown-toggle" data-toggle="dropdown">
                <span class="hidden-xs">Hello , {{ Auth::user()->employee->name }} </span>
            </a>
          </li>
          <!-- Control Sidebar Toggle Button -->
            <form id="logout-form" action="{{url('/logout')}}" method="POST" style="display: none">
              {{ csrf_field() }}
            </form>
          <li class=""><a title="" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="icon icon-share-alt"></i> <i class="fa fa-sign-out fa-2x"></i></a></li>
        </ul>
      </div>
    </nav>
  </header>