<!--
Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE HTML>
<html>
    <head>
        <title>Admin Panel | Haultips</title>
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
        <style type="text/css">
            html, body, body section, #page-wrapper{height: 100%;}

        </style>
</head> 
   
 <body class="sign-in-up">
    <section>
        <div id="page-wrapper" class="sign-in-wrapper">
            <div class="graphs _graphs login_table">
                <div class="sign-in-form">
                <div class="inner_login">
                    <div class="sign-in-form-top">
                            <p><a href="#">Sign In to  Admin</a></p>
                    </div>
                    <div class="signin">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li> {{$error}} </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                        {!! Form::open(array('url'=>'/auth/login','id'=>'login-form')) !!}
                            <div class="log-input">
                                <div class="log-input-left">
                                    {!! Form :: text('email','',['placeholder'=>'Yourname','class'=>'user','id'=>'user']) !!}
                                 </div>
                                <div class="clearfix"> </div>
                            </div>

                            <div class="log-input">
                                <div class="log-input-left">
                                    {!! Form::password('password', array('placeholder'=>'password', 'class'=>'lock')) !!}
                                </div>
                                <div class="clearfix"> </div>
                            </div>
                            
                        <div class="checkbox icheck">
                            <label style="padding: 0;">
                                <input name="remember" value="1" type="checkbox"> <span>Remember Me</span>
                            </label>
                        </div>
                        {!! Form::submit('Login') !!}
                        <div class="clearfix"></div>
                    {!!Form::close()!!}
                        
                    <div class="signin-rit">
                        <!-- <span class="checkbox1">
                            <label class="checkbox"><input type="checkbox" name="checkbox" checked="">Forgot Password ?</label>
                        </span> -->
                        <div class="clearfix"> </div>
                    </div> 
                    </div>
                </div>
                </div>
            </div>
        </div>
        <!--footer section start-->
        <footer>
           <p>Copyright © 2015 S4 Shipment Technologies Pvt. Ltd. | All Rights Reserved <a href="javascript:void(0);" target="_blank">Haultips.com</a></p>
        </footer>
        <!--footer section end-->
	</section>
	
    {!! HTML::script('public/admin/js/jquery.nicescroll.js')!!} 
    {!! HTML::script('public/admin/js/scripts.js')!!}  
    {!! HTML::script('public/admin/js/bootstrap.min.js')!!}
</body>
</html>