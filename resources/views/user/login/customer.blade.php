@extends('layouts.user-layout')
@section('title')
    Admin Panel | Haultips
@endsection

@section('body')


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
                        
                        {!! Form::open(array('url'=>'user/customer-registration','class'=>'form-horizontal','id'=>'newCustomer')) !!}
                         {!! Form :: hidden('userType','3',['class'=>'form-control1', 'id'=>'userType'])  !!}
                           <div class="col-lg-12">
                             <div class="form-group">
                                
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
                                
                                 {!! Form :: password('password',['placeholder'=>'Password | Minimum 6 characters','class'=>'form-control'])  !!}
                            </div>
                                <div class="form-group">
                                
                                 {!! Form :: password('cpassword',['placeholder'=>'Minimum 6 characters | Same as Password','class'=>'form-control'])  !!}
                            </div>
                            <br> <br>
                            <div class="form-group text-center">
                                
                                {!! Form :: submit("Sign Up",["class"=>"btn btn-color",'id'=>'user']) !!}
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
 $.validator.addMethod("uniqueMobile", function(value, element) {
         var isSuccess = false;
            $.ajax({
                type: "GET",
                url: '{{url('user/mobileCheck')}}',
                data: "checkMobile="+value,
                async: false,
                //dataType:"html",
                success: function(msg)
                { 
                    //If username exists, set response to true
                    //response = (msg === 'true') ? true : false;
                    isSuccess = msg === "true" ?  false : true;
                    
                }
             });
            return isSuccess;
        },
        "User Mobile is Already Taken"
    ); 
 
$('#newCustomer').validate({

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
                    uniqueMobile: true
                },

                password:{
                    required : true
                },

                cpassword:{
                    required : true
                   // equalTo: "#password"
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
                    required : "Please re-enter password",
                }
            }
            
           

        });
</script>
@endsection

