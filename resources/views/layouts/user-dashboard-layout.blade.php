<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    

     <title>@yield('title')</title>
    <meta name="description" content="HAULTIPS, A leading Delhi based transport company offers online truck booking and transportation services in Delhi. Call 9871120400 to book a truck online now." />
    <meta name="keywords" content="online truck booking, online truck booking services, haultips, book a truck online" />

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:200,300,400" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:300,400" rel="stylesheet">

   
     <!-- Bootstrap core CSS -->
        {!! Html::style('public/user/css/bootstrap.min.css') !!}
        {!! Html::style('public/user/css/bootstrap-datetimepicker.css') !!}         
        <!-- Custom CSS -->
        {!! Html::style('public/user/css/animate.css') !!}
        {!! Html::style('public/user/css/style.css') !!}
        {!! HTML::script('public/admin/js/jquery-1.10.2.min.js')!!}   

   
  </head>
<body>
   <!-- Navigation -->
   <header>
    <a  href="#" class="btn btn-dark btn-lg"><i class="fa fa-bars"></i></a>
    <nav id="sidebar-wrapper">
        <ul class="sidebar-nav">
            <a id="menu-close" href="#" class="btn btn-light btn-lg pull-right toggle"><i class="fa fa-times"></i></a>
            
            <li class="sidebar-brand"><a href="#top">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#services">Support</a></li>
            <li><a href="#portfolio">Deliveries</a></li>
            <li><a href="#contact">New Shipment</a></li>
            <li><a href="#contact">How it Works</a></li>
            <li><a href="#contact">Blog</a></li>
        </ul>
    </nav> 

<nav class="navbar navbar-default navbar-fixed-top topnav nav-theme" role="navigation">
        <div class="container-fluid topnav">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand topnav" href="{{url('user/home')}}" title="HAULTIPS"><img src="{{asset('public/user/img/logo.png')}}" alt="HAULTIPS"></a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-left">
                    <li>
                        <a href="#about">How to works</a>
                    </li>
                    <li>
                        <a href="#services">Deliveries</a>
                    </li>
                    
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <?php $tempArr = Session::get('currentUser'); ?>
                    <li class="pro_im">
                        <a href="#"><span><img src="{{asset('public/user/img/sign-up-icon.png')}}" alt=""></span>{{$tempArr["first_name"]." ".$tempArr["last_name"]}}</a>
                    </li>
                    <li>
                        <a href="JavaScript:void(0);" id="menu-toggle" class="toggle" ><img src="{{asset('public/user/img/nav-menu.png')}}"></a>
                    </li>
                    
                </ul>

            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
    </header>


<section class="main_signup _login_bg ">
    <div class="container">
        <div class="row">
           <div class="col-md-4">
            <div class="_dash_lft _dash_m">
                <ul>
                   <li><a href="{{url('user/home')}}"><span><i class="fa fa-tachometer"></i></span>Dashboard</a></li>
                   <li class="toggle"><a href="javascript:void();" onclick="toggle(1);">
                       <span><i class="fa fa-check-square" aria-hidden="true"></i></span>My Deliveries <span class="pull-right"><i class="fa fa-angle-down" aria-hidden="true"></i></span></a>
                        <ul class="ulist1">
                            <li><a href="{{ url('user/my-deliveries/all-status') }}">All Status</a></li>
                            <li><a href="{{ url('user/my-deliveries/active') }}">Active</a></li>
                            <li><a href="{{ url('user/my-deliveries/delivered') }}">Delivered</a></li>
                            <li><a href="{{ url('user/my-deliveries/deleted') }}">Deleted</a></li>
                        </ul>

                    </li>
                    <li><a href="{{ url('user/bank-infomation') }}"><span><i class="fa fa-university" aria-hidden="true"></i></span>Bank Information</a></li>
                    <li><a href="{{url('user/transactionhistory')}}"><span><i class="fa fa-credit-card" aria-hidden="true"></i></span>Transaction History</a></li>
                    <li class="toggle"><a href="javascript:void();" onclick="toggle(2);"><span><i class="fa fa-cog" aria-hidden="true"></i></span>Setting <span class="pull-right"><i class="fa fa-angle-down" aria-hidden="true"></i></span></a>
                        <ul class="ulist2">
                            <li><a href="{{ url('user/profile') }}">Profile</a></li>
                            <li><a href="{{url('user/changepassword')}}">Change Password</a></li>
                        </ul>

                    </li>
                    <li><a href="{{url('user/faq')}}"><span><i class="fa fa-info-circle" aria-hidden="true"></i></span>Faq</a></li>
                    <li><a href="{{url('auth/logout')}}"><span><i class="fa fa-power-off" aria-hidden="true"></i></span>Logout</a></li>
                </ul>  
            </div>   
          </div>
            @yield('body')
            
      </div>
    </div>
</section>

 <!--footer section start-->
       <footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4">
                <div class="footer_bx">
                    <h3>Transporters</h3>
                    <div class="clearfix"></div>
                    <ul>
                        <li><span><img src="{{asset('public/user/img/arrow.png')}}" alt=""></span>Transportation Services In Faridabad</li>
                        <li><span><img src="{{asset('public/user/img/arrow.png')}}" alt=""></span>Transportation Services In Gurgaon </li>
                        <li><span><img src="{{asset('public/user/img/arrow.png')}}" alt=""></span>Transportation Services In Ghaziabad </li>
                        <li><span><img src="{{asset('public/user/img/arrow.png')}}" alt=""></span>Transportation Services In Delhi </li>
                        <li><span><img src="{{asset('public/user/img/arrow.png')}}" alt=""></span>Transportation Services In Noida </li>
                        <li><span><img src="{{asset('public/user/img/arrow.png')}}" alt=""></span>Packers and Movers In Delhi
                        <li><span><img src="{{asset('public/user/img/arrow.png')}}" alt=""></span>Packers and Movers in Gurgaon </li>
                        <li><span><img src="{{asset('public/user/img/arrow.png')}}" alt=""></span>Packers and Movers In Noida </li>
                        <li><span><img src="{{asset('public/user/img/arrow.png')}}" alt=""></span>packers and Movers In Faridabad </li>
                        <li><span><img src="{{asset('public/user/img/arrow.png')}}" alt=""></span>Logistic services in Delhi NCR </li>
                   </ul>
                </div>

            </div>
            <div class="col-lg-4 col-md-4 no-padding">
                <div class="footer_bx three">
                    <h3>Quick Links</h3>
                     <div class="clearfix"></div>
                     <ul>

                        <li><span><img src="{{asset('public/user/img/arrow.png')}}" alt=""></span>Citywise Packers</li>
                        <li><span><img src="{{asset('public/user/img/arrow.png')}}" alt=""></span>Privacy Policy</li>
                        <li><span><img src="{{asset('public/user/img/arrow.png')}}" alt=""></span>Terms & Conditions</li>
                        <li><span><img src="{{asset('public/user/img/arrow.png')}}" alt=""></span>Sitemap</li>
                        <li><span><img src="{{asset('public/user/img/arrow.png')}}" alt=""></span>Email Login</li>
                        <li><span><img src="{{asset('public/user/img/arrow.png')}}" alt=""></span>Blog</li>
                        <li><span><img src="{{asset('public/user/img/arrow.png')}}" alt=""></span>Support</li>
                        <li><span><img src="{{asset('public/user/img/arrow.png')}}" alt=""></span>Carrier</li>
                        <li><span><img src="{{asset('public/user/img/arrow.png')}}" alt=""></span>Faq</li>
                        <li><span><img src="{{asset('public/user/img/arrow.png')}}" alt=""></span>About Us</li>
                        <li><span><img src="{{asset('public/user/img/arrow.png')}}" alt=""></span>Testimonials</li>
                        <li><span><img src="{{asset('public/user/img/arrow.png')}}" alt=""></span>Our Services</li>
                        <li><span><img src="{{asset('public/user/img/arrow.png')}}" alt=""></span>Projects</li>
                        <li><span><img src="{{asset('public/user/img/arrow.png')}}" alt=""></span>Careers</li>
                        <li><span><img src="{{asset('public/user/img/arrow.png')}}" alt=""></span>Contact Us</li>

                     </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="footer_bx">
                    <h3>Find Us</h3>
                     <div class="clearfix"></div>

                    <div class="foot-link">
              

<address class="address"><i class="fa fa-home"></i> S-11,
21 site-2 industrial area loni road, Mohan Nagar , Ghaziabad, UP ,
India - 201007</address>
<address><i class="fa fa-phone"></i> +91-9971664765</address>
<address><i class="fa fa-envelope"></i> info@haultips.com</address>


<div class="clearfix"></div>
<div class="foot-link">
<h3>Subscribe Now</h3>
    <div class="input-group foot-grp">
      <input type="text" class="form-control" placeholder="Search for...">
      <span class="input-group-btn">
        <button class="btn" type="button"><i class="fa fa-send-o"></i></button>
      </span>
    </div>
</div>

<h3>Follow Us </h3>
  <ul class="social-link">
          <li><a href="#"><i class="fa fa-facebook"></i></a></li>
          <li><a href="#"><i class="fa fa-twitter"></i></a></li>
          <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
          <li><a href="#"><i class="fa fa-youtube-play"></i></a></li>
        </ul>

                
            </div>


                </div>
            </div>
        </div>
    </div>
</footer>
<div class="inner_footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <p>© 2017 Haultips. All rights reserved.</p>
            </div>
        </div>
    </div>
</div>

</body>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    {!! Html::script('public/user/js/bootstrap.min.js') !!}
     {!! Html::script('public/admin/js/jquery.validate.min.js') !!}
     {!! Html::script('public/user/js/wow_main.js') !!}
     {!! Html::script('public/user/js/custome.js') !!}
     {!! Html::script('public/user/js/bootstrap-slider.js') !!}
     {!! Html::script('public/admin/js/additional-methods.min.js') !!}
     {!! Html::script('public/admin/js/jquery.nicescroll.js') !!}
     {!! Html::script('public/user/js/moment.js') !!}
     {!! Html::script('public/user/js/bootstrap-datetimepicker.js') !!}
     {!! Html::script('https://maps.googleapis.com/maps/api/js?key=AIzaSyDr-iGaiLmD2zCzCvl11pPRKgeFHxY8b7I&libraries=places&sensor=false&region=India') !!}
     <!-- {!! Html::script('public/admin/js/bootstrap-select.min.js') !!} -->

    @yield('script')
    <script>
       $('.carousel').carousel();
       $('#menu-toggle').click(function(){
           $('#sidebar-wrapper').toggle();
        });
        new WOW().init();
    </script>
  </body>
</html>