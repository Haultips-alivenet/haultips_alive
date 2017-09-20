@extends('layouts.user-dashboard-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')
<div class="col-md-8">
     <div class="_dash_lft _dash_m">
         <h3>My Offer Details</h3>

         <div class="_dash_m_inner">
            <h3>Shipping Customent Details</h3>

            <div class="_dash_full">
                <div class="_dash_full_lft">Customer</div>
                <div class="_dash_full_lrt">: {{ $offer->customer }}</div>
            </div>

            <div class="_dash_full">
                <div class="_dash_full_lft">Email</div>
                <div class="_dash_full_lrt">: {{ $offer->email }}</div>
            </div>

            <div class="_dash_full">
                <div class="_dash_full_lft">Mobile No</div>
                <div class="_dash_full_lrt">: {{ $offer->mobileNumber }}</div>
            </div>

            <div class="_dash_full">
                <div class="_dash_full_lft">Company Name</div>
                <div class="_dash_full_lrt">: Haultips</div>
            </div>



         </div>
         <div class="_dash_m_inner">
             <h3>Other information</h3>
            <div class="_dash_full">
                <div class="_dash_full_lft">The offer is valid till</div>
                <div class="_dash_full_lrt">: {{ $offer->validTill }}</div>
            </div>

            <div class="_dash_full">
                <div class="_dash_full_lft">Approve/Reject</div>
                <div class="_dash_full_lrt">: {{ $offer->status }}</div>
            </div>

            <div class="_dash_full">
                <div class="_dash_full_lft">Developed</div>
                <div class="_dash_full_lrt">: {{ $offer->developed }}</div>
            </div>

         </div>


         <div class="_dash_m_inner">
             <h3>Freight Information</h3>

            <div class="_dash_full">
                <div class="_dash_full_lft">Category Name</div>
                <div class="_dash_full_lrt">: {{ $offer->category }}</div>
            </div>
            <div class="_dash_full">
                <div class="_dash_full_lft">Sub Category Name</div>
                <div class="_dash_full_lrt">: {{ $offer->subcategory }}</div>
            </div>

            <div class="_dash_full">
                <div class="_dash_full_lft">Order No</div>
                <div class="_dash_full_lrt">: {{ $offer->orderNo }}</div>
            </div>

            <div class="_dash_full">
                <div class="_dash_full_lft">Title</div>
                <div class="_dash_full_lrt">: {{ $offer->title }}</div>
            </div>

            <div class="_dash_full">
                <div class="_dash_full_lft">Take Away</div>
                <div class="_dash_full_lrt">: {{ $offer->takeAway }}</div>
            </div>

            <div class="_dash_full">
                <div class="_dash_full_lft">Delivery To</div>
                <div class="_dash_full_lrt">: {{ $offer->delivery_address }}</div>
            </div>

         </div>
         
     </div>   
</div>
@endsection

@section('script')

@endsection

