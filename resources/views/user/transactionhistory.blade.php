@extends('layouts.user-dashboard-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')
<div class="col-md-8">
   <div class="_dash_rft">
      <h3>Transaction History</h3>
      <br>
      <table class="table table-striped table-hover">
         <thead>
            <tr>
               <th style="width: 5%;">No.</th>
               <th style="width: 15%;">Order ID</th>
               <th style="width: 20%;">Paid Amount</th>
               <th style="width: 10%;">Status</th>
               <th style="width: 20%;">Payment date</th>
            </tr>
         </thead>
         <tbody>
         @if(count($transaction_history))
            @foreach($transaction_history as $key=>$th)
              <tr>
                  <td>{{ $key+1 }}</td>
                  <td>#{{ $th->order_id }}</td>
                  <td>INR {{ $th->amount }}</td>
                  <td>{{ $th->status }}</td>
                  <td>{{ date('d-F-Y H:i:s', strtotime($th->created_at)) }}</td>
               </tr>
            @endforeach
         @else
            <tr><td colspan="5">No record found!</td></tr>
         @endif  
         </tbody>
      </table>
   </div>
</div>
@endsection
