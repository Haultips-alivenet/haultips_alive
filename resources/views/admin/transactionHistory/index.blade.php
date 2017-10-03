@extends('layouts.admin-layout')
@section('title')
    Admin Panel | Haultips
@endsection

@section('body')
   <div id="page-wrapper">
        <div class="graphs">
            <h3 class="blank1">Transaction History</h3>
                <div class="xs tabls">
                    <div class="panel panel-warning" >
                        
                                {!! Form::open(array('url'=>'admin/transaction/history','id'=>'menu','method'=>'get')) !!}
                                    <div class="row">  
                                        <div class="form-group col-md-2 grid_box1">
                                            {!! Form::text('name',Input::get('name'),['class'=>"form-control",'placeholder'=>'Name']) !!}
                                        </div>
                                       
                                        <div class="form-group col-md-2 grid_box1">
                                            {!! Form::text('Order_Category',Input::get('Order_Category'),['class'=>"form-control",'placeholder'=>'Order Category']) !!}
                                        </div>
                                        <div class="form-group col-md-2 grid_box1">
                                            {!! Form::text('mobile',Input::get('mobile'),['class'=>"form-control",'placeholder'=>'Mobile']) !!}
                                        </div>
                                        <div class="form-group col-md-2 grid_box1">
                                            {!! Form::text('transaction_id',Input::get('transaction_id'),['class'=>"form-control",'placeholder'=>'Transaction Id']) !!}
                                        </div>
                                         <div class="form-group col-md-2 grid_box1">
                                            {!! Form::text('Payment_Status',Input::get('Payment_Status'),['class'=>"form-control",'placeholder'=>'Payment Status']) !!}
                                        </div>                                      
                                        <div class="form-group col-sm-2">
                                            {!! Form::submit('Search',['class'=>"btn btn-success"]) !!}
                                            <a href="{{ url('admin/transaction/history') }}" class="btn btn-success" title="Refresh"><i class="fa fa-refresh"></i></a>                                            
                                        </div>                                        
                                    </div>
                                    {!! Form::close() !!}
                        
                        <div class="panel-heading">
                            <h2>Users List</h2>
                            <div class="panel-ctrls"><span class="button-icon has-bg"><i class="ti ti-angle-down"></i></span></div>
                        </div>
                        @if(Session::has('success'))
                            <div class="alert alert-success">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                {{Session::get('success')}}
                            </div>
                        @endif
                        <div class="panel-body no-padding" style="display: block;">
                            <table class="table table-striped">
                                <thead>
                                    <tr class="warning">
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Order Category</th>
                                        <th>Delivery Title</th>
                                        <th>Amount</th>
                                        <th>Payment Status</th>
                                        <th>Payment Type</th>
                                        <th>Transaction ID</th>
                                        <th>Payment Date</th>
                                        
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($users)>0)
                                    <?php $i=$page['from']; ?>
                                        @foreach($users as $user)
                                            <tr>
                                                <td><?= $i++ ?></td>
                                                <td>{{$user->first_name.' '.$user->last_name}}</td>
                                                <td>{{$user->mobile_number}}</td>
                                                <td>{{$user->category_name}}</td>
                                                <td>{!! App\ShippingDetail::getDeliveryName($user->table_name,$user->shipmentId) !!}</td>
                                                <td>{{$user->amount}}</td>
                                                <td>{{$user->status}}</td>
                                                <td>{{$user->method}}</td>
                                                <td>{{$user->transaction_id}}</td>
                                                <td>{{date('d-m-Y',strtotime($user->created_at))}}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr><td><i>No User Found</i></td></tr>
                                    @endif
                                </tbody>
                            </table>
                            <?php  echo $users->render();  ?>                                        
                        </div>
                    </div>						
                </div>
        </div>
</div>
@endsection

