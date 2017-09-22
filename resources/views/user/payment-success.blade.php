@extends('layouts.user-dashboard-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')
<div class="col-md-8">
    <div class="_dash_rft">
        <h3>Your transaction is successfull!</h3>
        <br>
        <strong>Transaction No:</strong> {{ $txnid }}<br>
        <strong>Amount:</strong> {{ $amount }}<br>
        
    </div>
</div>
@endsection

@section('script')

@endsection

