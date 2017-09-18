@extends('layouts.user-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')


 {!! Form::open(array('url'=>'home','class'=>'form-horizontal','id'=>'home_form','files' => true)) !!}

<style>
    .panel-body{
        display: none; 
    }
</style>
<?php //print_r($dinningRoom); ?>
<section class="_inner_pages containerfirstphase">
    <div class="container">
        <div class="row">
            <div class="_inner_bx">
                <h2>Home</h2>
                <h4>Haultips For Carriers</h4>
                <br>
                <br>
                <div class="clearfix"></div>
                
                <div class="col-md-8">
                    
                    <div class="select_opt">
                        <div class="row">
                            <div class="col-md-6">
                                 <div class="form-group">
                                <label for="">Delivery Title</label>
                                <input type="hidden" name="_token"  value="{{ csrf_token() }}">
                                <input type="text" onkeyup="delivery_titlehide();" name="title" value=""  id="title" class="form-control">
                             </div>
                             <div id='errname' style='display:none;color:red;'>Please Enter Delivery Title</div>
                            </div>
                           
                            <div class="clearfix"></div>
                            <br>

                        </div>

                    </div>

                </div>
                
                <div class="col-md-8">
                    
                    <div class="select_opt">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Type of residence</label>
                                    <select name="residenceType" id="residenceType" class="selectpicker">
                                        <option value="" selected="">Collection floor</option>
                                        <option value="House">House</option>
                                        <option value="Apartment">Flat/Apartment</option>
                                        <option value="Condo">Condo</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Number of Bedroom</label>
                                    <select name="no_of_room" id="no_of_room" class="selectpicker">
                                        <option value="" selected="">Collection floor</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                    </select>

                                </div>

                            </div>
                            <div class="clearfix"></div>
                            <br>

                        </div>

                    </div>

                </div>

                <div class="row">
                    <div class="col-md-12">
                        <h4><i>Get the best bids by filling out the information below.</i></h4>
                        <h5>Delivery Title</h5>
                        <div class="clearfix"></div>
                        <p>(e.g..3 Large Boxes) This will become thi title of your listing, so be as descriptive as possible.You do not have to include collection or delivery information here..</p>

                        <div class="panel _pnl">
                            <div class="panel-heading panelheding">

                                <span class="pull-left clickable"><i class="glyphicon glyphicon-plus"></i>Dining Room(s): (Optional)</span>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                     <ul>
                                    <?php foreach($dinningRoom as $dinning){ ?>
                                    
                                        <li>
                                            <div class="ch_bx">
                                                <label for="name">{{$dinning->name}}</label>
                                                <input type="text" name="dinning[]" value="0" id="dinning{{$dinning->id}}">
                                                <div class="_btn_act">
                                                    <a href="JavaScript:void(0);" class="a_sign" onclick="increaseValue('dinning<?php echo  $dinning->id ?>')" id="add{{$dinning->id}}">+</a>
                                                    <a href="JavaScript:void(0);" class="a_sign" onclick="decreaseValue('dinning<?php echo  $dinning->id ?>')" id="minus{{$dinning->id}}">-</a>
                                                </div>
                                            </div>
                                        </li>
                                         
                                     <?php } ?>
                                        </ul>
                                </div>

                            </div>
                        </div>

                        <div class="panel _pnl">
                            <div class="panel-heading panelheding">

                                <span class="pull-left clickable"><i class="glyphicon glyphicon-plus"></i>Living Room(s):(Optional)</span>
                            </div>
                            <div class="panel-body">

                                <ul>
                                     <?php foreach($livingRoom as $value){ ?>
                                    
                                        <li>
                                            <div class="ch_bx">
                                                <label for="name">{{$value->name}}</label>
                                                <input type="text" name="living[]" value="0" id="living{{$value->id}}">
                                                <div class="_btn_act">
                                                    <a href="JavaScript:void(0);" class="a_sign" onclick="increaseValue('living<?php echo  $value->id ?>')" id="add{{$value->id}}">+</a>
                                                    <a href="JavaScript:void(0);" class="a_sign" onclick="decreaseValue('living<?php echo  $value->id ?>')" id="minus{{$value->id}}">-</a>
                                                </div>
                                            </div>
                                        </li>
                                         
                                     <?php } ?>
                                   
                                </ul>

                            </div>
                        </div>

                        <div class="panel _pnl">
                            <div class="panel-heading panelheding">

                                <span class="pull-left clickable"><i class="glyphicon glyphicon-plus"></i>Bedroom(s):(Optional)</span>
                            </div>
                            <div class="panel-body">

                                <ul>
                                   <?php foreach($bedRooms as $value){ ?>
                                    
                                        <li>
                                            <div class="ch_bx">
                                                <label for="name">{{$value->name}}</label>
                                                <input type="text" name="bedrooms[]" value="0" id="bedrooms{{$value->id}}">
                                                <div class="_btn_act">
                                                    <a href="JavaScript:void(0);" class="a_sign" onclick="increaseValue('bedrooms<?php echo  $value->id ?>')" id="add{{$value->id}}">+</a>
                                                    <a href="JavaScript:void(0);" class="a_sign" onclick="decreaseValue('bedrooms<?php echo  $value->id ?>')" id="minus{{$value->id}}">-</a>
                                                </div>
                                            </div>
                                        </li>
                                         
                                     <?php } ?>
                                </ul>

                            </div>
                        </div>

                        <div class="panel _pnl">
                            <div class="panel-heading panelheding">

                                <span class="pull-left clickable"><i class="glyphicon glyphicon-plus"></i>Kitchen:(Optional)</span>
                            </div>
                            <div class="panel-body">
                               <ul>
                                   <?php foreach($kitchen as $value){ ?>
                                    
                                        <li>
                                            <div class="ch_bx">
                                                <label for="name">{{$value->name}}</label>
                                                <input type="text" name="kitchen[]" value="0" id="kitchen{{$value->id}}">
                                                <div class="_btn_act">
                                                    <a href="JavaScript:void(0);" class="a_sign" onclick="increaseValue('kitchen<?php echo  $value->id ?>')" id="add{{$value->id}}">+</a>
                                                    <a href="JavaScript:void(0);" class="a_sign" onclick="decreaseValue('kitchen<?php echo  $value->id ?>')" id="minus{{$value->id}}">-</a>
                                                </div>
                                            </div>
                                        </li>
                                         
                                     <?php } ?>
                                </ul>
                            </div>
                        </div>

                        <div class="panel _pnl">
                            <div class="panel-heading panelheding">

                                <span class="pull-left clickable"><i class="glyphicon glyphicon-plus"></i>Home Office:(Optional)</span>
                            </div>
                            <div class="panel-body">
                                 <ul>
                                   <?php foreach($homeOffice as $value){ ?>
                                    
                                        <li>
                                            <div class="ch_bx">
                                                <label for="name">{{$value->name}}</label>
                                                <input type="text" name="homeOffice[]" value="0" id="homeOffice{{$value->id}}">
                                                <div class="_btn_act">
                                                    <a href="JavaScript:void(0);" class="a_sign" onclick="increaseValue('homeOffice<?php echo  $value->id ?>')" id="add{{$value->id}}">+</a>
                                                    <a href="JavaScript:void(0);" class="a_sign" onclick="decreaseValue('homeOffice<?php echo  $value->id ?>')" id="minus{{$value->id}}">-</a>
                                                </div>
                                            </div>
                                        </li>
                                         
                                     <?php } ?>
                                </ul>
                            </div>
                        </div>

                        <div class="panel _pnl">
                            <div class="panel-heading panelheding">

                                <span class="pull-left clickable"><i class="glyphicon glyphicon-plus"></i>Garage:(Optional)</span>
                            </div>
                            <div class="panel-body">
                               <ul>
                                   <?php foreach($garage as $value){ ?>
                                    
                                        <li>
                                            <div class="ch_bx">
                                                <label for="name">{{$value->name}}</label>
                                                <input type="text" name="garage[]" value="0" id="garage{{$value->id}}">
                                                <div class="_btn_act">
                                                    <a href="JavaScript:void(0);" class="a_sign" onclick="increaseValue('garage<?php echo  $value->id ?>')" id="add{{$value->id}}">+</a>
                                                    <a href="JavaScript:void(0);" class="a_sign" onclick="decreaseValue('garage<?php echo  $value->id ?>')" id="minus{{$value->id}}">-</a>
                                                </div>
                                            </div>
                                        </li>
                                         
                                     <?php } ?>
                                </ul>
                            </div>
                        </div>

                        <div class="panel _pnl">
                            <div class="panel-heading panelheding">

                                <span class="pull-left clickable"><i class="glyphicon glyphicon-plus"></i>Outdoors:(Optional)</span>
                            </div>
                            <div class="panel-body">
                               <ul>
                                   <?php foreach($outdoors as $value){ ?>
                                    
                                        <li>
                                            <div class="ch_bx">
                                                <label for="name">{{$value->name}}</label>
                                                <input type="text" name="outdoors[]" value="0" id="outdoors{{$value->id}}">
                                                <div class="_btn_act">
                                                    <a href="JavaScript:void(0);" class="a_sign" onclick="increaseValue('outdoors<?php echo  $value->id ?>')" id="add{{$value->id}}">+</a>
                                                    <a href="JavaScript:void(0);" class="a_sign" onclick="decreaseValue('outdoors<?php echo  $value->id ?>')" id="minus{{$value->id}}">-</a>
                                                </div>
                                            </div>
                                        </li>
                                         
                                     <?php } ?>
                                </ul>
                            </div>
                        </div>

                        <div class="panel _pnl">
                            <div class="panel-heading panelheding">

                                <span class="pull-left clickable"><i class="glyphicon glyphicon-plus"></i>Boxes:(Optional)</span>
                            </div>
                            <div class="panel-body">
                               <ul>
                                   <?php foreach($boxes as $value){ ?>
                                    
                                        <li>
                                            <div class="ch_bx">
                                                <label for="name">{{$value->name}}</label>
                                                <input type="text" name="boxes[]" value="0" id="boxes{{$value->id}}">
                                                <div class="_btn_act">
                                                    <a href="JavaScript:void(0);" class="a_sign" onclick="increaseValue('boxes<?php echo  $value->id ?>')" id="add{{$value->id}}">+</a>
                                                    <a href="JavaScript:void(0);" class="a_sign" onclick="decreaseValue('boxes<?php echo  $value->id ?>')" id="minus{{$value->id}}">-</a>
                                                </div>
                                            </div>
                                        </li>
                                         
                                     <?php } ?>
                                </ul>
                            </div>
                        </div>

                        <div class="panel _pnl">
                            <div class="panel-heading panelheding">

                                <span class="pull-left clickable"><i class="glyphicon glyphicon-plus"></i>Miscellaneous Boxes</span>
                            </div>
                            <div class="panel-body">
                                <ul>
                                   <?php foreach($miscellaneous as $value){ ?>
                                    
                                        <li>
                                            <div class="ch_bx">
                                                <label for="name">{{$value->name}}</label>
                                                <input type="text" name="miscellaneous[]" value="0" id="miscellaneous{{$value->id}}">
                                                <div class="_btn_act">
                                                    <a href="JavaScript:void(0);" class="a_sign" onclick="increaseValue('miscellaneous<?php echo  $value->id ?>')" id="add{{$value->id}}">+</a>
                                                    <a href="JavaScript:void(0);" class="a_sign" onclick="decreaseValue('miscellaneous<?php echo  $value->id ?>')" id="minus{{$value->id}}">-</a>
                                                </div>
                                            </div>
                                        </li>
                                         
                                     <?php } ?>
                                </ul>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="row">

                    <div class="col-md-12">
                
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
                     <input type="hidden" name="subcategory_id" value="{{$subcategory_id}}">
                </div>
            </div>  <div class="gallery"></div>

                    <div class="col-md-6">
                        <div class="_add_cmt_bx">
                            <h4>Additional information (Optional) </h4>
                            <p>Additional information:</p>
                            <div class="form-group">
                                <textarea name="additonalInfo" id="additonalInfo" cols="55" rows="5" class="form-control"></textarea>
                                <small>You have 1200 characters left</small>
                            </div>
                        </div>

                        <div class="_add_btn_btm">
                            <button class="btn btn-border">Back</button>
                            <button type="button" class="btn btn-color" onclick="validate_home('next')">Continue</button>
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
    $('#home_form').validate({

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
function validate_home(action_type){  
    
     var title = $('#title').val();
    
     var c=0;
     
        if(title==''){
            $('html, body').animate({ scrollTop: 100 }, 1000);
            $('#errname').show();
                c = c+1;
            }else{
                $('#errname').hide();
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

