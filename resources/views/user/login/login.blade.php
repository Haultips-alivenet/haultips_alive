@extends('layouts.user-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')


<section class="main_signup _login_bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-offset-3 col-md-offset-3  col-lg-6 col-md-6">
                <div class="snup_bx _login wow zoomIn">
                    <h2>Log in</h2>
                     @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li> {{$error}} </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                     @if(Session::has('success'))
                            <div class="alert alert-success">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                {{Session::get('success')}}
                            </div>
                        @endif
                   <div class="clearfix"></div>
                    <div class="_cus_bx _cus_login">
                        <p>Don't have an account? <a href="{{url('user/signup')}}">Haultips Sign Up</a></p>
                           {!! Form::open(array('url'=>'/auth/login','id'=>'login-form')) !!}
                            <div class="form-group ">
                               
                                  {!! Form :: text('email','',['placeholder'=>'Enter your username or email address','class'=>'user form-control','id'=>'user']) !!}
                                  {!! Form :: hidden('user','user',['id'=>'user']) !!}
                            </div>
                            <div class="form-group">
                                
                                  {!! Form::password('password', array('placeholder'=>'Password', 'class'=>'lock form-control')) !!}
                            </div>
                             
                            <div class="form-group">
                                <div class="col-md-6">
                                
                            <div class="checkbox checkbox-circle">
                            <input name="remember" value="1" type="checkbox" class="filled-in" id="checkbox2">
                            <label for="checkbox2">Remember me</label>
                            </div>


                                </div>
                                <div class="col-md-6">
                                    <div class="rft_ps">
<!--                                        <a href="#">Forgot password1?</a>-->
                                    </div>
                                </div>
                            </div>

                         <br>
                            <hr style="border-color:#ccc;">
                               <br> 
                            <div class="form-group text-center">
                                 {!! Form::submit('Login',array('class'=>'btn btn-color')) !!}
                                
                            </div>  

                           </div>
                            
                         {!!Form::close()!!}

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


