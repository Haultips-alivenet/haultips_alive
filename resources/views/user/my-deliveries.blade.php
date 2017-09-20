@extends('layouts.user-dashboard-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')
<div class="col-md-8">
   <div class="_dash_rft">
    <h3>My Deliveries({{ $st_label }}) </h3>
    <br>
    <table class="table table-striped table-hover _del_img">
        <thead>
            <tr>
                <th style="width: 15%;">Shipment</th>
                <th style="width: 35%;">Title</th>
                <th style="width: 20%;">Amount</th>
                <th style="width: 15%;">Status</th>
                <th style="width: 15%;">Date</th>
            </tr>
        </thead>
        <tbody>
        @if(count($shippings))
          @foreach($shippings as $shipping)
            <tr style="cursor: pointer;" onclick="window.location.href='{{ url("user/delivery-detail/" . $shipping->id) }}';">
                 <td><span><img src="{{ $shipping->image }}" alt=""></span></td>
                 <td>{{ $shipping->title }}</td>
                 <td>INR {{ $shipping->price }}</td>
                 <td>{{ ($shipping->payment_status == 1) ? 'Paid' : 'Unpaid' }}</td>
                 <td>{{ $shipping->postDate }}</td>
             </tr>
          @endforeach
        @else
          <tr><td colspan="5">No Record Found</td></tr>
        @endif
        </tbody>
    </table>
  </div>
</div>
@endsection

@section('script')

@endsection

