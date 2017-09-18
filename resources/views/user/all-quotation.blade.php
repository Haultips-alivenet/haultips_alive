@extends('layouts.user-dashboard-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')
<div class="col-md-8">
 <div class="_dash_rft">
    <h3>Quotation Offer</h3>
    <br>
    <div class="_qu_list">
      <ul>
      @foreach($quoteDetails as $quoteDetail)
        <li>
            <div class="_qu_li_lft">
                <p><strong>Carreir</strong> :{{ $quoteDetail->carrier }}</p>
                <p style="color:#999">Haultips pvt ltd</p>
            </div>
            <div class="_qu_li_lrt">
                <span><a href="{{ url("user/quotation-offer/" . $quoteDetail->quoteId) }}"><i class="fa fa-gavel" aria-hidden="true"></i></a></span>
                <p><i class="fa fa-inr" aria-hidden="true"></i> {{ $quoteDetail->price }}<br><span>{{ $quoteDetail->quote_status }}</span></p>
            </div>
        </li>
      @endforeach
      </ul>
    </div>
  </div>
</div>
@endsection

@section('script')

@endsection

