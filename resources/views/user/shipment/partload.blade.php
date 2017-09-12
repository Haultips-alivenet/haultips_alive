@extends('layouts.user-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')


 {!! Form::open(array('url'=>'partload','class'=>'form-horizontal','id'=>'vehicle_form','files' => true)) !!}

<section class="categories containerfirstphase">
    <div class="container">
        <div class="row">
        <div class="col-md-12">
            <h2>Part Load</h2>
        </div>
            
    </div>
            <div class="clearfix"></div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group _fgrp">
                    <label for="" style="font-weight: normal; display: block;">Delivery Title</label>
                       <input type="text" onkeyup="delivery_titlehide();" name="delivery_title" id="delivery_title" class="form-control">
                       <div id='errname' style='display:none;color:red;'>Please Enter delivery title</div>
                    </div>
                <div class="form-group _fgrp">
                    <label for="" style="font-weight: normal; display: block;">Weight</label>
                   <input type="text" onkeyup="weightide();" name="weight" id="weight" class="form-control">
                   <div id='errweight' style='display:none;color:red;'>Please Enter Weight</div>
                    </div>


                    

<br><br>
                <div class="clearfix"></div>

                    


                <div class="clearfix"></div>


                </div>
                <div class="col-md-6">
                <i class="fa fa-question pull-left wow bounceIn" aria-hidden="true"></i>
                    <div class="_info_txt">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis 
                    </div>  

                    <div class="clearfix"></div> 
                        

                </div>
                
            </div>
           <div class="row">
                <div class="col-md-12">
                    <h2>Choose Material</h2>

                    <ul class="choose_m">
                        <?php foreach($materials as $value) { ?>
                        <li>
                        <a href="#" matid="{{$value->id}}" ><span class="_icon_m"><img src="{{asset('public/user/img/'.$value->image)}}" alt=""></span><span>{{$value->name}}</span></a>
                        </li>
                        <?php } ?>
                       
                    </ul>
                    <input type="hidden" name="material_id" id="material_id">
                    <div id='errmaterial' style='display:none;color:red;'>Please Select Material</div>
                </div>
            </div>
            <div class="clearfix"></div>
            <br>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    
        <div class="_add_cmt_bx">
<h4>Additional information (Optional) </h4>
                        <p>Additional information:</p>
                        <div class="form-group">
                            <textarea cols="55" rows="5" id="additional_detail" name="additional_detail" class="form-control"></textarea>
                            <small>You have 1200 characters left</small>
                        </div>
                    </div>

                    <div class="_add_btn_btm">
                        <button class="btn btn-border">Back</button>
                         <button type="button" class="btn btn-color" onclick="validate_part('next')">Continue</button>
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

   $(".choose_m a").click(function(){
       $('#errmaterial').hide();
     var a=$(this).attr("matid");
     $('#material_id').val(a);
  $(this).parent().addClass("selected").siblings().removeClass("selected"); 
});
</script>
@endsection

