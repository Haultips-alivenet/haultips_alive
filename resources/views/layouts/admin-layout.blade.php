<!--
Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE HTML>
<html>
    <head>
        <title>@yield('title')</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <!-- Bootstrap Core CSS -->
        {!! Html::style('public/admin/css/bootstrap.min.css') !!}        
        <!-- Custom CSS -->
        {!! Html::style('public/admin/css/style.css') !!}
        <!-- Graph CSS -->
        {!! Html::style('public/admin/css/font-awesome.css') !!}
        <!-- jQuery -->
        <!-- lined-icons -->
        {!! Html::style('public/admin/css/icon-font.min.css') !!}
        <!-- //lined-icons -->
        <!-- chart -->
        {!! HTML::script('public/admin/js/Chart.js')!!}      
        <!-- //chart -->
        <!--animate-->
        {!! Html::style('public/admin/css/animate.css') !!}
        {!! HTML::script('public/admin/js/wow.min.js')!!}   
                <script>
                         new WOW().init();
                </script>
        <!--//end-animate-->


         <!-- Meters graphs -->
         {!! HTML::script('public/admin/js/jquery-1.10.2.min.js')!!}   
        <!-- Placed js at the end of the document so the pages load faster -->  
</head> 
<body class="sticky-header left-side-collapsed"  onload="initMap()">
    <section>
    <!-- left side start-->
        <div class="left-side sticky-left-side">

            <!--logo and iconic logo start-->
            <div class="logo">
                <h1><a href="{{ url('admin/dashboard') }}">Haultips<span>Admin</span></a></h1>
            </div>
            <div class="logo-icon text-center">
                    <a href="{{ url('admin/dashboard') }}"><i class="lnr lnr-home"></i> </a>
            </div>

                <!--logo and iconic logo end-->
            <div class="left-side-inner">
                <!--sidebar nav start-->
                <ul class="nav nav-pills nav-stacked custom-nav">
                    <li class="active">
                        <a href="{{ url('admin/dashboard') }}"><i class="lnr lnr-power-switch"></i><span>Dashboard</span></a>
                    </li>
                    <li class="menu-list">
                        <a href="#"><i class="lnr lnr-cog"></i>
                        <span>User</span></a>
                        <ul class="sub-menu-list">
                            <li><a href="{{ url('admin/newuser') }}">New User</a> </li>
                            <li><a href="{{ url('admin/userList') }}">User List</a></li>
                        </ul>
                    </li>
                    <li class="menu-list">
                            <a href="#"><i class="lnr lnr-cog"></i>
                                    <span>Components</span></a>
                                    <ul class="sub-menu-list">
                                            <li><a href="grids.html">Grids</a> </li>
                                            <li><a href="widgets.html">Widgets</a></li>
                                    </ul>
                    </li>
                        <li><a href="forms.html"><i class="lnr lnr-spell-check"></i> <span>Forms</span></a></li>
                        <li><a href="tables.html"><i class="lnr lnr-menu"></i> <span>Tables</span></a></li>              
                        <li class="menu-list"><a href="#"><i class="lnr lnr-envelope"></i> <span>MailBox</span></a>
                                <ul class="sub-menu-list">
                                        <li><a href="inbox.html">Inbox</a> </li>
                                        <li><a href="compose-mail.html">Compose Mail</a></li>
                                </ul>
                        </li>      
                        <li class="menu-list"><a href="#"><i class="lnr lnr-indent-increase"></i> <span>Menu Levels</span></a>  
                                <ul class="sub-menu-list">
                                        <li><a href="charts.html">Basic Charts</a> </li>
                                </ul>
                        </li>
                        <li><a href="codes.html"><i class="lnr lnr-pencil"></i> <span>Typography</span></a></li>
                        <li><a href="media.html"><i class="lnr lnr-select"></i> <span>Media Css</span></a></li>
                        <li class="menu-list"><a href="#"><i class="lnr lnr-book"></i>  <span>Pages</span></a> 
                                <ul class="sub-menu-list">
                                        <li><a href="sign-in.html">Sign In</a> </li>
                                        <li><a href="sign-up.html">Sign Up</a></li>
                                        <li><a href="blank_page.html">Blank Page</a></li>
                                </ul>
                        </li>
                </ul>
                    <!--sidebar nav end-->
            </div>
        </div>
        <!-- left side end-->
        <!-- main content start-->
        <div class="main-content">
                <!-- header-starts -->
                <div class="header-section">

                <!--toggle button start-->
                <a class="toggle-btn  menu-collapsed"><i class="fa fa-bars"></i></a>
                <!--toggle button end-->

                <!--notification menu start -->
                <div class="menu-right">
                    <div class="user-panel-top">  	
                        <div class="profile_details_left">
                            <ul class="nofitications-dropdown">
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-envelope"></i><span class="badge">3</span></a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <div class="notification_header">
                                                    <h3>You have 3 new messages</h3>
                                            </div>
                                        </li>
                                        <li><a href="#">
                                           <div class="user_img"><img src="images/1.png" alt=""></div>
                                           <div class="notification_desc">
                                                <p>Lorem ipsum dolor sit amet</p>
                                                <p><span>1 hour ago</span></p>
                                            </div>
                                           <div class="clearfix"></div>	
                                         </a></li>
                                        <li class="odd"><a href="#">
                                            <div class="user_img"><img src="images/1.png" alt=""></div>
                                            <div class="notification_desc">
                                                 <p>Lorem ipsum dolor sit amet </p>
                                                 <p><span>1 hour ago</span></p>
                                                 </div>
                                           <div class="clearfix"></div>	
                                        </a></li>
                                        <li><a href="#">
                                           <div class="user_img"><img src="images/1.png" alt=""></div>
                                           <div class="notification_desc">
                                                <p>Lorem ipsum dolor sit amet </p>
                                                <p><span>1 hour ago</span></p>
                                                </div>
                                           <div class="clearfix"></div>	
                                        </a></li>
                                        <li>
                                                <div class="notification_bottom">
                                                        <a href="#">See all messages</a>
                                                </div> 
                                        </li>
                                    </ul>
                                </li>
                                <li class="login_box" id="loginContainer">
                                    <div class="search-box">
                                        <div id="sb-search" class="sb-search">
                                            <form>
                                                <input class="sb-search-input" placeholder="Enter your search term..." type="search" id="search">
                                                <input class="sb-search-submit" type="submit" value="">
                                                <span class="sb-icon-search"> </span>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- search-scripts -->
                                    <script src="js/classie.js"></script>
                                    <script src="js/uisearch.js"></script>
                                    <script>
                                            new UISearch( document.getElementById( 'sb-search' ) );
                                    </script>
                                    <!-- //search-scripts -->
                                </li>
                                <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-bell"></i><span class="badge blue">3</span></a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <div class="notification_header">
                                                <h3>You have 3 new notification</h3>
                                            </div>
                                        </li>
                                        <li><a href="#">
                                            <div class="user_img"><img src="images/1.png" alt=""></div>
                                           <div class="notification_desc">
                                                <p>Lorem ipsum dolor sit amet</p>
                                                <p><span>1 hour ago</span></p>
                                                </div>
                                          <div class="clearfix"></div>	
                                         </a></li>
                                        <li class="odd"><a href="#">
                                            <div class="user_img"><img src="images/1.png" alt=""></div>
                                            <div class="notification_desc">
                                               <p>Lorem ipsum dolor sit amet </p>
                                               <p><span>1 hour ago</span></p>
                                             </div>
                                          <div class="clearfix"></div>	
                                        </a></li>
                                        <li><a href="#">
                                            <div class="user_img"><img src="images/1.png" alt=""></div>
                                            <div class="notification_desc">
                                               <p>Lorem ipsum dolor sit amet </p>
                                               <p><span>1 hour ago</span></p>
                                            </div>
                                          <div class="clearfix"></div>	
                                        </a></li>
                                        <li>
                                            <div class="notification_bottom">
                                                    <a href="#">See all notification</a>
                                            </div> 
                                       </li>
                                    </ul>
                                </li>	
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-tasks"></i><span class="badge blue1">22</span></a>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <div class="notification_header">
                                                        <h3>You have 8 pending task</h3>
                                                </div>
                                            </li>
                                            <li><a href="#">
                                                <div class="task-info">
                                                    <span class="task-desc">Database update</span><span class="percentage">40%</span>
                                                    <div class="clearfix"></div>	
                                                </div>
                                                <div class="progress progress-striped active">
                                                    <div class="bar yellow" style="width:40%;"></div>
                                                </div>
                                            </a></li>
                                            <li><a href="#">
                                                <div class="task-info">
                                                    <span class="task-desc">Dashboard done</span><span class="percentage">90%</span>
                                                    <div class="clearfix"></div>	
                                                </div>
                                                <div class="progress progress-striped active">
                                                    <div class="bar green" style="width:90%;"></div>
                                                </div>
                                            </a></li>
                                            <li><a href="#">
                                                <div class="task-info">
                                                    <span class="task-desc">Mobile App</span><span class="percentage">33%</span>
                                                    <div class="clearfix"></div>	
                                                </div>
                                               <div class="progress progress-striped active">
                                                    <div class="bar red" style="width: 33%;"></div>
                                               </div>
                                            </a></li>
                                            <li><a href="#">
                                                <div class="task-info">
                                                    <span class="task-desc">Issues fixed</span><span class="percentage">80%</span>
                                                   <div class="clearfix"></div>	
                                                </div>
                                                <div class="progress progress-striped active">
                                                    <div class="bar  blue" style="width: 80%;"></div>
                                                </div>
                                            </a></li>
                                            <li>
                                                <div class="notification_bottom">
                                                    <a href="#">See all pending task</a>
                                                </div> 
                                            </li>
                                        </ul>
                                    </li>		   							   		
                                    <div class="clearfix"></div>	
                                </ul>
                            </div>
                            <div class="profile_details">		
                                <ul>
                                    <li class="dropdown profile_details_drop">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <div class="profile_img">	
                                            <span style="background:url(images/1.jpg) no-repeat center"> </span> 
                                             <div class="user-name">
                                                 
                                                    <p>{{$firstName}}<span>{{$lastName}}</span></p>
                                             </div>
                                             <i class="lnr lnr-chevron-down"></i>
                                             <i class="lnr lnr-chevron-up"></i>
                                            <div class="clearfix"></div>	
                                        </div>	
                                        </a>
                                        <ul class="dropdown-menu drp-mnu">
                                            <li> <a href="#"><i class="fa fa-cog"></i> Settings</a> </li> 
                                            <li> <a href="#"><i class="fa fa-user"></i>Profile</a> </li> 
                                            <li> <a href="{{ url('auth/logout') }}"><i class="fa fa-sign-out"></i> Logout</a> </li>
                                        </ul>
                                    </li>
                                    <div class="clearfix"> </div>
                                </ul>
                            </div>		

                            <div class="clearfix"></div>
                    </div>
                  </div>
                <!--notification menu end -->
                </div>
        <!-- //header-ends -->
 @yield('body')
 
 </div>
    <!--footer section start-->
        <footer>
           <p>Copyright © 2015 S4 Shipment Technologies Pvt. Ltd. | All Rights Reserved <a href="JavaScript:void(0);" target="_blank">Haultips.com</a></p>
        </footer>
    <!--footer section end-->

  <!-- main content end-->
   </section>
 
    {!! HTML::script('public/admin/js/jquery.nicescroll.js')!!} 
    {!! HTML::script('public/admin/js/scripts.js')!!}  
    {!! HTML::script('public/admin/js/bootstrap.min.js')!!}
 </body>
</html>