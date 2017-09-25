@extends('layouts.user-dashboard-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')
<div class="col-md-8">
    <div class="_dash_rft">
        <h3>Accept Quotation Offer</h3>
        <br>
        
               
        <div class="form-group _re_all_de text-center"> 
            @if($offerData->quote_status <> 1)
            <a href="javascript:void(0);" class="btn btn-color" onclick="cod()">Cash On Delivery</a>
            <a href="javascript:void(0);" class="btn btn-color" onclick="pay()">Online Payment</a>
            @endif
        </div>

        {!! Form::open(array('url'=>'user/payment', 'id'=>'pay_form')) !!}
            {!! Form::hidden('quot_id', $offerData->quoteId, array('id' => 'quot_id')) !!}
            {!! Form::hidden('amount', $offerData->price, array('id' => 'quot_id')) !!}
        {!! Form::close() !!}

        {!! Form::open(array('url'=>'user/quotation-offer/accept/cod', 'id'=>'cod_form')) !!}
            {!! Form::hidden('quot_id', $offerData->quoteId, array('id' => 'quot_id')) !!}
        {!! Form::close() !!}

    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
function pay(){
    $('#pay_form').submit();
}   
function cod(){
    $('#cod_form').submit();
}   
</script>
@endsection

