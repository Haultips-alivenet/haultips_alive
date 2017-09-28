@extends('layouts.admin-layout')
@section('title')
    Admin Panel | Haultips
@endsection

@section('body')
<?php //print_r($shipping_quotes); die;

?>
<div id="page-wrapper">
        <div class="graphs">
<div class="xs tabls">
                    <div class="panel panel-warning">
                        
                        <div class="panel-heading">
                            <h2>Cash on Delivery Payments</h2>
                            <div class="panel-ctrls"><span class="button-icon has-bg"><i class="ti ti-angle-down"></i></span></div>
                        </div>
                                                <div class="panel-body no-padding" style="display: block;">
                            <table class="table" style="align:left-250px;">
                                <thead align="center">
                                    <tr class="">
                                       
                                     <tr class="">    <td>Cash on Delivery</td> <td>{!! App\ShippingDetail::getDeliveryName($shipping_quotes->table_name,$shipping_quotes->id) !!}</td> </tr>
                                     <tr class="">    <td>Amount </td> <td>{{$shipping_quotes->quote_price}}</td></tr>
                                      <tr class="">   <td>Payment Status</td> <td>Cash on Delivery</td></tr>
                                   
                                </thead>
                                    <tr><td colspan="2" align="center"><a href="{{URL :: asset('shipment/cod/payment/'.$shipping_quotes->id.'_'.$category_id)}}"><button class="button-icon">Pay</button></a></td></tr>                                
                            </table>
                                                                    
                        </div>
                    </div>						
                </div>
            </div>						
                </div>
@endsection



