@extends('layouts.user-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')


 {!! Form::open(array('url'=>'others','class'=>'form-horizontal','id'=>'vehicle_form','files' => true)) !!}



<section class="_inner_pages containerfirstphase">
    <div class="container">
        <div class="row">
        <div class="_inner_bx">
            <div class="col-md-12">
                
                    <h2>Others</h2>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-6">
                <div class="form-group _fgrp">
                    <label for="" style="font-weight: normal; display: block;">Delivery Title</label>
                       <input type="text" onkeyup="delivery_titlehide();" name="delivery_title" id="delivery_title" class="form-control">
                       <div id='errname' style='display:none;color:red;'>Please Enter delivery title</div>
                    </div>
                </div>
                <div class="col-md-9">
                
                <ul class="img_up_list" id="img_up_list">
                    
                </ul>
                <div id='errAttchment' style='display:none'>Image should be jpg or png</div>
                <div class="clearfix"></div>

                <div class="form-group _fl_upd">
                    <label for="">Upload pictures:</label>
                    <div id="imagePreview"></div>
                    <span> <input id="uploadFile" type="file"  name="image[]" multiple="" class="_fl_f" />
                    Add Photo
                    </span>
                    {!! Form::hidden('imageCount', '', array('id' => 'imageCount')) !!}
                     <input type="hidden" name="subcategory_id" value="{{@$subcategory_id}}">
                </div>
            </div>  <div class="gallery"></div>
                <div class="col-md-6">
<div class="clearfix"></div>
                    <div class="_inner_ads_d">
                        <h4>Additional Details </h4>
                        

                       
                           
                        <div class="_inner_d_txt">
                          <textarea name="additional_detail" id="additional_detail" cols="30" rows="10" class="form-control"></textarea>
                           <small>You have 1200 characters left</small>
                        </div>
                         <div class="_new_inner_d_txt">
                        </div>
                        <div class="clearfix"></div>
                        <br>
                         <div class="_add_btn_btm">
                        <button class="btn btn-border">Back</button>
                         <button type="button" class="btn btn-color" onclick="validate_part('next')">Continue</button>
                    </div>
                    </div>   
                </div>
                <div class="col-md-6">
                <div class="_inner_ads_d">
                    <div class="_tips_one">
                        <i class="fa fa-question pull-left wow bounceIn" aria-hidden="true"></i>
                    <div class="_ads_txt_d">
                       <b> TIP:</b> Listings with pictures get 63% more bids than listings without pictures!You can add an image now or do it later from your dashboard.
                    </div> 
                    </div> 

                    
                    <div class="_tips_two">
                        <i class="fa fa-question pull-left wow bounceIn" aria-hidden="true"></i>
                    <div class="_ads_txt_d _dd">
                    <b>IMPORTANT:</b> Do not include your contact information here. For your security, your contact information will be exchanged only after you book a delivery with your chosen Service Provider
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="_inner_pages containersecondphase" style="display:none">
    <div class="container">
        <div class="row">
        <div class="_inner_bx">
      
            <div class="col-md-9">
            <h2>Pickup Details</h2>                     
                </div> 


                <div class="col-md-9" style="margin-top: 25px;">
                      
                     <div class="form-group row">
                        <div class="col-md-6">
                        <label for="">Pickup Date</label>
                           <div class="row">
                               <div class="col-md-12">
                                   
                                <div class='input-group date datetimepicker6' >
                               <input type="text" name="pickupdate" id="pickupdate" class="form-control datetimepicker6 ">
                                   <span class="input-group-addon" style="background: transparent; border-radius: 0;" ><img src="{{ asset('public/user/img/date-time-icon.png')}}" alt=""></span>
                               </div>

                               </div>
                               
                           </div>
                        </div>

                        <div class="col-md-6">
                        <label for="">Delivery Date</label>
                        <div class="row">
                               <div class="col-md-12">
                               <div class='input-group date datetimepicker7' >
                               <input type="text" name="deliverydate" class="form-control datetimepicker7">
                                   <span class="input-group-addon" style="background: transparent; border-radius: 0;"><img src="{{ asset('public/user/img/date-time-icon.png')}}" alt=""></span>
                               </div>
                               
                               </div>
                              
                           </div>
                        </div>
                       </div>
<div class="clearfix"></div>

               <div class="form-group row">
              <div class="col-md-12">
              <label for="">Pickup Address</label>

              <textarea name="pickupaddress" id="" cols="30" rows="10" class="form-control _fm_c_t"></textarea>


              </div>
              </div>
                <div class="form-group row">
                <div class="col-md-12">
              <label for="">Delivery Address</label>

              <textarea name="deliveryaddress" id="" cols="30" rows="10" class="form-control _fm_c_t"></textarea>

              </div>
              </div>
            <div class="form-group">
            
             {!! Form :: submit("Submit",["class"=>"btn btn-color",'id'=>'']) !!}
            </div>
               </div> 
                    
                </div> 
                <div class="clearfix"></div>
                
    </div>
</section>
 {!! Form::close() !!}
@endsection
@section('script')
<script>
    $('#vehicle_form').validate({

            rules: {
                pickupdate:{
                    required : true,
                },
                deliverydate:{
                    //required : true,
                    
                },
                pickupaddress:{
                    required : true,
                    
                },
                deliveryaddress:{
                    required : true,
                    
                }
                
                
            },

            messages: {
                
                pickupdate :{
                    required : "Select Pickup Date"
                   
                },
                deliverydate :{
                    //required : "Select Delivery Date"
                   
                },
                pickupaddress :{
                    required : "Enter Pickup Address"
                   
                },
                deliveryaddress :{
                    required : "Enter Delivery Address"
                   
                }
            }
            
        });
 
 $(function() {
    var imageCount = 1;
    // Multiple images preview in browser
    var imagesPreview = function(input, placeToInsertImagePreview) {

        if (input.files) {
            var filesAmount = input.files.length;
            
            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function(event) {
                    $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                    $('img').wrap('<li />');
                   
                }
           
                reader.readAsDataURL(input.files[i]);
                
            }
        }

    };

    $('#uploadFile').on('change', function() {
        $('#img_up_list').html('');
         imagesPreview(this, 'ul.img_up_list'); 
          var imageValue = imageCount++; 
        $('#imageCount').val(imageValue);
        });
});
function validate_part(action_type){  
    
     var title = $('#delivery_title').val();
     var weight = $('#weight').val();
     var material_id = $('#material_id').val();
     //var vehicle_name = $('#vehicle_name').val();
     var c=0;
     
        if(title==''){
            $('html, body').animate({ scrollTop: 100 }, 1000);
            $('#errname').show();
                c = c+1;
            }else{
                $('#errname').hide();
            }
        if(weight==''){
            $('html, body').animate({ scrollTop: 100 }, 1000);
            $('#errweight').show();
                c = c+1;
            }else{
                $('#errweight').hide();
            }
        if(material_id==''){
            $('html, body').animate({ scrollTop: 100 }, 1000);
            $('#errmaterial').show();
                c = c+1;
            }else{
                $('#errmaterial').hide();
            }    
        
        if(c>0){//alert('action_type');
                return false;
                
        }else{
                $('.inputbox-mandat-loading-1').css({ display: "block" });
                quicksetupprenext(action_type);
        }
}

function quicksetupprenext(action_type){
        
        if(action_type == 'next'){
            $(".containerfirstphase").slideUp(200);
            $(".containerfirstphase").hide();
            $(".containersecondphase").slideDown(200);
            $(".containersecondphase").show();
        }else if(action_type == 'pre'){
            $(".containersecondphase").slideUp(200);
            $(".containersecondphase").hide();
            $(".containerfirstphase").slideDown(200)
            $(".containerfirstphase").show();
        }else{
            
        }
        
}
function delivery_titlehide(){
    $('#errname').hide();
}
function weightide(){
    $('#errweight').hide(); 
}
</script>
 <script>
   $('.carousel').carousel();
   $('#menu-toggle').click(function(){
   $('#sidebar-wrapper').toggle();
   });
   new WOW().init();

$cols = 1;
    function fun_plus(){   
   if($cols<=4){
     $('._inner_d_txt').first().clone().attr('id','boxes'+$cols).appendTo('._new_inner_d_txt'); 
$cols++;
   }
 }
function fun_minus(){
    if($cols>=2){
    $('._inner_d_txt').last().remove();
    $cols--;
}
 }
</script>
@endsection

