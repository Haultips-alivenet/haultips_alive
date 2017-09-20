@extends('layouts.user-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')


 
<section class="main_signup _login_bg min_bids">
    <div class="container">
        <div class="row">
            <div class="col-lg-offset-3 col-md-offset-3  col-lg-6 col-md-6">
                <div class="snup_bx _min_in wow zoomIn">
                    <h2 class="text-center">Verify Mobile</h2>
                   <div class="clearfix"></div>
                    <div class="_cus_bx _cus_login">
                        @if(Session::has('success'))
                            <div class="alert alert-success">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                {{Session::get('success')}}
                            </div>
                        @endif   

                      
                     {!! Form::open(array('url'=>'user/checkotp','id'=>'menu','method'=>'post')) !!}
                        
                        <div class="form-group">
                        <div class="input-group">
                        <div class="input-group-addon" style="background: transparent; border: none;">OTP</div>
                            <input type="text" class="form-control" name="userotp" id="userotp" placeholder="Enter your Otp">
                             <input type="hidden" class="" name="userid" id="userid" value="{{$userid}}">
                        </div>
                        </div>
                        
                     
                           <br> 
                        <div class="form-group text-center">
                            
                             {!! Form::submit('Submit',['class'=>"btn btn-color"]) !!}
                            <a href="{{url('user/resend/opt/'.$userid)}}">Resend Otp?</a>
                        </div>  
                           {!! Form::close() !!}
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

