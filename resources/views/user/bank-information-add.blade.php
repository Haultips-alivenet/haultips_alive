@extends('layouts.user-dashboard-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')
<div class="col-md-8">
    <div class="_dash_rft _bnkac">
        <div class="row">
            <div class="col-md-9">
                <h3>Bank Information</h3>

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

                {!! Form::open(array('url'=>'user/bank-infomation/add', 'id'=>'bank_info_form')) !!}
                    <div class="form-group">
                        <label for="">
                            <i class="fa fa-university" aria-hidden="true"></i>Bank Name
                        </label>
                        {!! Form::text('name', '', array('class'=>'form-control', 'id'=>'name')) !!}
                    </div>

                    <div class="form-group">
                        <label for="">
                            <i class="fa fa-credit-card" aria-hidden="true"></i>Account Number
                        </label>
                        {!! Form::text('number', '', array('class'=>'form-control', 'id'=>'number')) !!}
                    </div>

                    <div class="form-group">
                        <label for="">
                            <i class="fa fa-university" aria-hidden="true"></i>IFSC Code
                        </label>
                        {!! Form::text('code', '', array('class'=>'form-control', 'id'=>'code')) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::submit('Submit', array('class'=>'btn btn-color', 'id'=>'submit')) !!}
                    </div>
                {!!Form::close()!!}
            </div>   
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
$('#bank_info_form').validate({
    rules: {
        name:{
            required : true,
        },
        number:{
            required : true,
            number: true
        },
        code:{
            required : true,
        },
    },
    messages: {
        name :{
            required : "Enter Bank Name"
        },
        number :{
            required : "Enter Account Number",
            number : "Enter Valid Account Number"
        },
        code :{
            required : "Enter IFSC Code"
        },
    }

});   
</script>

@endsection

