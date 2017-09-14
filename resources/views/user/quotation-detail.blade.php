@extends('layouts.user-dashboard-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')
<div class="col-md-8">
    <div class="_dash_rft">
        <h3>Quotation Offer Details</h3>
        <br>
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
                <button class="btn btn-color">Accept Offer</button>
                <button class="btn btn-color">Reject Offer</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')

@endsection

