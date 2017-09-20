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
<small class="pull-right"><a onclick="window.history.go(-1);" style="cursor: pointer;">Back to Search Results</a></small>
        <div class="goodes_details">

            <div class="col-md-3 border-right no-padding">
                <div class="_g_d_img">
                    <?php $img=explode(",",$detailsItem["itemImage"]);?>
                    <img src="{{asset('public/uploads/userimages/'.$img[0])}}" alt="" style="height:150px;weight:240px;">
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
                            <li><strong>Bid/Offer Expire Date:</strong></li>
                            <li>{{$details["pickupDate"]}} </li>
                            <li><strong>Item Expire Date :</strong></li>
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
            <h3><span>Motorcycles :</span> Pulsar 150 </h3>
            <div class="clearfix"></div>
 <?php foreach($detailsItem as $key=>$value) { if($key!="itemImage" && $key!="additionalDetail") { ?>
            <div class="col-md-12">
               
              <ul class="go_info_list">
                  <div class="col-md-8">
                <li><strong>{{strtoupper($key)}} </strong></li>
                </div>
                <div class="col-md-4">
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
        <br>
        <div class="_ad_info_faq">
            <h3>Ask the Question and Answer</h3>
            <small>There are currently no questions about this shipment </small>



            <ul>
                <li><strong>Lorem ipsum dolor sit amet, consectetur adipisicing elit,</strong></li>
                <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commod consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non roident, sunt in culpa qui officia deserunt mollit anim id est laborum.</li>

                <li><strong>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore</strong></li>
                <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commod consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non roident, sunt in culpa qui officia deserunt mollit anim id est laborum.</li>

                <li><strong>Lorem ipsum dolor sit amet, consectetur adipisicing elit,</strong></li>
                <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commod consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non roident, sunt in culpa qui officia deserunt mollit anim id est laborum.</li>

                <li><strong>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor</strong></li>
                <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commod consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non roident, sunt in culpa qui officia deserunt mollit anim id est laborum.</li>
            </ul>
            <div class="clearfix"></div>
            <a href="#">View All</a>
        </div>
        <div class="clearfix"></div>
   
    </div>
       </div>
    </div>
</section>


<section class="_make_btn">
    <div class="container">
        <div class="row">
             <button class="btn btn-color">Make Offer Now</button>
        </div>
    </div>
</section>
@endsection
@section('script')



