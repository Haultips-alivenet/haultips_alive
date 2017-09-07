<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    

    <title>@yield('title')</title>
    <meta name="description" content="HAULTIPS, A leading Delhi based transport company offers online truck booking and transportation services in Delhi. Call 9871120400 to book a truck online now." />
    <meta name="keywords" content="online truck booking, online truck booking services, haultips, book a truck online" />

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:200,300,400" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:300,400" rel="stylesheet">

    <!-- Bootstrap core CSS -->
         {!! Html::style('public/user/css/bootstrap.min.css') !!}        
        <!-- Custom CSS -->
        {!! Html::style('public/user/css/animate.css') !!}
        {!! Html::style('public/user/css/style.css') !!}
        {!! Html::style('public/user/css/bootstrap-select.min.css') !!}
        {!! HTML::script('public/admin/js/jquery-1.10.2.min.js')!!} 
       
        <!-- Graph CSS -->
        {!! Html::style('public/admin/css/font-awesome.css') !!}
        <!-- jQuery -->
        <!-- lined-icons -->
        {!! Html::style('public/admin/css/icon-font.min.css') !!}
        <!-- //lined-icons -->
        <!-- chart -->
        {!! HTML::script('public/admin/js/Chart.js')!!}      
        <!-- //chart -->
        {!! HTML::script('public/admin/js/wow.min.js')!!}   
          <script>
            new WOW().init();
           </script>
  </head>
<body>
   
   <!-- Navigation -->
   <header>
    <a  href="#" class="btn btn-dark btn-lg"><i class="fa fa-bars"></i></a>
    <nav id="sidebar-wrapper">
        <ul class="sidebar-nav">
            <a id="menu-close" href="#" class="btn btn-light btn-lg pull-right toggle"><i class="fa fa-times"></i></a>
            
            <li class="sidebar-brand"><a href="#top">Home1</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#services">Support</a></li>
            <li><a href="{{url('user/find/deliveries')}}">Deliveries</a></li>
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
                <a class="navbar-brand topnav" href="{{url('/')}}" title="HAULTIPS"><img src="{{asset('public/user/img/logo.png')}}" alt="HAULTIPS"></a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-left">
                    <li>
                        <a href="#about">How to works</a>
                    </li>
                    <li>
                        <a href="{{url('user/find/deliveries')}}">Deliveries</a>
                    </li>
                    
                </ul>
               
            <ul class="nav navbar-nav navbar-right">
                 <?php  $tempArr = Session::get('currentUser');
                 if($tempArr["first_name"]!="") {
                 ?>
                     <li>
                       <a href="#"> <span><img src="{{asset('public/user/img/sign-up-icon.png')}}" alt=""></span>{{$tempArr["first_name"]." ".$tempArr["last_name"]}}</a>
                    </li>
                    <li>
                        <a href="{{url('auth/logout')}}"><span><img src="{{asset('public/user/img/log-out-icon.png')}}" alt=""></span>Log out</a>
                    </li>
                 <?php } else { ?>
                    <li>
                        <a href="{{url('user/login')}}"><span><img src="{{asset('public/user/img/login-icon.png')}}" alt=""></span>Log in</a>
                    </li>
                    <li>
                        <a href="{{url('user/signup')}}"><span><img src="{{asset('public/user/img/sign-up-icon.png')}}" alt=""></span>Sign up</a>
                    </li>
                 <?php } ?>
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
    @yield('body')
 
 </div>
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
   <!-- {!! Html::script('public/user/js/jquery.min.js') !!} -->
    <!-- <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script> -->
    
     {!! Html::script('public/user/js/bootstrap.min.js') !!}
     {!! Html::script('public/admin/js/jquery.validate.min.js') !!}
     {!! Html::script('public/user/js/wow_main.js') !!}
     {!! Html::script('public/user/js/custome.js') !!}
     {!! Html::script('public/user/js/bootstrap-slider.js') !!}
     {!! Html::script('public/user/js/bootstrap-select.min.js') !!}
     {!! Html::script('public/admin/js/additional-methods.min.js') !!}
     {!! Html::script('public/admin/js/jquery.nicescroll.js') !!}
    @yield('script')
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script>
   $('.carousel').carousel();
   $('#menu-toggle').click(function(){
    
   $('#sidebar-wrapper').toggle();
   });
   new WOW().init();
     $("#ex2").slider({});
     
     
</script>
  </body>
</html>



