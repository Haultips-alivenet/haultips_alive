@extends('layouts.user-dashboard-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')
<div class="col-md-8">
    <div class="_dash_rft">
        <h3>My Delivery Details</h3>
        <br>
        <div class="form-group row">
            <div class="col-md-4">
               <div class="_m_gr">
                    <img src="{{ $shippingDetail->image }}" alt="">
               </div>
            </div>

            <div class="col-md-7">
                <h3>{{ ucwords($shippingDetail->title) }}</h3>
            </div>

            <div class="clearfix"></div>
            <br>

            <div class="clearfix"></div>    
            <div class="col-md-3">
                <label for="">Pickup Address</label>
            </div>

            <div class="col-md-8">
                {{ $shippingDetail->pickup_address }}
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-3">
               <label for="">Category</label>
            </div>
            <div class="col-md-8">
                 {{ $shippingDetail->category_name }}
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-3">
               <label for="">Subcategory</label>
            </div>
            <div class="col-md-8">
                 {{ $shippingDetail->subcat_name }}
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-3">
               <label for="">Price</label>
            </div>
            <div class="col-md-8">
                INR {{ $shippingDetail->price }}
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-3">
               <label for="">Published</label>
            </div>
            <div class="col-md-8">
                {{ date('d-F-Y', strtotime($shippingDetail->published)) }}
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-3">
             <label for="">  Pickup Date</label>
            </div>
            <div class="col-md-8">
                {{ date('d-F-Y', strtotime($shippingDetail->pickup_date)) }}
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-3">
              <label for=""> Delivery Date</label>
            </div>
            <div class="col-md-8">
                {{ date('d-F-Y', strtotime($shippingDetail->delivery_date)) }}
            </div>
        </div>
        
        @if($shippingDetail->paymnentType)
        <div class="form-group row">
            <div class="col-md-3">
              <label for=""> Payment</label>
            </div>
            <div class="col-md-8">
                {{ $shippingDetail->paymnentType }}
            </div>
        </div>
        @endif

        <div class="form-group row">
            <div class="col-md-3">
              <label for=""> Payment Status</label>
            </div>
            <div class="col-md-8">
                {{ ($shippingDetail->payments_status == 1) ? 'Paid' : 'Unpaid' }}
            </div>
        </div>

        <hr>
        <div class="form-group _re_all_de">
        @if($quotation_count <= 0 && $sts <> 2)
            <button class="btn btn-color" onclick="window.location.href='{{ url("user/relist-shipment/" . $shippingDetail->id) }}';">Relist Shipment</button>
        @endif
        
        @if($sts <> 2)
            <button class="btn btn-color"  onclick="window.location.href='{{ url("user/all-quotation/" . $shippingDetail->id) }}';">All Quotations</button>
        @endif

        @if($quotation_count <= 0 && $sts <> 2)
            <button class="btn btn-color" onclick="window.location.href='{{ url("user/my-delivery-delete/" . $shippingDetail->id) }}';">Delete</button>
        @endif
        </div>
    </div>
</div>
@endsection

@section('script')

@endsection

