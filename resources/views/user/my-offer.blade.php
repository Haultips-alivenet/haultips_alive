@extends('layouts.user-dashboard-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')
<div class="col-md-8">
     <div class="_dash_lft _dash_m">
        <h3>My Offer</h3>
        <hr>
       @if(count($offers))
         @foreach($offers as $offer)
         <ul class="event-list">
             <li>
                 <img src="{{ $offer->image }}" alt="" height="119">
                 <div class="info">
                     <h2>Test Garbage</h2>
                     <p><strong>Category :</strong> {{ $offer->category }}</p>
                     <p><strong>Offer Type :</strong> {{ $offer->category }}</p>
                 </div>
                 <div class="choose_item">
                     <ul>
                         <li><a href="#" class="btn ">{{ $offer->status }}</a></li>
                     </ul>
                 </div>
             </li>
         </ul>
         @endforeach
        @else
            <h4 class="text-center">No Offers Found!</h4>
        @endif

         
     </div>   
</div>
@endsection

@section('script')

@endsection

