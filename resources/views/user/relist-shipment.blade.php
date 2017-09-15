@extends('layouts.user-dashboard-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')
<div class="col-md-8">
  {!! Form::open(array('url'=>'user/relist-shipment/' . $shipPickDetail->shipping_id, 'id'=>'relist_ship_form')) !!}
  <div class="_dash_rft">
    <h3>Confirm Address</h3>
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
    <br>
    <div class="form-group row">
        <div class="col-md-6">
            <label for="">Pickup Location</label>
            {!! Form::text('pickup_address', $shipPickDetail->pickup_address, array('class'=>'form-control', 'id'=>'pickup_address')) !!}
        </div>
        <div class="col-md-6">
            <label for="">Drop Location</label>
            {!! Form::text('delivery_address', $shipDelivDetail->delivery_address, array('class'=>'form-control', 'id'=>'delivery_address')) !!}

        </div>
    </div>

    <div class="form-group row">
      <div class="col-md-6">
        <label for="">Pickup Date</label>
        <div class="input-group date datetimepicker8">
          {!! Form::text('pickup_date', $shipPickDetail->pickup_date, array('class'=>'form-control datetimepicker8', 'id'=>'pickup_date')) !!}
          <span class="input-group-addon" style="background: transparent; border-radius: 0;"> <img src="{!! url('public/user/img/date-time-icon.png') !!}" alt=""></span>
        </div>
      </div>

      <div class="col-md-6">
        <label for="">Delivery Date</label>
        <div class="input-group date datetimepicker9">
          {!! Form::text('delivery_date', $shipDelivDetail->delivery_date, array('class'=>'form-control datetimepicker9', 'id'=>'delivery_date')) !!}
          <span class="input-group-addon" style="background: transparent; border-radius: 0;"> <img src="{!! url('public/user/img/date-time-icon.png') !!}" alt=""></span>
        </div>
      </div>
      
    </div>

    <hr>
    <div class="form-group _re_all_de">
      {!! Form::submit('Continue', array('class'=>'btn btn-color', 'id'=>'rs_submit')) !!} 
    </div>

  </div>
  {!!Form::close()!!} 
</div>
@endsection

@section('script')
<script>
$(function () {

    $('.datetimepicker8').datetimepicker({
         format: 'Y-MM-DD'
    });

    $('.datetimepicker9').datetimepicker({
        useCurrent: false ,//Important! See issue #1075
         format: 'Y-MM-DD'
    });
    /*$(".datetimepicker8").on("dp.change", function (e) {
        $('.datetimepicker9').data("DateTimePicker").minDate(e.date);
    });
    $(".datetimepicker9").on("dp.change", function (e) {
        $('.datetimepicker8').data("DateTimePicker").maxDate(e.date);
    });*/
});

$('#relist_ship_form').validate({
    rules: {
        pickup_address:{
            required : true,
        },
        delivery_address:{
            required : true,
        },
        pickup_date:{
            required : true,
        },
        delivery_date:{
            required : true,
        },
       
    },
    messages: {
        pickup_address :{
            required : "Enter pickup address"
        },
        delivery_address :{
            required : "Enter delivery address"
        },
        pickup_date :{
            required : "Select pickup date"
        },
        delivery_date :{
            required : "Select delivery date"
        }
    }

});
</script>

@endsection

