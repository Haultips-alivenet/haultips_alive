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
                    
                    <div class="_cus_bx _get_offer _thankdiv ">
                    <div class="of_m_rbn">
                        <img src="img/bow_PNG10112.png" alt="">
                    </div>
                        
                      <h3> Thank you !</h3>

                            Your Email has been verified.
                           <br><br>
                            <p>Whishing you a lifetime of smiles, joy and happiness</p>
                           <p>we hope you enjoy this
Haultips .</p>

                            <br><a href="{{ url('/') }}" class="btn btn-color">Go On Home Page</a>



                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')

@endsection

