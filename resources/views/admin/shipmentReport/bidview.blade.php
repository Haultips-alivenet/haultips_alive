@extends('layouts.admin-layout')
@section('title')
    Admin Panel | Haultips
@endsection

@section('body')
<?php //print_r($tables_value);
error_reporting(0);
?>
 <div id="page-wrapper">
     <input type="button" value="Back" onclick="history.back()">
    <div class="graphs">
 <table class="table table-bordered"> 
     <thead>
        <tr class="warning">
           
            <th>Name</th>
            <th>Quote Price</th>
            <th>Status</th>
           
        </tr>
    </thead>
     <tbody>
         @if($shipping_quotes)
         @foreach($shipping_quotes as $value)
         <tr style="border:none;">
         <td>{{$value->first_name.' '.$value->last_name}}</td>
         <td>{{$value->quote_price}}</td>
         
         <td><?php if($value->quote_status == 0) { echo "Pending"; } else if($value->quote_status == 1) { echo "Accepted"; } else if($value->quote_status == 2) { echo "Rejected"; } else { echo "Others"; }?></td>
         </tr>
     @endforeach
     
     @else
     <tr>
          <tr><td colspan="3"><i>No Bids Found</i></td></tr>
        
     </tr> 
    @endif
     
 </tbody> 
 </table>
    </div>
</div>
@endsection





