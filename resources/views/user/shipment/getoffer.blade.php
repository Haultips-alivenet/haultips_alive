@extends('layouts.user-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')


 
<section class="main_signup _login_bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-offset-2 col-md-offset-2  col-lg-8 col-md-8">
                <div class="snup_bx _login wow zoomIn">
                    <h2>Get Offer</h2>
                     @if(Session::has('success'))
                            <div class="alert alert-success">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                {{Session::get('success')}}
                            </div>
                        @endif
                        
                    {{--    Error Display ends--}}
                   <div class="clearfix"></div>
                    <div class="_cus_bx _get_offer">
                        
                        


<!--                        <div class="_es_time">
                                <i class="fa fa-inr" aria-hidden="true"></i>
 500.00
                            
                        </div>
                        <span>Estimation price</span>-->


                        <div class="form-group text-center">
                            <a href="{{URL :: asset("user/getofferprocess")}}" class="wow  bounce"> <button class="btn btn-color" id="">Get Offer</button></a>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')

@endsection

