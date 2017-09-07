@extends('layouts.admin-layout')
@section('title')
    Admin Panel | Haultips
@endsection

@section('body')
    <div id="page-wrapper">
        <div class="graphs">
            <h3 class="blank1">Update User</h3>
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
                        {!! Form::model($user,['route'=>['admin.users.update',$user->id], 'method'=>'patch','class'=>'form-horizontal'])  !!}
                       
                            
                            <div id="msgStatus"></div>
                             
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">First Name</label>
                                <div class="col-sm-8">
                                     {!! Form :: text('firstName',$user->first_name,['class'=>'form-control1', 'id'=>'firstname'])  !!}
                                </div>                                
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Last Name</label>
                                <div class="col-sm-8">
                                    {!! Form :: text('lastName',$user->last_name,['class'=>'form-control1', 'id'=>'lastname'])  !!}
                                </div>                                
                            </div>
                             <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Mobile</label>
                                <div class="col-sm-8">
                                    {!! Form :: text('mobile',$user->mobile_number,['class'=>'form-control1', 'id'=>'mobile','readonly'])  !!}
                                </div>                                
                            </div>
                            <div class="form-group">
				<label class="col-sm-2 control-label">Email</label>
				<div class="col-sm-8">	
                                     {!! Form :: text('email',$user->email,['class'=>'form-control1', 'id'=>'email'])  !!}
				</div>
                            </div>                           
                            
                            <div class="col-sm-8 col-sm-offset-2">
                            {!! Form :: submit("Update User",["class"=>"btn-success btn",'id'=>'user']) !!}
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
                }
            }
            
           

        });
</script>
@endsection