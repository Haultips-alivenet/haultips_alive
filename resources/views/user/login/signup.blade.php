@extends('layouts.user-layout')
@section('title')
    Admin Panel | Haultips
@endsection

@section('body')


<section class="main_signup">
    <div class="container">
        <div class="row">
            <div class="col-lg-push-3 col-md-push-3 col-lg-6">
                <div class="snup_bx wow zoomIn">
                    <h2>Sign up</h2>
                   <div class="clearfix"></div>
                   <div class="_sinup_bx">
                        <p>I am a ...</p>

                        <a href="{{url('user/customer')}}"><span><img src="{{asset('public/user/img/customer_img.png')}}" alt=""></span> Customer</a>
                        <a href="{{url('user/partner')}}"><span><img src="{{asset('public/user/img/partner_img.png')}}" alt=""></span> Partner</a>

                   </div>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection


