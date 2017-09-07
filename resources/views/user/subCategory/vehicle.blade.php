@extends('layouts.user-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')



<section class="categories">
    <div class="container">
         {!! Form::open(array('url'=>'user/vehicle/pickup','class'=>'form-horizontal','id'=>'vehicle_form','files' => true)) !!}
        <div class="row">
            
            <div class="col-md-6">
                <div class="_cate_up_img">
                <img src="img/img_upload_icon.png" id="thumbnil" style="width:45%; margin-top:45;"  alt="" class="wow pulse">
                <div class="clearfix"></div>
                    <div class="_ul_d">
                    Upload Images
                        <input type="file"  onchange="showMyImage(this)" class="_btn_f" name="vehicle_image" title="Upload images">
                    </div>
                </div>

                <div class="_cate_up_dv">
                    <h2>Vehicle information </h2>
                       <div class="form-group row">
                            <div class="col-md-5">
                                <label for="">Delivery Title</label>
                                </div>
                                <div class="col-md-7">
                            <input type="text" name="delivery_title" class="form-control">
                            </div>
                       </div>  
                       <div class="form-group row">
                       <div class="col-md-5">
                            <label for="">Vehicle Name</label>
                            </div>
                            <div class="col-md-7">
                            <input type="text" name="vehicle_name" class="form-control">
                            </div>
                       </div>     
                       <div class="form-group row">
                            <div class="col-md-5">
                                <label for="">Vehicle Model</label>
                                </div>
                                <div class="col-md-7">
                            <input type="text" name="vehicle_model" class="form-control">
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
                            <textarea name="" id="" cols="55" rows="5"  class="form-control"></textarea>
                            <small>You have 1200 characters left</small>
                        </div>
                    </div>

                    <div class="_add_btn_btm">
                        <button class="btn btn-border">Back</button>
                       
                         {!! Form :: submit("Continue",["class"=>"btn btn-color",'id'=>'']) !!}
                    </div>

                </div>
                <div class="col-md-6">
                <i class="fa fa-question pull-left wow bounceIn" aria-hidden="true"></i>
                    <div class="_info_txt">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis 
                    </div>   
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</section>

@endsection
@section('script')
<script>
  function showMyImage(fileInput) {
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
 $('#vehicle_form').validate({

            rules: {
                delivery_title:{
                    required : true,
                    minlength:2
                    
                },
                 vehicle_name:{
                    required : true,
                    minlength:2
                    
                },
                vehicle_image : {
                    required : true,
                    extension: "jpg|png|jpeg|gif"
                }
                
            },

            messages: {
                
                delivery_title :{
                    required : "Enter Delivery Title",
                    minlength : 'Delivery Title should be 2 digits'
                },
                vehicle_name :{
                    required : "Enter Vehicle Name",
                    minlength : 'Vehicle Name should be 2 digits'
                },
                 vehicle_image :{
                    required : "Select Vehicle Image",
                    extension : "Select Image Only"
                }
               
            }
            
           

        });
</script>
@endsection

