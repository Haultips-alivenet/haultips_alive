@extends('layouts.admin-layout')
@section('title')
    Admin Panel | Haultips
@endsection

@section('body')
<?php //print_r($category); ?>
    <div id="page-wrapper">
        <div class="graphs">
            <h3 class="blank1">Cost Estimation</h3>
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
                
                     <?php if($costupdate) { ?>
                        <div class="tab-pane active" id="horizontal-form">
                      
                          {!! Form::model($costupdate,['route'=>['admin.cost.update',$costupdate->id],'files'=>true, 'method'=>'patch','class'=>'form-horizontal'])  !!}
                            
                          
                             
                           <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Cost Type</label>
                                <div class="col-sm-8">
                                    <select name="costtypeupdate" id="costtypeupdate" class="form-control select2" >
                                        <option value=""  >Select Cost Type</option> 
                                        <option value="1-KM"  {{$costupdate->cost_type=='1-KM' ? 'selected' : ''}} >1-KM</option>
                                        <option value="1-HOUR" {{$costupdate->cost_type=='1-HOUR' ? 'selected' : ''}} >1-HOUR</option>
                                       
                                    </select>
                                </div> 
                            </div>
                            
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Title</label>
                                <div class="col-sm-8">
                                     {!! Form :: text('titleupdate',$costupdate->title,['class'=>'form-control1', 'id'=>'titleupdate'])  !!}
                                </div>  
                               
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Price</label>
                                <div class="col-sm-8">
                                     {!! Form :: text('priceupdate',$costupdate->price,['class'=>'form-control1', 'id'=>'priceupdate'])  !!}
                                </div>  
                               
                            </div>
                           
                            <div class="col-sm-8 col-sm-offset-2">
                            {!! Form :: submit("Update",["class"=>"btn-success btn",'id'=>'truckupdate']) !!}
                               <a href="{{ url('admin/cost/create') }}" class="btn btn-success" title="Refresh"><i class="fa fa-refresh"></i></a>
                            </div>
                        {!! Form::close() !!}
					
                    </div>
                     <?php } else { ?>
                    <div class="tab-pane active" id="horizontal-form">
                        {!! Form::open(array('url'=>'admin/cost-Save','class'=>'form-horizontal','id'=>'costestimation','files' => true)) !!}
                            
                            <div id="msgStatus"></div>
                             
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Cost Type</label>
                                <div class="col-sm-8">
                                    <select name="costtype" id="costtype" class="form-control select2" >
                                        <option value=""  >Select Cost Type</option> 
                                        <option value="1-KM"  >1-KM</option>
                                        <option value="1-HOUR"  >1-HOUR</option>
                                       
                                    </select>
                                </div> 
                            </div>
                            
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Title</label>
                                <div class="col-sm-8">
                                     {!! Form :: text('title','',['class'=>'form-control1', 'id'=>'truckcapacity'])  !!}
                                </div>  
                               
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Price</label>
                                <div class="col-sm-8">
                                     {!! Form :: text('price','',['class'=>'form-control1', 'id'=>'price'])  !!}
                                </div>  
                               
                            </div>
                           
                            <div class="col-sm-8 col-sm-offset-2">
                            {!! Form :: submit("Save",["class"=>"btn-success btn",'id'=>'trucklen']) !!}
                             <a href="{{ url('admin/cost/create') }}" class="btn btn-success" title="Refresh"><i class="fa fa-refresh"></i></a>
                            </div>
                              
                        {!! Form::close() !!}
					
                    </div>
                     <?php } ?>
                </div>
            <br><br>
                 <div class="xs tabls">
                    <div class="panel panel-warning" >
                        
                               
                        
                        <div class="panel-heading">
                            <h2>Cost Estimation List</h2>
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
                                        <th>Cost Type</th>
                                        <th>Title</th>
                                         <th>Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($cost)>0)
                                    <?php $i=$page['from']; ?>
                                        @foreach($cost as $value)
                                            <tr>
                                                <td><?= $i++ ?></td>
                                                <td>{{$value->cost_type}}</td>
                                                <td>{{$value->title}}</td>
                                                <td>{{$value->price}}</td>
                                                <td>
                                                    <a href="{{URL :: asset('admin/cost/'.$value->id)}}/update" class="btn btn-success" title='edit'><i class="fa fa-edit"></i></a>
                                                    <a onclick="return confirm('Do you Want to Delete Cost Estimation?');return false;" href="{{URL :: asset('admin/cost/'.$value->id)}}/delete" class="btn btn-success" title='delete'><i class="fa fa-trash-o"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr><td><i>No User Found</i></td></tr>
                                    @endif
                                </tbody>
                            </table>
                            <?php  echo $cost->render();  ?>                                        
                        </div>
                    </div>						
                </div>
            
            
            
            
            
        </div>
    </div>
@endsection

@section('script')
<script>
  
   
$('#costestimation').validate({

            rules: {
                costtype:{
                    required : true
                    
                },
                title:{
                    required : true
                   
                },
                price:{
                     required : true
                }
                
            },

            messages: {
                
                costtype :{
                    required : "Select Cost Type"
                    
                },
                 title :{
                    required : "Plese Enter Title",
                    minlength : 'Truck Length should be 2 digits'
                },
                price :{
                    required : "Plese Enter Price",
                    minlength : 'Truck Capacity should be 2 digits'
                }
               
            }
            
           

        });
</script>
@endsection