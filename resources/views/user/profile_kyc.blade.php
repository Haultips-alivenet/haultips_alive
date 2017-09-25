@extends('layouts.user-dashboard-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')


<div class="col-md-8">
           <div class="_dash_rft">
           
            <div class="row">
                 @if(Session::has('success'))
                            <div class="alert alert-success">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                {{Session::get('success')}}
                            </div>
                        @endif   
                <div class="col-md-12">
                 <div class="_per_docu_fl">
                     <h3>Personal Document</h3>
                     {!! Form::open(array('url'=>'user/kyc/rc', 'id'=>'rc_form','files'=>true)) !!} 
                     <div class="col-md-4">
                       <div class="form-group">
                           <label for="">Upload RC Photo</label>
                            <div class="_up_img_load">
                                <div class="_imp_load_fl">
                                    <img src="{{ asset('public/admin/kyc/' . @$kycdata->rc_photo) }}" alt="">
                                </div>                                
                            </div>
                           <div class="up_fl_in" style="width:80%"><input type="file" name="rc_photo" id="rc_photo" onchange="getrc(1);">
                                Upload 
                           </div>
                       </div>
                    </div>  
                     {!!Form::close()!!}
                     {!! Form::open(array('url'=>'user/kyc/pan', 'id'=>'pan_form','files'=>true)) !!} 
                     <div class="col-md-4">
                       <div class="form-group">
                           <label for="">Pan Card</label>
                            <div class="_up_img_load">
                                <div class="_imp_load_fl">
                                    <img src="{{asset('public/admin/kyc/'. @$kycdata->pancart)}}" alt="">
                                </div>                                
                            </div>
                           <div class="up_fl_in" style="width:80%"><input type="file" name="pancard" id="pancard" onchange="getrc(2);">
                                Upload 
                           </div>
                       </div>
                    </div>  
                     {!!Form::close()!!}
                     {!! Form::open(array('url'=>'user/kyc/business', 'id'=>'business_form','files'=>true)) !!} 
                     <div class="col-md-4">
                       <div class="form-group">
                           <label for="">Business Card</label>
                            <div class="_up_img_load">
                                <div class="_imp_load_fl">
                                    <img src="{{asset('public/admin/kyc/'.@$kycdata->business_card)}}" alt="">
                                </div>                                
                            </div>
                           <div class="up_fl_in" style="width:80%"><input type="file" name="businesscard" id="businesscard" onchange="getrc(3);">
                                Upload 
                           </div>
                       </div>
                    </div>  
                     {!!Form::close()!!}
                 </div>
                
                </div>
            </div>
          </div>
          </div>
@endsection

@section('script')
<script>
    function getrc(id){
      
        if(id==1) {
        var fuData = document.getElementById('rc_photo');
        } else if(id==2) {
             var fuData = document.getElementById('pancard');
        } else if(id==3){
            var fuData = document.getElementById('businesscard');
        }
        var FileUploadPath = fuData.value;

//To check if user upload any file
        if (FileUploadPath == '') {
            alert("Please upload an image");

        } else {
            var Extension = FileUploadPath.substring(
                    FileUploadPath.lastIndexOf('.') + 1).toLowerCase();

//The file uploaded is an image

if (Extension == "gif" || Extension == "png" 
                    || Extension == "jpeg" || Extension == "jpg") {

        if(id==1) {
        document.getElementById("rc_form").submit();
        } else if(id==2) {
           document.getElementById("pan_form").submit();
        } else if(id==3){
           document.getElementById("business_form").submit();
        }
 
            } 

//The file upload is NOT an image
else {
                alert("Photo only allows file types of GIF, PNG, JPG and JPEG. ");

            }
        }
    }
       
    
    
   
    </script>
@endsection
