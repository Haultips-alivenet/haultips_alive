@extends('layouts.user-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')


 {!! Form::open(array('url'=>'twowheeler','class'=>'form-horizontal','id'=>'vehicle_form','files' => true)) !!}
<section class="categories containerfirstphase">
    <div class="container">
        
        <div class="row">
            
            <div class="col-md-6">                 
                <div class="clearfix"></div>
                <div class="_cate_up_img">
                <img src="img/img_upload_icon.png" id="thumbnil" style="width:45%; margin-top:45;"  alt="" class="wow pulse">
                <div class="clearfix"></div>
                    <div class="_ul_d">
                    Upload Images
                        <input type="file"  onchange="showMyImage(this)" class="_btn_f" name="vehicle_image" id="vehicle_image" title="Upload images">
                        <div id='errAttchment' style='display:none;color:red;'>Image should be jpg or png</div>
                <div id='errAttchment1' style='display:none;color:red;'>Please Select Image</div>
                    </div>
                
                </div>
                 
                <div class="_cate_up_dv">
                    <h2>Vehicle information </h2>
                       <div class="form-group row">
                            <div class="col-md-5">
                                <label for="">Delivery Title</label>
                                </div>
                                <div class="col-md-7">
                            <input type="text" onkeyup="delivery_titlehide();" name="delivery_title" id="delivery_title" class="form-control">
                            <div id='errname' style='display:none;color:red;'>Please Enter delivery title</div>
                            </div>
                            
                       </div>  
                       
                       <div class="form-group row">
                       <div class="col-md-5">
                            <label for="">Vehicle Name</label>
                            </div>
                            <div class="col-md-7">
                            <input type="text" onkeyup="vehicle_namehide();" name="vehicle_name" id="vehicle_name" class="form-control">
                            <div id='errvehicle' style='display:none;color:red;'>Please Enter Vehicle Name</div>
                            </div>
                       </div>     
                       <div class="form-group row">
                            <div class="col-md-5">
                                <label for="">Vehicle Model</label>
                                </div>
                                <div class="col-md-7">
                            <input type="text" name="vehicle_model" class="form-control">
                            <input type="hidden" name="subcategory_id" value="{{$subcategory_id}}">
                            </div>
                       </div>
                       <div class="form-group row">
                        <div class="col-md-5">
                            <label for="">Vehicle Model color</label>
                        </div>
                            <div class="col-md-7">
                            <input type="text" name="vehicle_color" class="form-control">
                            </div>
                       </div>
                    </div>
                </div>
             
            </div>
            <div class="clearfix"></div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <div class="_add_cmt_bx">
<h4>Additional information (Optional) </h4>
                        <p>Additional information:</p>
                        <div class="form-group">
                            <textarea name="additional_detail" id="additional_detail" cols="55" rows="5"  class="form-control"></textarea>
                            <small>You have 1200 characters left</small>
                        </div>
                    </div>

                    <div class="_add_btn_btm">
                        <a href="{{ url('subCategory/3') }}" class="btn btn-border">Back</a>
                       
                        <button type="button" class="btn btn-color" onclick="validate_office('next')">Continue</button>
                        <div class="inputbox-mandat-loading-1" style="display:none"><img src="{{ asset('public/admin/images/ajax-loader.gif')}}" alt=""></div>    
                    </div>

                </div>
                <div class="col-md-6">
                <i class="fa fa-question pull-left wow bounceIn" aria-hidden="true"></i>
                    <div class="_info_txt">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis 
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

              <textarea name="pickupaddress" id="pickupaddress" cols="30" rows="10" class="form-control _fm_c_t"></textarea>


              </div>
              </div>
                <div class="form-group row">
                <div class="col-md-12">
              <label for="">Delivery Address</label>

              <textarea name="deliveryaddress" id="deliveryaddress" cols="30" rows="10" class="form-control _fm_c_t"></textarea>

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
  function showMyImage(fileInput) {
      $('#errAttchment1').hide();
       $('#errAttchment').hide();
        var files = fileInput.files;
        for (var i = 0; i < files.length; i++) {           
            var file = files[i];
            var imageType = /image.*/;     
            if (!file.type.match(imageType)) {
                continue;
            }           
            var img=document.getElementById("thumbnil");            
            img.file = file;    
            var reader = new FileReader();
            reader.onload = (function(aImg) { 
                return function(e) { 
                    aImg.src = e.target.result; 
                }; 
            })(img);
            reader.readAsDataURL(file);
        }    
    }
 
function validate_office(action_type){  
    
     var title = $('#delivery_title').val();
     var image = $('#vehicle_image').val();
     var vehicle_name = $('#vehicle_name').val();
     var c=0;
     
        if(title==''){
            $('html, body').animate({ scrollTop: 100 }, 1000);
            $('#errname').show();
                c = c+1;
            }else{
                $('#errname').hide();
            }
         if(image==''){
            $('html, body').animate({ scrollTop: 100 }, 1000);
            $('#errAttchment1').show();
                c = c+1;
            }else{
                $('#errAttchment1').hide();
            }
        if(image!=''){
                var fileInput = $('#vehicle_image')[0]; 
                var ext = fileInput.files[0].type;     
                if($.inArray(ext, ['image/jpg','image/jpeg','image/png']) == -1){
                    $('html, body').animate({ scrollTop: 450 }, 1000);
                    $('#errAttchment').show();
                    c = c+1;
                }else{
                    $('#errAttchment').hide();
                } 
        }
        if(vehicle_name==''){
            $('html, body').animate({ scrollTop: 100 }, 1000);
            $('#errvehicle').show();
                c = c+1;
            }else{
                $('#errvehicle').hide();
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
function vehicle_namehide(){
    $('#errvehicle').hide(); 
}
</script>
@endsection

