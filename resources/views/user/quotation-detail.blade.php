@extends('layouts.user-dashboard-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')
<div class="col-md-8">
    <div class="_dash_rft">
        <h3>Quotation Offer Details</h3>
        <br>
        @if(Session::has('alert_msg'))
            <div class="alert alert-{{ Session::get('alert_type') }}">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {{ Session::get('alert_msg') }}
            </div>
        @endif
        <div class="_qu_list-de col-md-12">
            <div class="text-center">
                <div class="_price_cir">
                    <i class="fa fa-inr"></i> <strong>{{ $offerData->price }}</strong>
                </div>
            </div>
            <br>
            <p class="text-center">Price information</p>

            <h4>Other Information</h4>
            <ul>
                <li class="_w60">The offer is vailid till :</li>
                <li class="_w40">{{ $offerData->validTill }}</li>
                <li class="_w60">Developed :</li>
                <li class="_w40">{{ $offerData->developed }}</li>
                <li class="_w60">Status :</li>
                <li class="_w40">{{ $offerData->status }}</li>
            </ul>
                
            <div class="clearfix"></div>
            <br>
               
            <div class="form-group _re_all_de text-center"> 
                @if($offerData->quote_status <> 1)
                <button class="btn btn-color" onclick="quoteStsChange(1);">Accept Offer</button>
                @endif
                @if($offerData->quote_status <> 2)
                <button class="btn btn-color" onclick="quoteStsChange(2);">Reject Offer</button>
                @endif
                <button class="btn btn-color" onclick="window.location.href='{{ url("user/all-quotation/" . $offerData->shippingId) }}';">Go Back</button>
            </div>

            {!! Form::open(array('url'=>'user/quotation-offer/' . $offerData->quoteId, 'id'=>'ch_pass_form')) !!}
                {!! Form::hidden('quot_sts', '', array('id' => 'quot_sts')) !!}
            {!! Form::close() !!}

        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
function quoteStsChange(sts){
    $('#quot_sts').val(sts);
    $('#ch_pass_form').submit();
}   
</script>
@endsection

