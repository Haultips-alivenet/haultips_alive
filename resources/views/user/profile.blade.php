@extends('layouts.user-dashboard-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')

<div class="col-md-8">
    <div class="_dash_rft">
        <span class="edit_btn"><i class="fa fa-pencil" aria-hidden="true"></i></span>
        <div class="row">
            
            <div class="col-md-9 col-md-offset-1">
           
            {!! Form::open(array('url'=>'user/profile/edit', 'id'=>'prf_edt_frm')) !!}
             <h4>Your personal information</h4>
                <span id="alertmsg">
                    @if(Session::has('alert_msg'))
                        <div class="alert alert-{{ Session::get('alert_type') }}">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            {{ Session::get('alert_msg') }}
                        </div>
                    @endif
                </span>
                <div class="form-group col-md-12">
                    <div class="col-md-4"><label for="">First Name</label></div>
                    <div class="col-md-8">
                        <div class="_me_dis" id="first_name_spn">{{ $user->first_name }}</div>
                        <div class="_me_input">
                            {!! Form::text('first_name', $user->first_name, array('class'=>'form-control', 'id'=>'first_name')) !!}
                        </div> 
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <div class="col-md-4"><label for="">Last Name</label></div>
                    <div class="col-md-8">
                        <div class="_me_dis" id="last_name_spn">{{ $user->last_name }}</div>
                        <div class="_me_input">
                            {!! Form::text('last_name', $user->last_name, array('class'=>'form-control', 'id'=>'last_name')) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <div class="col-md-4"><label for="">Email Id</label></div>
                    <div class="col-md-8">
                        <div class="_me_dis" id="first_name_spn">{{ $user->email }}</div>
                        <div class="_me_input">
                            <span class="form-control">{{ $user->email }}</span>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <div class="col-md-4"><label for="">Phone No</label></div>
                    <div class="col-md-8">
                        <div class="_me_dis">{{ $user->country_code }}-<span id="mobile_number_spn">{{ $user->mobile_number }}</span></div>
                        <div class="_me_input">
                            <span class="form-control">{{ $user->country_code . '-' . $user->mobile_number }}</span>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <hr>
                <h4>Your Address</h4>
                <div class="form-group col-md-12">
                    <div class="col-md-4"><label for="">Street</label></div>
                    <div class="col-md-8">
                        <div class="_me_dis" id="street_spn">{{ @$user_detail->street }}</div>
                        <div class="_me_input">
                            {!! Form::text('street', @$user_detail->street, array('class'=>'form-control', 'id'=>'street')) !!}
                        </div>
                    </div>
                </div>
                
                <div class="form-group col-md-12">
                    <div class="col-md-4"><label for="">City</label></div>
                    <div class="col-md-8"> 
                        <div class="_me_dis" id="city_spn">{{ @$user_detail->city }}</div>
                        <div class="_me_input">
                            {!! Form::text('city', @$user_detail->city, array('class'=>'form-control', 'id'=>'city')) !!}
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-12">
                    <div class="col-md-4"><label for="">Enter a Location</label></div>
                    <div class="col-md-8">
                        <div class="_me_dis" id="location_spn">{{ @$user_detail->location }}</div>
                        <div class="_me_input">
                            {!! Form::text('location', @$user_detail->location, array('class'=>'form-control', 'id'=>'location')) !!}
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-12">
                    <div class="col-md-4"><label for="">Pin Code</label></div>
                    <div class="col-md-8">
                        <div class="_me_dis" id="pincode_spn">{{ @$user_detail->pincode }}</div>
                        <div class="_me_input">
                            {!! Form::text('pincode', @$user_detail->pincode, array('class'=>'form-control', 'id'=>'pincode')) !!}
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-12">
                    <div class="col-md-4"><label for="">Country</label></div>
                    <div class="col-md-8">
                        <div class="_me_dis" id="country_spn">{{ @$user_detail->country }}</div>
                        <div class="_me_input">
                            {!! Form::text('country', @$user_detail->country, array('class'=>'form-control', 'id'=>'country')) !!}
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>
                <hr>
                 <div class="form-group col-md-12 _me_input">
                 {!! Form::submit('Submit', array('class'=>'btn btn-color', 'id'=>'prf_submit')) !!}
                 </div>
                
                
            {!!Form::close()!!}
            </div>
            
            <?php if($user->user_type_id == 2) {?>
                <div class="form-group col-md-12 ">
                <a href="{{url('parner/profile/kyc')}}"> {!! Form::button('Kyc', array('class'=>'btn btn-color', 'id'=>'')) !!}</a>
                
               <a href="{{url('parner/profile/transporter')}}">  {!! Form::button('Transporter', array('class'=>'btn btn-color', 'id'=>'')) !!}</a>
                 </div>
            <?php } ?>

        </div>
      </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
$('.edit_btn').click(function(){
    $('._me_dis').toggle();
    $('._me_input').toggle();
});

$('#prf_edt_frm').validate({
    rules: {
        first_name:{
            required : true,
        },
        last_name:{
            required : true,
        },
        mobile_number:{
            required : true,
        },
        street:{
            required : true,
        },
        city:{
            required : true,
        },
        location:{
            required : true,
        },
        pincode:{
            required : true,
        },
        country:{
            required : true,
        },
       
    },
    messages: {
        first_name:{
            required : "Enter first name"
        },
        last_name:{
            required : "Enter last name"
        },
        mobile_number:{
            required : "Select mobile number"
        },
        street:{
            required : "Select street"
        },
        city:{
            required : "Enter city"
        },
        location:{
            required : "Enter location"
        },
        pin_code:{
            required : "Enter pin code"
        },
        country :{
            required : "Enter country"
        }
    }

});

$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    $(document).on('submit', '#prf_edt_frm', function(e) {
        e.preventDefault();
        $('#alertmsg').html('');
        var ajax_url = "{{ url('user/profile/edit') }}";
        $.ajax ({
            type: "POST",
            url: ajax_url,
            cache: false,
            data: $("#prf_edt_frm").serialize(),
            dataType: "json",
            success: function(msg) {
                var alertmsg = '';
                if(msg.id > 0){
                    $('.usrnm').html(msg.first_name + ' ' +  msg.last_name);
                    var fields = ['first_name', "last_name", "street", "city", "location", "pincode", "pincode", "country"];
                    fields.forEach(function(field) {
                        $('#' + field + '_spn').html(msg[field]);
                    });
                    alertmsg += '<div class="alert alert-success">';
                    alertmsg += 'Profile is updated successfully.';
                }
                else if(msg == 0){
                    alertmsg += '<div class="alert alert-danger">';
                    alertmsg += 'Error: Profile update failed.';
                }
                else{
                    alertmsg += '<div class="alert alert-danger">';
                    var obj = JSON.parse(JSON.stringify(msg));
                    $.each(obj, function(key, value) {
                        alertmsg += value + '<br>';
                    }); 
                }
                alertmsg += '</div>';
                $("html, body").animate({ scrollTop: 0 }, "slow");
                $('#alertmsg').html(alertmsg);
            },
            error : function(msg, status) {
                alert(msg+status);
            }
        });
    });
});
</script>
@endsection
