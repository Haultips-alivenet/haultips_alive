@extends('layouts.admin-layout')
@section('title')
    Admin Panel | Haultips
@endsection

@section('body')
<?php //print_r($category); ?>
    <div id="page-wrapper">
        <div class="graphs">
            <h3 class="blank1"><?php if($id==1) { echo "Truck Booking"; } else if($id==2) { echo "Packers & Movers"; } else if($id==3) { echo "Vehicle Shifting"; } else if($id==4) { echo "Part Load"; }?></h3>
                <div class="tab-content">
                    {{--    Error Display--}}
                        @if($errors->any())
                        <ul class="alert">
                            @foreach($errors->all() as $error)
                            <li style="color:red;"><b>{{ $error }}</b></li>
                            @endforeach
                        </ul>
                        @endif
                    {{--    Error Display ends--}}
                
                     
                    <div class="tab-pane active" id="horizontal-form">
                        {!! Form::open(array('url'=>'shipment/reportList/'.$id,'class'=>'form-horizontal','id'=>'newCategory','files' => true)) !!}
                            
                            <div id="msgStatus"></div>
                            
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Category Type</label>
                                <div class="col-sm-8">
                                    <select name="category" id="category" class="form-control select2" >
                                        <option value=""  >Select Category</option> 
                                        <?php foreach($categoryname as $value) { ?>
                                        <option value="<?php echo $value->id; ?>" {{Input::get('category')==$value->id ? 'selected' : ''}}><?php echo $value->category_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div> 
                            </div>
                           
                            <div class="col-sm-8 col-sm-offset-2">
                            {!! Form :: submit("Search",["class"=>"btn-success btn",'id'=>'trucklen']) !!}
                             <a href="{{ url('shipment/'.$id.'/report') }}" class="btn btn-success" title="Refresh"><i class="fa fa-refresh"></i></a>
                            </div>
                              
                        {!! Form::close() !!}
					
                    </div>
                    
                </div>
            <br><br>
                 <div class="xs tabls">
                    <div class="panel panel-warning" >
                        
                               
                        
                        <div class="panel-heading">
                            <h2>Shipping Details</h2>
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
                                        <th>Category</th>
                                        <th>Sub Category</th>
                                        <th>Estimated Price</th>
                                         <th>Payments</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($shiping_details)>0)
                                    <?php $i=@$page['from']; ?>
                                        @foreach(@$shiping_details as $value) 
                                            <tr>
                                                <?php if($value->status==0){ $status="Inactive"; } else if($value->status==1) { $status="Active"; } else if($value->status==2) { $status="Processing"; } else if($value->status==3) { $status="Complete"; } ?>
                                                <td><?= $i++ ?></td>
                                                <td>{{$value->categoty_name}}</td>
                                                <td>{{$value->subcategory_name}}</td>
                                                <td>{{$value->estimated_price}}</td>
                                                <td>{{$value->method}}</td>
                                                <td>{{$status}}</td>
                                                <td><a href="{{URL :: asset('shipment/detailsReport/'.$value->id)}}"  class="btn btn-xs btn-link">View</a></td>
                                              
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr><td><i>No User Found</i></td></tr>
                                    @endif
                                </tbody>
                            </table>
                            <?php if(count($shiping_details)>0) { echo @$shiping_details->render(); } ?>                                        
                        </div>
                    </div>						
                </div>
            
            
            
            
            
        </div>
    </div>
@endsection

@section('script')
<script>
      
$('#newCategory').validate({

            rules: {
                category:{
                    required : true
                    
                }
                
            },

            messages: {
                
                category :{
                    required : "Select Category Type"
                    
                }
               
            }
            
           

        });
</script>
@endsection