@extends('layouts.admin-layout')
@section('title')
    Admin Panel | Haultips
@endsection

@section('body')
<?php //print_r($category); ?>
    <div id="page-wrapper">
        <div class="graphs">
            <h3 class="blank1">Truck Capacity</h3>
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
                
                     <?php if($truckupdate) { ?>
                        <div class="tab-pane active" id="horizontal-form">
                      
                          {!! Form::model($truckupdate,['route'=>['admin.truckcapacity.update',$truckupdate->id],'files'=>true, 'method'=>'patch','class'=>'form-horizontal','id'=>'newCategoryupdate'])  !!}
                            
                          
                             
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Truck Type</label>
                                <div class="col-sm-8">
                                    <select name="trucktypeupdate" id="trucktypeupdate" class="form-control select2" onchange="gettrucklengthupdate(this.value);">
                                        <option value=""  >Select Truck Type</option>                                                
                                       <?php foreach($categoryname as $value) { ?>
                                        <option   value="{{$value->id}}"  {{$truckupdate->truck_type_id==$value->id ? 'selected' : ''}}>{{$value->category_name}}</option>
                                       <?php } ?>
                                    </select>
                                </div> 
                            </div>
                           <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Truck Length</label>
                                <div class="col-sm-8">
                                    <select name="trucklengthupdate" id="trucklengthupdate" class="form-control select2">
                                        <option value=""  >Select Truck Length</option>                                                
                                        <?php foreach($truck_lengths as $value) { ?>
                                        <option   value="{{$value->id}}"  {{$truckupdate->truck_length_id==$value->id ? 'selected' : ''}}>{{$value->truck_length}}</option>
                                       <?php } ?>
                                    </select>
                                </div> 
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Truck Capacity</label>
                                <div class="col-sm-8">
                                     {!! Form :: text('truckcapacityupdate',$truckupdate->truck_capacity,['class'=>'form-control1', 'id'=>'truckcapacityupdate'])  !!}
                                </div>  
                               
                            </div>
                           
                            <div class="col-sm-8 col-sm-offset-2">
                            {!! Form :: submit("Update",["class"=>"btn-success btn",'id'=>'truckupdate']) !!}
                               <a href="{{ url('admin/truckcapacity/create') }}" class="btn btn-success" title="Refresh"><i class="fa fa-refresh"></i></a>
                            </div>
                        {!! Form::close() !!}
					
                    </div>
                     <?php } else { ?>
                    <div class="tab-pane active" id="horizontal-form">
                        {!! Form::open(array('url'=>'admin/truckcapacity-Save','class'=>'form-horizontal','id'=>'newCategory','files' => true)) !!}
                            
                            <div id="msgStatus"></div>
                             <div class="form-group" style="display:none">
                                     {!! Form :: text('userType','3',['class'=>'form-control1', 'id'=>'userType'])  !!}                                                              
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Truck Type</label>
                                <div class="col-sm-8">
                                    <select name="trucktype" id="status" class="form-control select2" onchange="gettrucklength(this.value);">
                                        <option value=""  >Select Truck Type</option>                                                
                                        <?php foreach($categoryname as $value) { ?>
                                        <option   value="{{$value->id}}"  {{Input::get('status')=='1' ? 'selected' : ''}}>{{$value->category_name}}</option>
                                       <?php } ?>
                                    </select>
                                </div> 
                            </div>
                             <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Truck Length</label>
                                <div class="col-sm-8">
                                    <select name="trucklength" id="trucklength" class="form-control select2">
                                        <option value=""  >Select Truck Length</option>                                                
                                         
                                    </select>
                                </div> 
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Truck Capacity</label>
                                <div class="col-sm-8">
                                     {!! Form :: text('truckcapacity','',['class'=>'form-control1', 'id'=>'truckcapacity'])  !!}
                                </div>  
                               
                            </div>
                           
                            <div class="col-sm-8 col-sm-offset-2">
                            {!! Form :: submit("Save",["class"=>"btn-success btn",'id'=>'trucklen']) !!}
                             <a href="{{ url('admin/truckcapacity/create') }}" class="btn btn-success" title="Refresh"><i class="fa fa-refresh"></i></a>
                            </div>
                              
                        {!! Form::close() !!}
					
                    </div>
                     <?php } ?>
                </div>
            <br><br>
                 <div class="xs tabls">
                    <div class="panel panel-warning" >
                        
                             {!! Form::open(array('url'=>'admin/truckcapacity-search','id'=>'shiplist','method'=>'post')) !!}
                                    <div class="row">  
                                        <div class="form-group col-md-4 grid_box1">
                                         <select name="trucktype_search" id="status" class="form-control select2" onchange="gettrucklength_search(this.value);">
                                            <option value=""  >Select Truck Type</option>                                                
                                            <?php foreach($categoryname as $value) { ?>
                                            <option   value="{{$value->id}}"  {{Input::get('trucktype_search')==$value->id ? 'selected' : ''}}>{{$value->category_name}}</option>
                                           <?php } ?>
                                        </select>
                                        </div>
                                       
                                        <div class="form-group col-md-4 grid_box1">
                                              
                                            <input type="text" name="trucklength_search" id="trucklength_search" value="{{Input::get('trucklength_search')}}" class="form-control" placeholder="Truck Length">
                                        </div>
                                                                     
                                        <div class="form-group col-sm-4">
                                            {!! Form::submit('Search',['class'=>"btn btn-success"]) !!}
                                            <a href="{{ url('admin/truckcapacity/create') }}" class="btn btn-success" title="Refresh"><i class="fa fa-refresh"></i></a>                                            
                                        </div>                                        
                                    </div>
                                    {!! Form::close() !!}   
                        
                        <div class="panel-heading">
                            <h2>Category List</h2>
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
                                        <th>Track Type</th>
                                        <th>Truck Length</th>
                                         <th>Truck Capacity</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($truckcapacity)>0)
                                    <?php $i=$page['from']; ?>
                                        @foreach($truckcapacity as $value)
                                            <tr>
                                                <td><?= $i++ ?></td>
                                                <td>{{$value->category_name}}</td>
                                                <td>{{$value->truck_length}}</td>
                                                <td>{{$value->truck_capacity}}</td>
                                              
                                               
                                                <td>
                                                    <a href="{{URL :: asset('admin/truckcapacity/'.$value->id)}}/update" class="btn btn-success" title='edit'><i class="fa fa-edit"></i></a>
                                                    <a onclick="return confirm('Do you Want to Delete Capacity?');return false;" href="{{URL :: asset('admin/truckcapacity/'.$value->id)}}/delete" class="btn btn-success" title='delete'><i class="fa fa-trash-o"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr><td><i>No User Found</i></td></tr>
                                    @endif
                                </tbody>
                            </table>
                            <?php  echo $truckcapacity->render();  ?>                                        
                        </div>
                    </div>						
                </div>
            
            
            
            
            
        </div>
    </div>
@endsection

@section('script')
<script>
     $.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /^[a-z0-9\-\s]+$/i.test(value);
    }, "Length must contain only letters, numbers, or dashes.");

    function gettrucklength(id){
         var i;
         var _options ="";
           $.ajax({ 
        type: 'get',
        url: '{{url('gettrucklength')}}',
        data: 'id='+id,
        dataType: 'json',
        //cache: false,

        success: function(data) {
        // data = jQuery.parseJSON(data);
        console.log(data.truck_lengths);
       
        _options =('<option value="">Select Truck Length</option>');
        for(i=0; i<data.truck_lengths.length; i++){
           _options +=('<option value="'+ data.truck_lengths[i].id+'">'+ data.truck_lengths[i].truck_length +'</option>');
        }
        $('#trucklength').html(_options);
        }

   });
    }
    function gettrucklengthupdate(id){
         var i;
         var _options ="";
           $.ajax({ 
        type: 'get',
        url: '{{url('gettrucklength')}}',
        data: 'id='+id,
        dataType: 'json',
        //cache: false,

        success: function(data) {
        // data = jQuery.parseJSON(data);
        console.log(data.truck_lengths);
       
        _options =('<option value="">Select Truck Length</option>');
        for(i=0; i<data.truck_lengths.length; i++){
           _options +=('<option value="'+ data.truck_lengths[i].id+'">'+ data.truck_lengths[i].truck_length +'</option>');
        }
        $('#trucklengthupdate').html(_options);
        }

   });
    }
    
    
    
$('#newCategory').validate({

            rules: {
                trucktype:{
                    required : true
                    
                },
                trucklength:{
                    required : true
                   
                },
                truckcapacity:{
                     required : true,
                     lettersonly: true
                }
                
            },

            messages: {
                
                trucktype :{
                    required : "Select Truck Type"
                    
                },
                 trucklength :{
                    required : "Select Truck Length",
                    minlength : 'Truck Length should be 2 digits'
                },
                truckcapacity :{
                    required : "Enter Truck Capacity",
                    minlength : 'Truck Capacity should be 2 digits'
                }
               
            }
            
           

        });
        
        $('#newCategoryupdate').validate({

            rules: {
                trucktypeupdate:{
                    required : true
                    
                },
                trucklengthupdate:{
                    required : true
                   
                },
                truckcapacityupdate:{
                     required : true,
                     lettersonly: true
                }
                
            },

            messages: {
                
                trucktypeupdate :{
                    required : "Select Truck Type"
                    
                },
                 trucklengthupdate :{
                    required : "Select Truck Length",
                    minlength : 'Truck Length should be 2 digits'
                },
                truckcapacityupdate :{
                    required : "Enter Truck Capacity",
                    minlength : 'Truck Capacity should be 2 digits'
                }
               
            }
            
           

        });
</script>
@endsection