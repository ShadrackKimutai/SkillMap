<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Scripts -->
    
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style4.css')}}">




  </head>
  <body style="margin: 0;height: 100%;padding: 0">
     <div class="wrapper">
            <!-- Sidebar Holder -->
            <nav id="sidebar">
                <div class="sidebar-header">
                    <h3>Skillmap</h3>
                    <strong>Contractor Listing Service</strong>
                </div>

                <ul class="list-unstyled components">
                    <li class="active">
                        <a href="{{ url('/') }}" >
                            <i class="fa fa-home"></i>
                            Home
                        </a>
                        
                    </li>
                     <li class="active">
                        <a href="{{ url('/entrepreneurs') }}" >
                            <i class="fa fa-users"></i> Entrepreneurs
                        </a>
                        
                    </li>
                   @guest
                         <li><a href="{{ url('/login') }}">
                            <i class="fa fa-user"></i>
                           Login
                        </a></li>
                        <li><a href="{{ url('/register') }}">
                            <i class="fa fa-plus"></i>
                           Register
                        </a></li>
                        @else
                        <li>
<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();" data-toggle="collapse" aria-expanded="false">
   <i class="fa fa-user"></i>
    Logout
</a>    
<form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
</form>

                       
                    </li>
                    <li>
                         <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">My Pages</a>
                    <ul class="collapse list-unstyled" id="pageSubmenu">
                        <li>
                              <a href="{{ url('/profile') }}">
                            <i class="fa fa-address-book"></i>
                            My Profile
                        </a>
                        </li>
                        <li>
                            <a href="{{ url('/edit-profile') }}"> <i class="fa fa-edit"></i> Edit Profile</a>
                        </li>
                        
                    </ul>
                    
                      </li>
                       @endguest
                      <li>
                        <!--a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false">
                            <i class="fa fa-search"></i>
                            Search Skill
                        </a>
                        <ul class="collapse list-unstyled" id="pageSubmenu">
                        <form action="/search" method="POST" role="search">
                            {{ csrf_field() }}
                          <div class="input-group">
                            <input type="text" class="form-control" name="q" placeholder="Search users"> <span class="input-group-btn">
            <button type="submit" class="btn btn-default"><span class="fa fa-search"></span></button></span>
                  </div>
</form>
                        </ul>
                    </li-->
                   
                </ul>

                <ul class="list-unstyled CTAs">
                    <li><a href="" class="download">Developed by Ruth Kiplagat<br> RVTTI</a></li>
              
                </ul>
            </nav>

            <!-- Page Content Holder -->
            


                            <button type="button" id="sidebarCollapse" class="navbar-btn">
                                <span></span>
                                <span></span>
                                <span></span>
                            </button>

            @yield('content')

    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>


    <!-- Bootstrap JS -->
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <!-- JQuery -->
 <script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="//maps.googleapis.com/maps/api/js"></script>
  </script>
    <script type="text/javascript">
        $(document).ready(function () {$('#sidebarCollapse').on('click', function () {$('#sidebar').toggleClass('active');});});
    </script>
</body>


</html>
