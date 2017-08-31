@extends('layouts.user-layout')
@section('title')
    Admin Panel | Haultips
@endsection

@section('body')
<style>
    ._box{
       color: #333;
        padding: 20px;
        display: none;
        margin-top: 20px;
    }
    .inner_box{
       color: #333;
        padding: 20px;
        display: none;
        margin-top: 20px;
    }
    
    .checkbox input{ left: 0;}
    .inner{
      border:1px solid #ddd;
      padding:10px;
      margin-bottom:5px;
    }
    ._box{
       margin-left:10px;
       border:1px solid #0d0;
    }
    .inner_box{ border-top:1px dashed #ddd;}
    </style>
<section class="main_signup _cus_bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-push-3 col-md-push-3 col-lg-6 col-md-6">
                <div class="snup_bx _cus wow zoomIn">
                    <h2>Sign up</h2>
                     {{--    Error Display--}}
                        @if($errors->any())
                        <ul class="alert">
                            @foreach($errors->all() as $error)
                            <li style="color:red;"><b>{{ $error }}</b></li>
                            @endforeach
                        </ul>
                        @endif
                    {{--    Error Display ends--}}
                   <div class="clearfix"></div>
                    <div class="_cus_bx">
                        
                        {!! Form::open(array('url'=>'user/partner-registration','class'=>'form-horizontal','id'=>'newPartneraaa')) !!}
                           <div class="col-lg-12">
                             <div class="form-group">
                                   {!! Form :: hidden('userType','2',['class'=>'form-control1', 'id'=>'userType'])  !!}
                                
                                 {!! Form :: text('firstName','',['class'=>'form-control', 'id'=>'firstname','placeholder'=>'Enter your First Name'])  !!}
                            </div>
                            <div class="form-group">
                                
                                  {!! Form :: text('lastName','',['class'=>'form-control', 'id'=>'lastname','placeholder'=>'Enter your Last Name'])  !!}
                            </div>
                           
                            <div class="form-group">
                                
                                 {!! Form :: text('email','',['class'=>'form-control', 'id'=>'email','placeholder'=>'Enter your email address'])  !!}
                            </div>
                            <div class="form-group">
                                
                                {!! Form :: text('mobile','',['class'=>'form-control', 'id'=>'mobile','placeholder'=>'Enter your Mobile Number'])  !!}
                            </div> 
                            <div class="form-group">
                                
                                {!! Form :: password('password',['placeholder'=>'Password | Minimum 6 characters','class'=>'form-control','placeholder'=>'Enter your Password'])  !!}
                            </div>
                            <div class="form-group">
                                
                                {!! Form :: password('cpassword',['placeholder'=>'Minimum 6 characters | Same as Password','class'=>'form-control'])  !!}
                            </div>   
                               
                            <div class="row">
                            <div class="form-group">
                              <div class="col-lg-6" >
                                <select name="city" id="city" class="selectpicker form-control" >
                                <option value="">select a city</option>
                                <option value="Andaman & Nicobar Islands">Andaman & Nicobar Islands</option>
                                <option value="Andhra Pradesh">Andhra Pradesh</option>
                                <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                <option value="Assam">Assam</option>
                                <option value="Bihar">Bihar</option>
                                <option value="Chandigarh">Chandigarh</option>
                                <option value="Chhatisgarh">Chhatisgarh</option>
                                <option value="Dadra & Nagar Haveli">Dadra & Nagar Haveli</option>
                                <option value="Daman & Diu">Daman & Diu</option>
                                <option value="Delhi">Delhi</option>
                                <option value="Goa">Goa</option>
                                <option value="Gujarat">Gujarat</option>
                                <option value="Haryana">Haryana</option>
                                <option value="Himachal Pradesh">Himachal Pradesh</option>
                                <option value="Jammu & Kashmir">Jammu & Kashmir</option>
                                <option value="Jharkhand">Jharkhand</option>
                                <option value="Karnataka">Karnataka</option>
                                <option value="Kerala">Kerala</option>
                                <option value="Lakshadweep">Lakshadweep</option>
                                <option value="Madhya Pradesh">Madhya Pradesh</option>
                                <option value="Maharashtra">Maharashtra</option>
                                <option value="Manipur">Manipur</option>
                                <option value="Meghalaya">Meghalaya</option>
                                <option value="Mizoram">Mizoram</option>
                                <option value="Nagaland">Nagaland</option>
                                <option value="Odisha">Odisha (Orissa)</option>
                                <option value="Puducherry (Pondicherry)">Puducherry (Pondicherry)</option>
                                <option value="Punjab">Punjab</option>
                                <option value="Rajasthan">Rajasthan</option>
                                <option value="Sikkim">Sikkim</option>
                                <option value="Tamil Nadu">Tamil Nadu</option>
                                <option value="Telangana">Telangana</option>
                                <option value="Tripura">Tripura</option>
                                <option value="Uttar Pradesh">Uttar Pradesh</option>
                                <option value="Uttarakhand">Uttarakhand</option>
                                <option value="West Bengal">West Bengal</option>
                                </select>
                              </div> 
                              <div class="col-lg-6">
                                   <select name="state" id="state" class="selectpicker form-control">
                                <option value="">select a state</option>
                                <option value="Andaman & Nicobar Islands">Andaman & Nicobar Islands</option>
                                <option value="Andhra Pradesh">Andhra Pradesh</option>
                                <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                <option value="Assam">Assam</option>
                                <option value="Bihar">Bihar</option>
                                <option value="Chandigarh">Chandigarh</option>
                                <option value="Chhatisgarh">Chhatisgarh</option>
                                <option value="Dadra & Nagar Haveli">Dadra & Nagar Haveli</option>
                                <option value="Daman & Diu">Daman & Diu</option>
                                <option value="Delhi">Delhi</option>
                                <option value="Goa">Goa</option>
                                <option value="Gujarat">Gujarat</option>
                                <option value="Haryana">Haryana</option>
                                <option value="Himachal Pradesh">Himachal Pradesh</option>
                                <option value="Jammu & Kashmir">Jammu & Kashmir</option>
                                <option value="Jharkhand">Jharkhand</option>
                                <option value="Karnataka">Karnataka</option>
                                <option value="Kerala">Kerala</option>
                                <option value="Lakshadweep">Lakshadweep</option>
                                <option value="Madhya Pradesh">Madhya Pradesh</option>
                                <option value="Maharashtra">Maharashtra</option>
                                <option value="Manipur">Manipur</option>
                                <option value="Meghalaya">Meghalaya</option>
                                <option value="Mizoram">Mizoram</option>
                                <option value="Nagaland">Nagaland</option>
                                <option value="Odisha (Orissa)">Odisha (Orissa)</option>
                                <option value="Puducherry (Pondicherry)">Puducherry (Pondicherry)</option>
                                <option value="Rajasthan">Punjab</option>
                                <option value="">Rajasthan</option>
                                <option value="Sikkim">Sikkim</option>
                                <option value="Tamil Nadu">Tamil Nadu</option>
                                <option value="Telangana">Telangana</option>
                                <option value="Tripura">Tripura</option>
                                <option value="Uttar Pradesh">Uttar Pradesh</option>
                                <option value="Uttarakhand">Uttarakhand</option>
                                <option value="West Bengal">West Bengal</option>
                                </select>


                              </div>

                              </div>
                               
                            </div>
                            <div class="form-group">
                                
                                {!! Form :: text('total_vehicle','',['class'=>'form-control', 'id'=>'total_vehicle','placeholder'=>'Total Vehicle'])  !!}
                            </div>
                             <div class="form-group">
                                
                                 {!! Form :: text('attached_vehicle','',['class'=>'form-control', 'id'=>'attached_vehicle','placeholder'=>'Attached Vehicle'])  !!}
                            </div>   
                            <div class="row">
                            <div class="form-group">
                              
                            <?php $i=1; foreach($carrier_types as $types) {?>
                              <div class="col-lg-6">
                                   <input class="carrer_group" type="checkbox" name="carrer_type<?php echo $i; ?>" id="carrer_type<?php echo $i; ?>"  value="{{$types->id}}"><span><img src="img/transport_icon.png" alt=""></span>{{$types->carrier_type}}
                                
                              </div>
                            <?php $i++; } ?>
                              </div>
                             <div class="form-group"  id="transport_div" style="display: none">
                                <?php foreach($categoryname as $types) { ?> 
                                 <div class="col-lg-12">
                                     <label><input type="checkbox" name="trucktype[]" id="trucktype" onclick="gettrucklength({{$types->id}});" value="{{$types->id}}">  {{$types->category_name}}</label>
                                     <div class="_box {{$types->id}}" id="trucklength_div{{$types->id}}"></div>
                                 </div>
                                   <?php } ?>
                            </div>
                            <div class="checkbox checkbox-circle">
                            <input type="checkbox" class="filled-in" id="checkbox2">
                            <label for="checkbox2">By clicking Register, you accept the terms and conditions of
the HAULTIPS User Agreement</label>
                            </div>

                            <br> <br>
                            <div class="form-group text-center">
                                 {!! Form :: submit("Save",["class"=>"btn btn-color",'id'=>'partner']) !!}
                                <a href="{{url('user/signup')}}" class="_back"><i  class="fa fa-chevron-left"></i>Back</a>
                            </div>  

                           </div>
                            
                         {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('script')
<script>
$(document).ready(function(){

    $('input[type="checkbox"]').click(function(){
       var inputValue = $(this).attr("value");
        $("."+ inputValue).toggle();
    });
});
$('#carrer_type2').click(function(){
   
this.checked?$('#transport_div').show():$('#transport_div').hide(); //time for show
});

$('#newPartneraaa').validate({

            rules: {
                firstName:{
                    required : true,
                    minlength:2
                },
                lastName:{
                    required : true,
                    minlength:2
                },
                email:{
                    required : true,
                    email : true
                },

                mobile:{
                    required : true,
                    minlength:10,
                    number:true
                },

                password:{
                    required : true
                },

                cpassword:{
                    required : true
                },
                 state:{
                    required : true,
                   
                },
                 city:{
                    required : true,
                    
                },
                 total_vehicle:{
                    required : true,
                    minlength:1,
                    number:true
                },
                 attached_vehicle:{
                    required : true,
                    minlength:1,
                    number:true
                },
                carrer_type1: {
                    require_from_group: [1, ".carrer_group"]
                  },
                  carrer_type2: {
                    require_from_group: [1, ".carrer_group"]
                  }
               
            },

            messages: {
                
                firstName :{
                    required : "Enter your First name",
                    minlength : 'First Name should be 2 digits'
                },
                lastName :{
                    required : "Enter your Last name",
                    minlength : 'Last Name should be 2 digits'
                },
                email :{
                    required : "Enter your email",
                    email : "Enter Valid email"
                },
                mobile :{
                    required : 'Enter your Mobile Number',
                    minlength : 'Mobile Number should be 10 digits'
                },                
                password :{
                    required : "Enter your Password",
                },                
                cpassword :{
                    required : "Enter your Confirm Password",
                },
                state :{
                    required : "Enter your State",
                    minlength : 'State should be 2 digits'
                },
                city :{
                    required : "Enter your City",
                    minlength : 'City should be 2 digits'
                },
                total_vehicle :{
                    required : "Enter your Total Vehicle",
                    minlength : 'Total Vehicle should be 1 digits'
                },
                attached_vehicle :{
                    required : "Enter your Attached Vehicle",
                    minlength : 'Attached Vehicle should be 1 digits'
                }
            }
            
           

        });
        function gettrucklength(id){
           $.ajax({ 
        type: 'get',
        url: '{{url('getlength')}}',
        data: 'id='+id,
        dataType: 'json',
        //cache: false,

        success: function(data) {
       // alert(data);
        console.log(data.truck_lengths);
       var _options='';
        
        for(i=0; i<data.truck_lengths.length; i++){
           
           _options +='<div class="inner">';
               _options +='<div class="">';
               _options +='<label><input type="checkbox" name="trucklength_'+id+'[]" id="trucklength'+ data.truck_lengths[i].id+'" onclick="gettruckcapacity('+ data.truck_lengths[i].id+');" value="'+ data.truck_lengths[i].id+'"> '+ data.truck_lengths[i].truck_length +'</label>';
              _options +='</div>';
               _options +='<div class="inner_box '+data.truck_lengths[i].id+'" id="truckcapacity_div'+ data.truck_lengths[i].id+'">';
              
               _options +='</div>';
               _options +='</div>';
               
        }
      
        //alert(id);
        $('#trucklength_div'+id).html(_options);
        }

   });
        }
        
    function gettruckcapacity(id){
    
   $.ajax({ 
        type: 'get',
        url: '{{url('getcapacity')}}',
        data: 'id='+id,
        dataType: 'json',
        //cache: false,

        success: function(data) {
       var _options='';
       for(i=0; i<data.truck_capacity.length; i++){
                 _options +='<label><input type="checkbox" name="truckcapacity_'+id+'[]" id="truckcapacity'+ data.truck_capacity[i].id+'" value="'+ data.truck_capacity[i].id+'"> '+ data.truck_capacity[i].truck_capacity +'</label>';
              
             
       }
         $("."+ id).toggle();
         $('#truckcapacity_div'+id).html(_options);
       
        }

   });
    }    
    
 
</script>
@endsection


