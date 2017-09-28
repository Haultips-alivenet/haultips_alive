@extends('layouts.user-layout')
@section('title')
    Admin Panel | Haultips
@endsection

@section('body')

<?php error_reporting(0); ?>

<section class="categories cate_div">
    <div class="container">
      <div class="row">
          <div class="col-md-12">
              <h2>Using haultips to Book Loads is Easy!</h2>
               <br>
                          @if(Session::has('success'))
                            <div class="alert alert-success">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                {{Session::get('success')}}
                            </div>
                        @endif
          </div>

          <div class="clearfix"></div>
          <div class="col-md-3">
             <div class="_bx_box">
             <h4>Step 1 </h4>
                 <strong>Join for FREE </strong> & find listings 
like the one below to bid on.
             </div> 


          </div>
          <div class="col-md-3">
             <div class="_bx_box">
              <h4>Step 2 </h4>
                 <strong>Ask questions</strong>, work out details, 
and make bids on the listings.
             </div> 


          </div>
          <div class="col-md-3">
             <div class="_bx_box">
              <h4>Step 3 </h4>
                 Pick up the shipment and make 
the delivery in order to <strong>complete 
the transaction and get paid.</strong>
             </div> 


          </div>
          <div class="col-md-3">
              
             <div class="_bx_box">
              <h4>Step 4 </h4>
                <strong>Shippers leave you feedback</strong>, 
which helps you book more loads. 
             </div>

          </div>
      </div> 
      <a href="#">Already reviewed this listing and ready to make a bid?</a> 
    </div>
<div class="clearfix"></div>
<br>
<br>
       <div class="container">
       
            <div class="row">
<small class="pull-right custom-back-arrow"><i class="fa fa-arrow-left" aria-hidden="true"></i>  <a onclick="window.history.go(-1);" style="cursor: pointer;">Back to Search Results</a></small>
        <div class="goodes_details">

            <div class="col-md-3 border-right no-padding">
                <div class="_g_d_img">
                    <?php $img=explode(",",$detailsItem["itemImage"]); if($img[0]) { ?>
                    <img src="{{asset('public/uploads/userimages/'.$img[0])}}" alt="" style="height:150px;weight:240px;">
                    <?php } else { ?>
                    <img src="{{asset('public/user/img/not-available.jpg')}}" alt="" style="height:150px;weight:240px;">
                    <?php } ?>
                    <div class="_g_d_d">
                       {{$detailsItem["deliveryTitle"]}}
                    </div>  
                </div>
            </div>
            <div class="col-md-9 no-padding">

               <div class="_g_d_txt">
                   <h2>Listing Information</h2>
                        
                    <div class="col-md-6">
                        <ul class="_g_d_list">
                            <li><strong>Delivery Title:</strong></li>
                            <li>{{$detailsItem["deliveryTitle"]}}</li>
                            <li><strong>Delivery ID:</strong></li>
                            <li> {{$details["order_id"]}}</li>
                            <li><strong>Customer:</strong></li>
                            <li> {{$details["userName"]}}</li>
                        </ul>
                    </div>

                <div class="col-md-6">
                        <ul class="_g_d_list">
                            <li><strong>Publish Date:</strong></li>
                            <li>{{$details["publishDate"]}}</li>
                            <li><strong>Bid/Offer Pickup Date:</strong></li>
                            <li>{{$details["pickupDate"]}} </li>
                            <li><strong>Item Expiry Date :</strong></li>
                            <li> {{$details["expireDate"]}} </li>
                        </ul>
                    </div>

               </div> 
            </div>
        </div>
        </div>


        <div class="row">
            <div class="_cash_d">
                <ul>
                    <li><img src="{{asset('public/user/img/_pay_cash.png')}}" alt=""><strong>Payment type :-</strong> </li>
                    <li><strong>{{$details["payment_method"]}}</strong></li>
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="_des_ro_info">
                <div class="col-md-7 no-padding">
                   <div class="_mp_ro_info">
                        <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3499.721162949348!2d77.32893381482512!3d28.697986532391937!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390cfbb4bd33eefb%3A0x4be413f674f238a2!2sBhopura%2C+Ghaziabad%2C+Uttar+Pradesh+201005!5e0!3m2!1sen!2sin!4v1466579597948"
                                width="100%" height="100%" frameborder="0" style="border:0" allowfullscreen></iframe>
                   </div> 
                </div>
                <div class="col-md-5 no-padding">
                   <div class="_ds_info_rou">
                       <h4>Destination, & Route Information</h4>
                       <div class="form-group">
                           <h4>Collection</h4>
                           <div class="media">
                               <div class="media-body">
                                   <div class="media-left">
                                       <img src="{{asset('public/user/img/location_01.png')}}" alt="">
                                   </div>
                                   <div class="media-right">
                                       <p>{{$details["pickupAddress"]}}</p>
                                       
                                   </div>
                               </div>
                           </div>
                       </div>
                       <div class="form-group">
                           <h4>Route</h4>
                           <div class="media">
                               <div class="media-body">
                                   <div class="media-left">
                                       
                                   </div>
                                   <div class="media-right">
                                       <p>Common Route</p>
                                        <p>Est. Distance: {{$details["distance"]}} </p>
                                   </div>
                               </div>
                           </div>
                       </div>

                       <div class="form-group">
                           <h4>Delivery</h4>
                           <div class="media">
                               <div class="media-body">
                                   <div class="media-left">
                                       <img src="{{asset('public/user/img/location_02.png')}}" alt="">
                                   </div>
                                   <div class="media-right">
                                       <p>{{$details["deliveryAddress"]}}</p>
                                     
                                   </div>
                               </div>
                           </div>
                       </div>

                   </div> 
                </div>
            </div>
        </div>
    <div class="row">
        <div class="goods_info">
            <h3><span>{{$detailsItem["deliveryTitle"]}} :</span> </h3>
            <div class="clearfix"></div>
 <?php foreach($detailsItem as $key=>$value) { if($key!="itemImage" && $key!="additionalDetail") { ?>
            <div class="col-md-6">
               
              <ul class="go_info_list">
                  <div class="col-md-6">
                <li><strong>{{strtoupper($key)}} </strong></li>
                </div>
                <div class="col-md-6">
                <li>{{$value}}</li>
                </div>
                
              </ul> 
               
            </div>
 <?php } } ?>
           <br>
            <div class="_go_info_bm">
                <a href="#">* Approx. weight and dimensions based on vehicle body type</a>
            <br>
            <small>Accepted Service Types : Enclosed Transport</small>
            </div> 
        </div>
    </div>
    <div class="row">
        <div class="_ad_info">
            <h3>Additional Information</h3>
            <p>{{$detailsItem["additionalDetail"]}}</p>
        </div>
        <br><div class="clearfix"></div>
        
        <div class="clearfix"></div>
   
    </div>
           
           <div class="row">
               {!! Form::open(array('url'=>'partner/question/save','id'=>'bid_form','method'=>'post')) !!}
        <div class="goods_info">
            <h3>Ask the Question</h3>
            
 
            <div class="col-md-12">
                 <textarea name="question" placeholder="Write Question" class="form-control"></textarea>
                 <input type="hidden" name="ship_id" value="{{$shiping_id}}">
            </div><div class="col-md-12"><br></div>
            <div class="col-md-12">
                <div class="col-md-4">
                {!! Form::submit('Submit',['class'=>"btn btn-color"]) !!}
                </div>
            </div><div class="col-md-12"><br></div>
 <div class="clearfix"></div>
        </div>
               {!! Form::close() !!}
    </div>
           
       </div>
    </div>
</section>

@if(!$count_bid)
<section class="_make_btn">
    <div class="container">
        <div class="row">
             <a href="{{url('bid/offer/'.$shiping_id)}}" class="btn btn-color">Make Offer Now</a>
        </div>
    </div>
</section>
@endif

@endsection

@section('script')



