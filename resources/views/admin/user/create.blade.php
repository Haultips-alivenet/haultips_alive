@extends('layouts.admin-layout')
@section('title')
    Admin Panel | Haultips
@endsection

@section('body')
    <div id="page-wrapper">
        <div class="graphs">
            <h3 class="blank1">New User</h3>
                <div class="tab-content">
                    {{--    Error Display--}}
                        @if($errors->any())
                        <ul class="alert">
                            @foreach($errors->all() as $error)
                            <li style="color:red;"><b>{{ $error }}</b></li>
                            @endforeach
                        </ul>
                        @endif
                    {{--    Error Display ends--}}
                    <div class="tab-pane active" id="horizontal-form">
                        {!! Form::open(array('url'=>'admin/store','class'=>'form-horizontal','id'=>'newUser')) !!}
                            
                            <div id="msgStatus"></div>
                             <div class="form-group" style="display:none">
                                     {!! Form :: text('userType','3',['class'=>'form-control1', 'id'=>'userType'])  !!}                                                              
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">First Name</label>
                                <div class="col-sm-8">
                                     {!! Form :: text('firstName','',['class'=>'form-control1', 'id'=>'firstname'])  !!}
                                </div>                                
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Last Name</label>
                                <div class="col-sm-8">
                                    {!! Form :: text('lastName','',['class'=>'form-control1', 'id'=>'lastname'])  !!}
                                </div>                                
                            </div>
                             <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Mobile</label>
                                <div class="col-sm-8">
                                    {!! Form :: text('mobile','',['class'=>'form-control1', 'id'=>'mobile'])  !!}
                                </div>                                
                            </div>
                            <div class="form-group">
				<label class="col-sm-2 control-label">Email</label>
				<div class="col-sm-8">	
                                     {!! Form :: text('email','',['class'=>'form-control1', 'id'=>'email'])  !!}
				</div>
                            </div>                            
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Password</label>
                                <div class="col-sm-8">
                                    {!! Form :: password('password',['placeholder'=>'Minimum 6 characters','class'=>'form-control1'])  !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Confirm Password</label>
                                <div class="col-sm-8">
                                    {!! Form :: password('cpassword',['placeholder'=>'Minimum 6 characters | Same as Password','class'=>'form-control1'])  !!}
                                </div>
                            </div>
                            <div class="col-sm-8 col-sm-offset-2">
                            {!! Form :: submit("Save User",["class"=>"btn-success btn",'id'=>'user']) !!}
                            </div>
                        {!! Form::close() !!}
					
                    </div>
                </div>
        </div>
    </div>
@endsection

@section('script')
<script>
$('#newUser').validate({

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
                    minlength:10
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