 <!-- [ navigation menu ] start -->
 <nav class="pcoded-navbar menupos-fixed menu-light brand-blue ">
        <div class="navbar-wrapper ">
            <div class="navbar-brand header-logo nav_border">
                <a href="{{route('dashboard')}}" class="b-brand">
                    <img src="{{asset('assets/images/logo_new.png')}}" alt="" class="logo header_logo images ">

                    <img src="{{asset('assets/images/logo-icon.svg')}}" alt="" class="logo-thumb images">
                </a>
                <!-- <a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a> -->
            </div>
            <div class="navbar-content scroll-div">
                <ul class="nav pcoded-inner-navbar">
                    @if(Auth::user()->role=="Admin" || Auth::user()->role=="Manager")
                        <li class="nav-item pcoded-hasmenu">
                            <a href="{{route('dashboard')}}" class="nav-link"><span class="pcoded-micon"><img class="nav-icons nav-icons-new" src="{{asset('assets/images/Dashboard_icon.png')}}"> <span class="nav_tooltip" data-toggle="tooltip" data-placement="right" title="Dashboard"><img class="nav-icons" src="{{asset('assets/images/Dashboard_icon.png')}}"></span></span><span class="pcoded-mtext">Dashboard</span></a>
                        </li>
                    @endif
                        @if(Auth::user()->role=="Admin" || Auth::user()->role=="Manager")
                            <li class="nav-item pcoded-hasmenu">
                                <a href="{{route('dashboard.projects')}}" class="nav-link"><span class="pcoded-micon"><img class="nav-icons nav-icons-new" src="{{asset('assets/images/periods_icon.png')}}"><span class="nav_tooltip" data-toggle="tooltip" data-placement="right" title="Periods"><img class="nav-icons" src="{{asset('assets/images/periods_icon.png')}}"></span></span><span class="pcoded-mtext">Projects</span></a>
                            </li>
                        @endif
                    @if(Auth::user()->role=="Admin" || Auth::user()->role=="Manager")
                        <li class="nav-item pcoded-hasmenu">
                            <a href="{{route('dashboard.periods')}}" class="nav-link"><span class="pcoded-micon"><img class="nav-icons nav-icons-new" src="{{asset('assets/images/periods_icon.png')}}"><span class="nav_tooltip" data-toggle="tooltip" data-placement="right" title="Periods"><img class="nav-icons" src="{{asset('assets/images/periods_icon.png')}}"></span></span><span class="pcoded-mtext">Periods</span></a>
                        </li>
                    @endif
                    @if(Auth::user()->role=="Admin" || Auth::user()->role=="Manager")
                        <li class="nav-item pcoded-hasmenu">
                            <a href="{{route('dashboard.users')}}" class="nav-link"><span class="pcoded-micon"><img class="nav-icons nav-icons-new" src="{{asset('assets/images/Users_icon.png')}}"> <span class="nav_tooltip" data-toggle="tooltip" data-placement="right" title="Users"><img class="nav-icons" src="{{asset('assets/images/Users_icon.png')}}"></span></span><span class="pcoded-mtext">Users</span></a>
                        </li>
                    @endif
                    @if(Auth::user()->role=="Admin" || Auth::user()->role=="Manager" || Auth::user()->role=="User")
                        <li class="nav-item pcoded-hasmenu">
                            <a href="{{route('dashboard.forms')}}" class="nav-link"><span class="pcoded-micon"> <img class="nav-icons nav-icons-new" src="{{asset('assets/images/Forms_icon.png')}}"> <span class="nav_tooltip" data-toggle="tooltip" data-placement="right" title="Stream"><img class="nav-icons" src="{{asset('assets/images/Forms_icon.png')}}"></span></span><span class="pcoded-mtext">Streams</span></a>
                        </li>
                    @endif
                    @if(Auth::user()->role=="Admin" || Auth::user()->role=="Manager")
                        <li class="nav-item pcoded-hasmenu">
                            <a  href="{{route('dashboard.reports')}}" class="nav-link"><span class="pcoded-micon"> <img class="nav-icons nav-icons-new" src="{{asset('assets/images/reports_icon.png')}}"> <span class="nav_tooltip" data-toggle="tooltip" data-placement="right" title="Reports"><img class="nav-icons" src="{{asset('assets/images/reports_icon.png')}}"></span></span><span class="pcoded-mtext">Reports</span></a>
                        </li>
                    @endif
                    @if(Auth::user()->role=="Admin" || Auth::user()->role=="Manager")
                        <li class="nav-item pcoded-hasmenu" >
                            <a href="{{route('dashboard.permissions', [0])}}"  class="nav-link"><span class="pcoded-micon">   <img class="nav-icons nav-icons-new" src="{{asset('assets/images/permission.png')}}"> <span class="nav_tooltip" data-toggle="tooltip" data-placement="right" title="Permissions"><img class="nav-icons" src="{{asset('assets/images/permission.png')}}"> </span></span>  <span class="pcoded-mtext"> Permissions </span></a>
                        </li>
                    @endif
                    <!-- <li class="nav-item pcoded-hasmenu">
                    <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="feather icon-box"></i></span><span class="pcoded-mtext">Componant</span></a>
                    <ul class="pcoded-submenu">
                        <li class=""><a href="bc_button.html" class="">Button</a></li>
                        <li class=""><a href="bc_badges.html" class="">Badges</a></li>
                        <li class=""><a href="bc_breadcrumb-pagination.html" class="">Breadcrumb & paggination</a></li>
                        <li class=""><a href="bc_collapse.html" class="">Collapse</a></li>
                        <li class=""><a href="bc_progress.html" class="">Progress</a></li>
                        <li class=""><a href="bc_tabs.html" class="">Tabs & pills</a></li>
                        <li class=""><a href="bc_typography.html" class="">Typography</a></li>
                    </ul>
                </li> -->

                </ul>
            </div>
        </div>
    </nav>
    <!-- [ navigation menu ] end -->
