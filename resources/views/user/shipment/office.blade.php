@extends('layouts.user-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')


<section class="_inner_pages containerfirstphase">
    <div class="container">
        <div class="row">
            <div class="_inner_bx">          
                <h2>Office</h2>
                <br>
                <br>

                <div class="clearfix"></div>
            <!--   {!! Form::open(array('url'=>'office','class'=>'form-horizontal','id'=>'office_form', 'method'=>'POST', 'files'=>true,)) !!}-->
               <form role="form" method="POST" action="{{ url('office') }}" enctype="multipart/form-data">
               <div class="col-md-8">
                   <div class="col-md-6">                             
                            <div class="form-group">
                                <label for="">Delivery Title</label>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="text" name="title" value=""  id="title" class="office-data">
                             </div>
                       <div id='errname' style='display:none'>Please Enter delivery title</div>
                        </div>
                <div class="select_opt">
                    <div class="row">
                        <div class="col-md-6">                             
                            <div class="form-group">
                                <label for="">Collection floor</label>
                                {!! Form::select('collectionFloor', ['First Floor', 'Second Floor', 'Third Floor', 'Fourth Floor', 'Fifth Floor', 'Sixth Floor', 'Seventh Floor', 'Eight Floor'], null, ['class'=>'selectpicker']) !!}
                             </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Delivery Floor</label>
                                 {!! Form::select('deliveryFloor', ['First Floor', 'Second Floor', 'Third Floor', 'Fourth Floor', 'Fifth Floor', 'Sixth Floor', 'Seventh Floor', 'Eight Floor'], null, ['class'=>'selectpicker']) !!}
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <br>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-4"><label for="">Lift/Elevatior</label></div>
                                    <div class="col-sm-8">
                                        <div class="radio radio-inline">
                                            <input name="lift" id="radio1" value="1" checked="" type="radio">
                                            <label for="radio1">
                                                Yes
                                            </label>                                               
                                        </div> 
                                        <div class="radio radio-inline">
                                            <input name="lift" id="radio2" value="0" checked="" type="radio">
                                           <label for="radio2">
                                               No
                                           </label>
                                       </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>    
               </div>

  
         <div class="row">
             <div class="col-md-12">
                <h4><i>Get the best bids by filling out the information below.</i></h4>
                <h5>Delivery Title</h5>
                <div class="clearfix"></div>
                <p>(e.g..3 Large Boxes) This will become the title of your listing, so be as descriptive as possible.You do not have to include collection or  delivery information here..</p>

                <div class="panel _pnl">
                    <div class="panel-heading">
                        <span class="pull-left clickable"><i class="glyphicon glyphicon-minus"></i>General</span>
                    </div>
                    <div class="panel-body col-md-8">
                        <div class="row">
                            <ul>
                                <?php foreach($general as $generalData){ ?>
                                    <li>
                                        <div class="ch_bx">
                                                <label for="name">{{$generalData->name}}</label>
                                                <input type="text" name="general[]" value="0"  id="general{{$generalData->id}}">
                                                <div class="_btn_act">
                                                <a href="JavaScript:void(0);" class="a_sign" onclick="increaseValue('general<?php echo  $generalData->id ?>')" id="add{{$generalData->id}}">+</a>
                                                <a href="JavaScript:void(0);" class="a_sign" onclick="decreaseValue('general<?php echo  $generalData->id ?>')" id="minus{{$generalData->id}}">-</a>
                                                </div>
                                        </div>  
                                    </li>
                                <?php } ?>                                       
                            </ul>
                        </div>
                    </div>
                </div>


                <div class="panel _pnl">
                <div class="panel-heading">
                    <span class="pull-left clickable"><i class="glyphicon glyphicon-minus"></i>Equipment</span>
                </div>
                    <div class="panel-body col-md-8">
                        <div class="row">
                            <ul>
                                <?php foreach($equipment as $equipmentData){ ?>
                                    <li>
                                        <div class="ch_bx">
                                                <label for="name">{{$equipmentData->name}}</label>
                                                <input type="text" name="equipment[]" value="0"  id="equipment{{$equipmentData->id}}">
                                                <div class="_btn_act">
                                                <a href="JavaScript:void(0);" class="a_sign" onclick="increaseValue('equipment<?php echo  $equipmentData->id ?>')"  id="add{{$equipmentData->id}}">+</a>
                                                <a href="JavaScript:void(0);" class="a_sign" onclick="decreaseValue('equipment<?php echo  $equipmentData->id ?>')" id="minus{{$equipmentData->id}}">-</a>
                                                </div>
                                        </div>  
                                    </li>
                                <?php } ?>                                       
                            </ul>
                        </div>
                    </div>
                </div>


                <div class="panel _pnl">
                    <div class="panel-heading">
                        <span class="pull-left clickable"><i class="glyphicon glyphicon-minus"></i>Miscellaneous Boxes</span>
                    </div>
                    <div class="panel-body col-md-8">
                        <div class="row">
                            <ul>
                                <?php foreach($miscellaneous as $miscellaneousData){ ?>
                                    <li>
                                        <div class="ch_bx">
                                                <label for="name">{{$miscellaneousData->name}}</label>
                                                <input type="text" name="box[]" value="0"  id="misc{{$miscellaneousData->id}}">
                                                <div class="_btn_act">
                                                <a href="JavaScript:void(0);" class="a_sign" onclick="increaseValue('misc<?php echo  $miscellaneousData->id ?>')"  id="add{{$miscellaneousData->id}}">+</a>
                                                <a href="JavaScript:void(0);" class="a_sign" onclick="decreaseValue('misc<?php echo  $miscellaneousData->id ?>')" id="minus{{$miscellaneousData->id}}">-</a>
                                                </div>
                                        </div>  
                                    </li>
                                <?php } ?>                                       
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                    
        <div class="row">
            <div class="col-md-12">
                
                <ul class="img_up_list">
                    
                </ul>
                <div id='errAttchment' style='display:none'>Image should be jpg or png</div>
                <div class="clearfix"></div>

                <div class="form-group _fl_upd">
                    <label for="">Upload pictures:</label>
                    <div id="imagePreview"></div>
                    <span> <input id="uploadFile" type="file"  name="image" class="_fl_f" />
                    Add Photo
                    </span>
                  
                </div>
            </div>  <div class="gallery"></div>
            <div id="finalimage">
                <input type="text" name="inputTextToSave" id="inputTextToSave">
            </div>

            <div class="col-md-6">
                <div class="_add_cmt_bx">
                    <h4>Additional information (Optional) </h4>
                    <p>Additional information:</p>
                    <div class="form-group">
                        <textarea name="additonalInfo" id="" cols="55" rows="5"  class="form-control"></textarea>
                        <small>You have 1200 characters left</small>
                    </div>
                </div>

                <div class="_add_btn_btm">
                    <button class="btn btn-border">Back</button>                    
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
                    {!! Form::hidden('imageCount', '', array('id' => 'imageCount')) !!}
              </div>                
            <div class="form-group">
            
             {!! Form :: submit("Submit",["class"=>"btn btn-color",'id'=>'']) !!}
            </div>
               </div> 
                    
                </div> 
                <div class="clearfix"></div>
                
    </div>
</section>
 <!--{!! Form::close() !!}-->
</form>
@endsection
@section('script')
<script type="text/javascript">
    
    $('#office_form').validate({

            rules: {
                pickupdate:{
                    required : true,
                },
                deliverydate:{
                    required : true,
                    
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
                    required : "Select Delivery Date"
                   
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
            var j=1;
            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function(event) {
                    $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                    $('img').wrap('<li />');
                   
                }
              // alert(event.target.result);
              //  $a='<input id="uploadFilellll" type="file"  name="image[]" class="" value="'+event.target.result+'" />';
               // $('#finalimage').html($a);
                reader.readAsDataURL(input.files[i]);
                j++;
            }
        }

    };

    $('#uploadFile').on('change', function() {
    
        var fileToLoad = document.getElementById("uploadFile").files[0];

  var fileReader = new FileReader();
  fileReader.onload = function(fileLoadedEvent){
      var textFromFileLoaded = fileLoadedEvent.target.result;
      document.getElementById("inputTextToSave").value = textFromFileLoaded;
  };

    
    
    
         imagesPreview(this, 'ul.img_up_list'); 
          var imageValue = imageCount++; 
        $('#imageCount').val(imageValue);
        });
});

$('#office-shipment').validate({ 

            rules: {
                pickDate :{
                    required : true
                },
                pickAddress :{
                    required : true,
                    minlength:2
                },
                deliveryaddress :{
                    required : true,
                    minlength:2
                }
             },
            messages: {
                
                pickDate :{
                    required : "Select the Pickup Date"
                },
                pickAddress :{
                    required : "Enter your Pick Address",
                    minlength : 'Pick Address should be 2 digits'
                },
                deliveryaddress :{
                    required : "Enter your Delivery Address",
                    minlength : 'Delivery Address should be 2 digits'
                }
            }
            
           

        });

function validate_office(action_type){  
    
     var title = $('#title').val();
     var image = $('#uploadFile').val();
     var c=0;
     
        if(title==''){
            $('html, body').animate({ scrollTop: 100 }, 1000);
            $('#errname').show();
                c = c+1;
            }else{
                $('#errname').hide();
            }
        
        if(image!=''){
                var fileInput = $('#uploadFile')[0]; 
                var ext = fileInput.files[0].type;     
                if($.inArray(ext, ['image/jpg','image/jpeg','image/png']) == -1){
                    $('html, body').animate({ scrollTop: 450 }, 1000);
                    $('#errAttchment').show();
                    c = c+1;
                }else{
                    $('#errAttchment').hide();
                } 
        }

        if(c>0){
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

</script>
@endsection

