@extends('layouts.user-dashboard-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')

            <div class="col-md-8">
            {!! Form::open(array('url'=>'user/changepassword','id'=>'ch_pass_form')) !!}
                {!! csrf_field() !!}
                 <div class="_dash_rft _dash_m">
                    <h3>Change Password</h3> 
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li> {{$error}} </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(Session::has('alert_msg'))
                            <div class="alert alert-{{ Session::get('alert_type') }}">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                {{ Session::get('alert_msg') }}
                            </div>
                        @endif

                        <div class="_dash_mbx">

                            <div class="form-group">
                                {!! Form::password('old_password', array('placeholder'=>'Enter your Current Password', 'class'=>'form-control', 'id'=>'old_password')) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::password('new_password', array('placeholder'=>'Enter your New Password', 'class'=>'form-control', 'id'=>'new_password')) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::password('new_password_confirmation', array('placeholder'=>'Enter your New Password To Confirm', 'class'=>'form-control', 'id'=>'confirm_password')) !!}
                            </div>
                        
                        </div>
                        <hr>
                        <div class="form-group">
                            <button type="submit" class="btn btn-color">Submit</button>
                        </div>

                 </div> 
            {!!Form::close()!!} 
            </div>
@endsection

@section('script')
<script type="text/javascript">
$('#ch_pass_form').validate({
    rules: {
        old_password:{
            required : true,
        },
        new_password:{
            required : true,
        },
        new_password_confirmation: {
          equalTo: "#new_password"
        }
        
    },
    messages: {
        old_password :{
            required : "Enter old password"
        },
        new_password :{
            required : "Enter new password"
        },
        new_password_confirmation :{
            required : "Enter new password to confirm"
        }
    }

});
</script>
@endsection

