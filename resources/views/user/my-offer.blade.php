@extends('layouts.user-dashboard-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')
<div class="col-md-8">
     <div class="_dash_lft _dash_m">
        <h3>My Offers</h3>
        <hr>
       @if(count($offers))
         @foreach($offers as $key=>$offer)
         <ul class="event-list">
             <li>
                 <img src="{{ $offer->image }}" alt="" height="119" onclick="location.href='{{ url('user/my-offer/' . $offer->id) }}'" style="cursor: pointer;">
                 <div class="info">
                     <h2 onclick="location.href='{{ url('user/my-offer/' . $offer->id) }}'" style="cursor: pointer;">{{ $offer->title }}</h2>
                     <p><strong>Category :</strong> {{ $offer->category }}</p>
                     <p><strong>Bid Amount :</strong> INR {{ $offer->quotePrice }}</p>
                 </div>
                 <div class="choose_item">
                     <ul>
                         <li><a href="#" class="btn ">{{ $offer->status }}</a></li>
                        @if($offer->edit_bid)
                        <form id="bid_form_{{ $key }}" method="post" action="{{ url('bid/offer/' . $offer->shipping_id) }}">
                            {{ csrf_field() }}
                         <li><a href="javascript:void(0)" onclick="formsubmit('bid_form_{{ $key }}')" class="btn ">Edit Bid</a></li>
                         <input type="hidden" name="quote_id" value="{{ $offer->quoteId }}">
                        </form>
                        @endif
                     </ul>
                 </div>
             </li>
         </ul>
         @endforeach
        @else
            <h4 class="text-center">No Offers Found!</h4>
        @endif
        
        {!! $offers->render() !!}
     </div>   
     
        
</div>
@endsection

@section('script')
<script type="text/javascript">
function formsubmit(elmid){
    $('#'+elmid).submit();
}
</script>
@endsection

