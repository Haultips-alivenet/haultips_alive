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
 @yield('body')
 
    {!! HTML::script('public/admin/js/jquery.nicescroll.js')!!} 
    {!! HTML::script('public/admin/js/scripts.js')!!}  
    {!! HTML::script('public/admin/js/bootstrap.min.js')!!}
 </body>
</html>