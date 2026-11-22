<aside class="main-sidebar">
<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
<!-- Sidebar user panel -->
<div class="user-panel">
  <div class="pull-left image">
    <img src="{{asset('lte/dist/img/user.png')}}" class="img-circle" alt="User Image">
  </div>
  <div class="pull-left info">
    <p>{{ Auth::user()->username }}</p>
    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
  </div>
</div>
<!-- sidebar menu: : style can be found in sidebar.less -->
  <ul class="sidebar-menu" data-widget="tree">
<!--  NEW Day  -->
  @if(Auth::user()->userpermission(10) || Auth::user()->userpermission(2) || Auth::user()->userpermission(1)|| Auth::user()->userpermission(7)|| Auth::user()->userpermission(8)|| Auth::user()->userpermission(9)|| Auth::user()->userpermission(22))
    <li class="treeview">
      <a href="#">
        <i class="fa fa-dashboard"></i> <span>Admin</span>
        <span class="pull-right-container">
        <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        @if(Auth::user()->userpermission(10)  || Auth::user()->userpermission(1))
          <li><a href="{{route('office.index')}}"><i class="fa fa-circle-o"></i>Office</a></li>
        @endif
        @if(Auth::user()->userpermission(1))
          <li><a href="{{route('admin.office.index')}}"><i class="fa fa-circle-o"></i>Clients</a></li>
        @endif
        @if(Auth::user()->userpermission(2))
          <li><a href="{{route('role.index')}}"><i class="fa fa-circle-o"></i>Role</a></li>
        @endif
         @if(Auth::user()->userpermission(7))
          <li><a href="{{route('designation.index')}}"><i class="fa fa-circle-o"></i>Designation</a></li>
        @endif
        @if(Auth::user()->userpermission(8))
          <li><a href="{{route('employee.index')}}"><i class="fa fa-circle-o"></i>Employees</a></li>
        @endif
        @if(Auth::user()->userpermission(9))
          <li><a href="{{route('user.index')}}"><i class="fa fa-circle-o"></i>Users</a></li>
        @endif
        @if(Auth::user()->userpermission(22))
        <li><a href="{{route('associate.index')}}"><i class="fa fa-circle-o"></i>Business Associates</a></li>
        @endif
        @if(Auth::user()->userpermission(1))
        <li><a href="{{route('security.dashboard')}}"><i class="fa fa-shield"></i>Security & Monitoring</a></li>
        @endif
      </ul>
    </li>
  @endif
  @if(Auth::user()->userpermission(25))
    <li class="treeview">
      <a href="#">
        <i class="fa fa-dashboard"></i> <span>Receivable</span>
        <span class="pull-right-container">
        <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        @if(Auth::user()->userpermission(25))
          <li><a href="{{route('collection.followups.index')}}"><i class="fa fa-circle-o"></i>Customer Outstanding</a></li>
        @endif        
      </ul>
    </li>
  @endif
  @if(Auth::user()->userpermission(5) || Auth::user()->userpermission(6) || Auth::user()->userpermission(3) || Auth::user()->userpermission(4) || Auth::user()->userpermission(14) || Auth::user()->userpermission(1) || Auth::user()->userpermission(24))
    <li class="treeview">
      <a href="#">
        <i class="fa fa-dashboard"></i> <span>Master</span>
        <span class="pull-right-container">
        <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        @if(Auth::user()->userpermission(5))
          <li><a href="{{route('unit.index')}}"><i class="fa fa-circle-o"></i>UOM</a></li>
        @endif
        @if(Auth::user()->userpermission(6) || Auth::user()->userpermission(1))
          <li><a href="{{route('config.index')}}"><i class="fa fa-circle-o"></i>Configuration</a></li>
        @endif
        @if(Auth::user()->userpermission(3))
          <li><a href="{{route('product.category.index')}}"><i class="fa fa-circle-o"></i>Category Master</a></li>
        @endif
        @if(Auth::user()->userpermission(4))
          <li><a href="{{route('product.index')}}"><i class="fa fa-circle-o"></i>Product Master</a></li>
        @endif
        @if(Auth::user()->userpermission(14))
          <li><a href="{{route('price.list.index')}}"><i class="fa fa-circle-o"></i>Price List</a></li>
        @endif
        @if(Auth::user()->userpermission(24))
          <li><a href="{{route('customer_classifications.index')}}"><i class="fa fa-circle-o"></i>Customer Classification</a></li>  
          <li><a href="{{route('customers.index')}}"><i class="fa fa-circle-o"></i>Customer Master</a></li>
        @endif
      </ul>
    </li>
  @endif
  @if(Auth::user()->userpermission(13) || Auth::user()->userpermission(11) || Auth::user()->userpermission(12) || Auth::user()->userpermission(16))
    <li class="treeview">
      <a href="#">
        <i class="fa fa-dashboard"></i> <span>Sales & Marketing</span>
        <span class="pull-right-container">
        <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        @if(Auth::user()->userpermission(13))
          <li><a href="{{route('team.mapping.index')}}"><i class="fa fa-circle-o"></i>Team Mapping</a></li>
        @endif
        @if(Auth::user()->userpermission(16) && !Auth::user()->userpermission(11))
          <li><a href="{{route('admin.enquiry.index')}}"><i class="fa fa-circle-o"></i>Lead Management</a></li>
        @endif
        @if(Auth::user()->userpermission(11))
          <li><a href="{{route('enquiry.index')}}"><i class="fa fa-circle-o"></i>Enquiry Management</a></li>
        @endif
        @if(Auth::user()->userpermission(20))
        <li><a href="{{route('followups.index')}}"><i class="fa fa-circle-o"></i>Followups</a></li>
        @endif
        @if(Auth::user()->userpermission(21))
        <li><a href="{{route('freebie.issue')}}"><i class="fa fa-circle-o"></i>Freebie Issue</a></li>
        @endif
        @if(Auth::user()->userpermission(19))
          <li><a href="{{route('escalated.followup.index')}}"><i class="fa fa-circle-o"></i>Escalated Followup</a></li>
        @endif
        @if(Auth::user()->userpermission(12))
          <li><a href="{{route('team.enquiry.index')}}"><i class="fa fa-circle-o"></i>Team Enquiry</a></li>
        @endif


      </ul>
    </li>
    @if(Auth::user()->userpermission(15))
    <li class="treeview">
        <a href="#">
          <i class="fa fa-dashboard"></i> <span>Reports</span>
          <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
            @if(Auth::user()->userpermission(15))
            <li><a href="{{route('enquiry.report')}}"><i class="fa fa-circle-o"></i>Enquiry Report</a></li>
            <li><a href="{{route('activity.report')}}"><i class="fa fa-circle-o"></i>Activity Report</a></li>
          <li><a href="{{route('freebie.report')}}"><i class="fa fa-circle-o"></i>Freebie Report</a></li>
          <li><a href="{{route('performance.report')}}"><i class="fa fa-circle-o"></i>Performence Report</a></li>
          <li><a href="{{route('collection.followup.report')}}"><i class="fa fa-circle-o"></i>Collection Followup Report</a></li>
          @endif


      </ul>
    </li>
    @endif
  @endif
  @if(Auth::user()->userpermission(17) || Auth::user()->userpermission(18))
    <li class="treeview">
      <a href="#">
        <i class="fa fa-dashboard"></i> <span>Complaint Managment</span>
        <span class="pull-right-container">
        <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        @if(Auth::user()->userpermission(17))
        <li><a href="{{route('complaint.index')}}"><i class="fa fa-circle-o"></i>Complaints</a></li>
        @endif
        @if(Auth::user()->userpermission(18))
        <li><a href="{{route('complaint.report')}}"><i class="fa fa-circle-o"></i>Complaint Report</a></li>
        @endif
      </ul>
    </li>
  @endif
  {{-- Support Ticket Management -- Administrator (Application Developer) --}}
  @if(Auth::user()->isSupportAdmin())
    <li class="treeview {{ request()->is('admin/support*') ? 'active' : '' }}">
      <a href="#">
        <i class="fa fa-headphones text-aqua"></i> <span>Support (Developer)</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li class="{{ request()->routeIs('admin.support.dashboard') ? 'active' : '' }}">
          <a href="{{ route('admin.support.dashboard') }}"><i class="fa fa-circle-o"></i> Support Dashboard</a>
        </li>
        <li class="{{ request()->routeIs('admin.support.tickets.index') ? 'active' : '' }}">
          <a href="{{ route('admin.support.tickets.index') }}"><i class="fa fa-circle-o"></i> All Tickets</a>
        </li>
        <li class="{{ request()->routeIs('admin.support.categories.index') ? 'active' : '' }}">
          <a href="{{ route('admin.support.categories.index') }}"><i class="fa fa-circle-o"></i> Ticket Categories</a>
        </li>
        <li class="{{ request()->routeIs('admin.support.reports') ? 'active' : '' }}">
          <a href="{{ route('admin.support.reports') }}"><i class="fa fa-circle-o"></i> Reports</a>
        </li>
      </ul>
    </li>
  @endif

  {{-- Support Ticket Management -- Client User (Office Scoped) --}}
  @if(Auth::user()->office_id > 0 || !Auth::user()->isSupportAdmin())
    <li class="treeview {{ (request()->is('support*') && !request()->is('admin/support*')) ? 'active' : '' }}">
      <a href="#">
        <i class="fa fa-life-ring text-yellow"></i> <span>Support</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li class="{{ request()->routeIs('support.dashboard') ? 'active' : '' }}">
          <a href="{{ route('support.dashboard') }}"><i class="fa fa-circle-o"></i> Support Dashboard</a>
        </li>
        <li class="{{ request()->routeIs('support.tickets.create') ? 'active' : '' }}">
          <a href="{{ route('support.tickets.create') }}"><i class="fa fa-circle-o"></i> Create New Ticket</a>
        </li>
        <li class="{{ request()->routeIs('support.tickets.index') ? 'active' : '' }}">
          <a href="{{ route('support.tickets.index') }}"><i class="fa fa-circle-o"></i> All Tickets</a>
        </li>
      </ul>
    </li>
  @endif
    @if(1 == 2)
    <li class="treeview">
      <a href="#">
        <i class="fa fa-dashboard"></i> <span>####</span>
        <span class="pull-right-container">
        <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li><a href=""><i class="fa fa-circle-o"></i> $$$$$$$$$$$$$</a></li>
      </ul>
    </li>
    @endif
  </ul>
</section>
</aside>
