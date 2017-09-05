@extends('layouts.admin-layout')
@section('title')
    Admin Panel | Haultips
@endsection

@section('body')
<?php //print_r($category); ?>
    <div id="page-wrapper">
        <div class="graphs">
            <h3 class="blank1">Truck Length</h3>
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
                      
                          {!! Form::model($truckupdate,['route'=>['admin.trucklength.update',$truckupdate->id],'files'=>true, 'method'=>'patch','class'=>'form-horizontal'])  !!}
                            
                          
                             
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Truck Type</label>
                                <div class="col-sm-8">
                                    <select name="trucktypeupdate" id="status" class="form-control select2">
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
                                     {!! Form :: text('trucklengthupdate',$truckupdate->truck_length,['class'=>'form-control1', 'id'=>'trucklengthupdate'])  !!}
                                </div> 
                                
                            </div>
                           
                            <div class="col-sm-8 col-sm-offset-2">
                            {!! Form :: submit("Update",["class"=>"btn-success btn",'id'=>'truckupdate']) !!}
                            </div>
                        {!! Form::close() !!}
					
                    </div>
                     <?php } else { ?>
                    <div class="tab-pane active" id="horizontal-form">
                        {!! Form::open(array('url'=>'admin/trucklength-Save','class'=>'form-horizontal','id'=>'newCategory','files' => true)) !!}
                            
                            <div id="msgStatus"></div>
                             <div class="form-group" style="display:none">
                                     {!! Form :: text('userType','3',['class'=>'form-control1', 'id'=>'userType'])  !!}                                                              
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Truck Type</label>
                                <div class="col-sm-8">
                                    <select name="trucktype" id="trucktype" class="form-control select2">
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
                                     {!! Form :: text('trucklength','',['class'=>'form-control1', 'id'=>'trucklength'])  !!}
                                </div>                                
                            </div>
                           
                            <div class="col-sm-8 col-sm-offset-2">
                            {!! Form :: submit("Save",["class"=>"btn-success btn",'id'=>'trucklen']) !!}
                            </div>
                        {!! Form::close() !!}
					
                    </div>
                     <?php } ?>
                </div>
            <br><br>
                 <div class="xs tabls">
                    <div class="panel panel-warning" >
                        
                                {!! Form::open(array('url'=>'admin/trucklength/create','id'=>'menu','method'=>'get')) !!}
                                    <div class="row">  
                                        <div class="form-group col-md-4 grid_box1">
                                             <select name="trucktypesearch" id="status" class="form-control select2">
                                        <option value=""  >Select Truck Type</option>                                                
                                        <?php foreach($categoryname as $value) { ?>
                                        <option   value="{{$value->id}}"  {{Input::get('trucktypesearch')==$value->id ? 'selected' : ''}}>{{$value->category_name}}</option>
                                       <?php } ?>
                                    </select>
                                        </div>
                                       
                                        <div class="form-group col-md-4 grid_box1">
                                            {!! Form::text('trucklengthsearch',Input::get('trucklengthsearch'),['class'=>"form-control",'placeholder'=>'Truck Length']) !!}
                                        </div>
                                       
                                                                     
                                        <div class="form-group col-sm-4">
                                            {!! Form::submit('Search',['class'=>"btn btn-success"]) !!}
                                            <a href="{{ url('admin/trucklength/create') }}" class="btn btn-success" title="Refresh"><i class="fa fa-refresh"></i></a>                                            
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
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($trucklength)>0)
                                    <?php $i=$page['from']; ?>
                                        @foreach($trucklength as $value)
                                            <tr>
                                                <td><?= $i++ ?></td>
                                                <td>{{$value->trucktype}}</td>
                                                <td>{{$value->truck_length}}</td>
                                              
                                               
                                                <td>
                                                    <a href="{{URL :: asset('admin/trucklength/'.$value->id)}}/update" class="btn btn-success" title='edit'><i class="fa fa-edit"></i></a>
                                                    <a onclick="return confirm('Do you Want to Delete Length?');return false;" href="{{URL :: asset('admin/trucklength/'.$value->id)}}/delete" class="btn btn-success" title='delete'><i class="fa fa-trash-o"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr><td><i>No User Found</i></td></tr>
                                    @endif
                                </tbody>
                            </table>
                            <?php  echo $trucklength->render();  ?>                                        
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

 
        $.validator.addMethod("uniqueUserName", function(value, element) {
        var response = false;
        var trucktype=$('#trucktype').val();
       
            $.ajax({
                type: "GET",
                url: '{{url('checktrucklength')}}',
                data: "trucklength="+value+'&type='+trucktype,
                dataType:"html",
                success: function(msg)
                {
                  console.log(msg);
                   // response = ( msg == true ) ? true : false;
                   response = false;
                   return response;
                }
             });
           // return response;
        },
       
    )
    // return response;  

$('#newCategory').validate({

            rules: {
                trucktype:{
                    required : true
                    
                },
                trucklength:{
                    
                    required : true,
                    minlength:2,
                    lettersonly: true
                  //  uniqueUserName: true
                }
                
            },

            messages: {
                
                trucktype :{
                    required : "Select Truck Type"
                    
                },
                 trucklength :{
                    required : "Enter Truck Length",
                    minlength : 'Truck Length should be 2 digits'
                  //  uniqueUserName : 'Username is Already Taken'
                }
               
            }
            
           

        });
</script>
@endsection