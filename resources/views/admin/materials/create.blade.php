@extends('layouts.admin-layout')
@section('title')
    Admin Panel | Haultips
@endsection

@section('body')
    <div id="page-wrapper">
        <div class="graphs">
            <h3 class="blank1">New Materials</h3>
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
                
                     <?php if($materialsupdate) { ?>
                        <div class="tab-pane active" id="horizontal-form">
                      
                          {!! Form::model($materialsupdate,['route'=>['admin.materials.update',$materialsupdate->id],'files'=>true, 'method'=>'patch','class'=>'form-horizontal','id'=>'newmaterialupdate'])  !!}
                            
                            <div id="msgStatus"></div>
                             
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Materials Name</label>
                                <div class="col-sm-8">
                                     {!! Form :: text('materialNameupdate',$materialsupdate->name,['class'=>'form-control1', 'id'=>'materialNameupdate'])  !!}
                                </div> 
                                
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Materials Image</label>
                                <div class="col-sm-4">
                                     {!! Form :: file('materialsImageupdate','',['class'=>'form-control1', 'id'=>'materialsImageupdate'])  !!}
                                </div> 
                                <div class="col-sm-4">
                                     <img src="{{asset('public/admin/images/materials/'.$materialsupdate->image)}}" alt='foo' width='50' height='30'/>
                                </div>   
                            </div>
                            <div class="col-sm-8 col-sm-offset-2">
                            {!! Form :: submit("Update Materials",["class"=>"btn-success btn",'id'=>'materialpdate']) !!}
                            </div>
                        {!! Form::close() !!}
					
                    </div>
                     <?php } else { ?>
                    <div class="tab-pane active" id="horizontal-form">
                        {!! Form::open(array('url'=>'admin/materials-Save','class'=>'form-horizontal','id'=>'newMaterials','files' => true)) !!}
                            
                            <div id="msgStatus"></div>
                             
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Materials Name</label>
                                <div class="col-sm-8">
                                     {!! Form :: text('materialsName','',['class'=>'form-control1', 'id'=>'materialsname'])  !!}
                                </div>                                
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Materials Image</label>
                                <div class="col-sm-8">
                                     {!! Form :: file('materialsImage','',['class'=>'form-control1', 'id'=>'materialsImage'])  !!}
                                </div>                                
                            </div>
                            <div class="col-sm-8 col-sm-offset-2">
                            {!! Form :: submit("Save Materials",["class"=>"btn-success btn",'id'=>'Category']) !!}
                            </div>
                        {!! Form::close() !!}
					
                    </div>
                     <?php } ?>
                </div>
            <br><br>
                 <div class="xs tabls">
                    <div class="panel panel-warning" >
                        
                                {!! Form::open(array('url'=>'admin/materials/create','id'=>'menu','method'=>'get')) !!}
                                    <div class="row">  
                                        <div class="form-group col-md-4 grid_box1">
                                            {!! Form::text('materials_name',Input::get('materials_name'),['class'=>"form-control",'placeholder'=>'Materials Name']) !!}
                                        </div>
                                       
                                                                     
                                        <div class="form-group col-sm-4">
                                            {!! Form::submit('Search',['class'=>"btn btn-success"]) !!}
                                            <a href="{{ url('admin/materials/create') }}" class="btn btn-success" title="Refresh"><i class="fa fa-refresh"></i></a>                                            
                                        </div>                                        
                                    </div>
                                    {!! Form::close() !!}
                        
                        <div class="panel-heading">
                            <h2>Materials List</h2>
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
                                        <th>Materials Name</th>
                                        <th>Image</th>
                                        <th>Action</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($materials)>0)
                                    <?php $i=$page['from']; ?>
                                        @foreach($materials as $value)
                                            <tr>
                                                <td><?= $i++ ?></td>
                                                <td>{{$value->name}}</td>
                                                <td><img src="{{asset('public/admin/images/materials/'.$value->image)}}" alt='foo' width='50' height='30'/></td>
                                               
                                                <td>
                                                    <a href="{{URL :: asset('admin/materials/'.$value->id)}}/update" class="btn btn-success" title='edit'><i class="fa fa-edit"></i></a>
                                                    <a onclick="return confirm('Do you Want to Delete Materials?');return false;" href="{{URL :: asset('admin/materials/'.$value->id)}}/delete" class="btn btn-success" title='delete'><i class="fa fa-trash-o"></i></a>
                                                </td>
                                                <td><a onclick="return confirm('Do you Want to Change Status?');return false;" href="{{URL :: asset('admin/materials/status/'.$value->id.'_'.$value->status)}}">{{($value->status==1)?"Active":"InActive"}}</a></td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr><td><i>No User Found</i></td></tr>
                                    @endif
                                </tbody>
                            </table>
                            <?php  echo $materials->render();  ?>                                        
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

$('#newMaterials').validate({

            rules: {
                materialsName:{
                    required : true,
                    minlength:2,
                    lettersonly: true
                },
                materialsImage : {
                    required : true,
                    extension: "jpg|png|jpeg|gif"
                }
                
            },

            messages: {
                
                materialsName :{
                    required : "Enter Materials Name",
                    minlength : 'Category Name should be 2 digits'
                },
                 materialsImage :{
                    required : "Select Materials Image",
                    extension : "Select Image Only"
                }
               
            }
            
           

        });
        $('#newCategoryupdate').validate({

            rules: {
                categoryNameupdate:{
                    required : true,
                    minlength:2,
                   // lettersonly: true
                },
                categoryImageupdate : {
                   
                    extension: "jpg|png|jpeg|gif"
                }
                
            },

            messages: {
                
                categoryNameupdate :{
                    required : "Enter Category Name",
                    minlength : 'Category Name should be 2 digits'
                },
                 categoryImageupdate :{
                   
                    extension : "Select Image Only"
                }
               
            }
            
           

        });
</script>
@endsection