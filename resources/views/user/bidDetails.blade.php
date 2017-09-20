@extends('layouts.user-layout')
@section('title')
    Admin Panel | Haultips
@endsection

@section('body')

<?php //print_r($diliveries); ?>

<section class="main_signup _login_bg min_bids">
    <div class="container">
        <div class="row">
            <div class="col-lg-offset-3 col-md-offset-3  col-lg-6 col-md-6">
                <div class="snup_bx _min_in wow zoomIn">
                    <h2 class="text-center">Min Bid Price</h2>
                   <div class="clearfix"></div>
                    <div class="_cus_bx _cus_login">
                        <div class="_price_m">
                          <i class="fa fa-inr" aria-hidden="true"></i>
 <span> {{($miid->minimumBid == '') ? '0' : $miid->minimumBid}}</span>
                        </div>    

                       <p>Enter a lower amount to automatically underbid other
Service providers .Learn More</p>
                       {!! Form::open(array('url'=>'bid/offer/save','id'=>'bid_form','method'=>'post')) !!}
                        
                        <div class="form-group">
                        <div class="input-group">
                        <div class="input-group-addon" style="background: transparent; border: none;"><i class="fa fa-inr" aria-hidden="true" style="font-size: 30px;"></i></div>
                            <input type="text" name="bidvalue" class="form-control" placeholder="Enter your quotation">
                            <input type="hidden" name="minbid" value="{{($miid->minimumBid == '') ? '0' : $miid->minimumBid}}">
                            <input type="hidden" name="shipingid" value="{{$shiping_id}}">
                        </div>
                        </div>
                        
                         
                        <div class="form-group">
                            <div class="col-md-12">
                            
                        <div class="checkbox checkbox-circle">
                        <input type="checkbox" class="filled-in" id="checkbox2">
                        <!--<label for="checkbox2">Subtract haultips fee to calculate the total bid as seen by 
the customer</label>--->
                        </div>
                            </div>
                            
                        </div>

                        <br>
                     
                        <div class="form-group text-center">
                           
                            {!! Form::submit('Submit Offer',['class'=>"btn btn-color"]) !!}
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

<script>
    $('#bid_form').validate({
            rules: {
                bidvalue:{
                    required : true,
                    number: true
                    
                }
               
            },

            messages: {
                
                bidvalue :{
                    required : "Enter your Bids"
                   
                }
            }
            
           

        });
</script>    
@endsection