@extends('layouts.admin-layout')
@section('title')
    Admin Panel | Haultips
@endsection

@section('body')
<?php //print_r($category); ?>
    <div id="page-wrapper">
        <div class="graphs">
            <h3 class="blank1">New Sub Category</h3>
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
                
                     <?php if($categoryupdate) { ?>
                        <div class="tab-pane active" id="horizontal-form">
                      
                          {!! Form::model($categoryupdate,['route'=>['admin.subcategory.update',$categoryupdate->id],'files'=>true, 'method'=>'patch','class'=>'form-horizontal','id'=>'newCategoryupdate'])  !!}
                            
                            <div id="msgStatus"></div>
                             <div class="form-group" style="display:none">
                                     {!! Form :: text('userType','3',['class'=>'form-control1', 'id'=>'userType'])  !!}                                                              
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Category Name</label>
                                <div class="col-sm-8">
                                    <select name="categoryupdate" id="status" class="form-control select2">
                                        <option value=""  >Select Category</option>                                                
                                       <?php foreach($categoryname as $value) { ?>
                                        <option   value="{{$value->id}}"  {{$categoryupdate->parent_id==$value->id ? 'selected' : ''}}>{{$value->category_name}}</option>
                                       <?php } ?>
                                    </select>
                                </div> 
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Sub Category Name</label>
                                <div class="col-sm-8">
                                     {!! Form :: text('subcategoryupdate',$categoryupdate->category_name,['class'=>'form-control1', 'id'=>'categoryNameupdate'])  !!}
                                </div> 
                                
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Category Image</label>
                                <div class="col-sm-4">
                                     {!! Form :: file('subcategoryImageupdate','',['class'=>'form-control1', 'id'=>'subcategoryImageupdate'])  !!}
                                </div> 
                                <div class="col-sm-4">
                                     <img src="{{asset('public/admin/images/category/'.$categoryupdate->category_image)}}" alt='foo' width='50' height='30'/>
                                </div>   
                            </div>
                            <div class="col-sm-8 col-sm-offset-2">
                            {!! Form :: submit("Update Category",["class"=>"btn-success btn",'id'=>'Categoryupdate']) !!}
                            </div>
                        {!! Form::close() !!}
					
                    </div>
                     <?php } else { ?>
                    <div class="tab-pane active" id="horizontal-form">
                        {!! Form::open(array('url'=>'admin/subCategory-Save','class'=>'form-horizontal','id'=>'newCategory','files' => true)) !!}
                            
                            <div id="msgStatus"></div>
                             <div class="form-group" style="display:none">
                                     {!! Form :: text('userType','3',['class'=>'form-control1', 'id'=>'userType'])  !!}                                                              
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Category Name</label>
                                <div class="col-sm-8">
                                    <select name="categoryName" id="status" class="form-control select2">
                                        <option value=""  >Select Category</option>                                                
                                        <?php foreach($categoryname as $value) { ?>
                                        <option   value="{{$value->id}}"  {{Input::get('status')=='1' ? 'selected' : ''}}>{{$value->category_name}}</option>
                                       <?php } ?>
                                    </select>
                                </div> 
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Category Name</label>
                                <div class="col-sm-8">
                                     {!! Form :: text('subCategoryName','',['class'=>'form-control1', 'id'=>'subCategoryName'])  !!}
                                </div>                                
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Category Image</label>
                                <div class="col-sm-8">
                                     {!! Form :: file('subcategoryImage','',['class'=>'form-control1', 'id'=>'categoryImage'])  !!}
                                </div>                                
                            </div>
                            <div class="col-sm-8 col-sm-offset-2">
                            {!! Form :: submit("Save Category",["class"=>"btn-success btn",'id'=>'Category']) !!}
                            </div>
                        {!! Form::close() !!}
					
                    </div>
                     <?php } ?>
                </div>
            <br><br>
                 <div class="xs tabls">
                    <div class="panel panel-warning" >
                        
                                {!! Form::open(array('url'=>'admin/subcategory/create','id'=>'menu','method'=>'get')) !!}
                                    <div class="row">  
                                        <div class="form-group col-md-4 grid_box1">
                                             <select name="categoryNamesearch" id="status" class="form-control select2">
                                        <option value=""  >Select Category</option>                                                
                                        <?php foreach($categoryname as $value) { ?>
                                        <option   value="{{$value->id}}"  {{Input::get('categoryNamesearch')==$value->id ? 'selected' : ''}}>{{$value->category_name}}</option>
                                       <?php } ?>
                                    </select>
                                        </div>
                                       
                                        <div class="form-group col-md-4 grid_box1">
                                            {!! Form::text('subcategory_namesearch',Input::get('subcategory_namesearch'),['class'=>"form-control",'placeholder'=>'Sub Category Name']) !!}
                                        </div>
                                       
                                                                     
                                        <div class="form-group col-sm-4">
                                            {!! Form::submit('Search',['class'=>"btn btn-success"]) !!}
                                            <a href="{{ url('admin/subcategory/create') }}" class="btn btn-success" title="Refresh"><i class="fa fa-refresh"></i></a>                                            
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
                                        <th>Category Name</th>
                                        <th>Sub Category Name</th>
                                        <th>Image</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($category)>0)
                                    <?php $i=$page['from']; ?>
                                        @foreach($category as $value)
                                            <tr>
                                                <td><?= $i++ ?></td>
                                                <td>{{$value->cname}}</td>
                                                <td>{{$value->category_name}}</td>
                                                <td><img src="{{asset('public/admin/images/category/'.$value->category_image)}}" alt='foo' width='50' height='30'/></td>
                                               
                                                <td>
                                                    <a href="{{URL :: asset('admin/subcategory/'.$value->id)}}/update" class="btn btn-success" title='edit'><i class="fa fa-edit"></i></a>
                                                    <a onclick="return confirm('Do you Want to Delete Category?');return false;" href="{{URL :: asset('admin/subcategory/'.$value->id)}}/delete" class="btn btn-success" title='delete'><i class="fa fa-trash-o"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr><td><i>No User Found</i></td></tr>
                                    @endif
                                </tbody>
                            </table>
                            <?php  echo $category->render();  ?>                                        
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

$('#newCategory').validate({

            rules: {
                categoryName:{
                    required : true
                    
                },
                subCategoryName:{
                    required : true,
                    minlength:2,
                    lettersonly: true
                },
                subcategoryImage:{
                    required : true,
                    extension: "jpg|png|jpeg|gif"
                    
                }
                
            },

            messages: {
                
                categoryName :{
                    required : "Select Category Name"
                    
                },
                 subCategoryName :{
                    required : "Enter Sub Category Name",
                    minlength : 'Sub Category Name should be 2 digits'
                },
                 subcategoryImage :{
                    required : "Select Image For Sub Category",
                    extension : "Select Image Only"
                    
                }
               
            }
            
           

        });
        
$('#newCategoryupdate').validate({

            rules: {
                categoryupdate:{
                    required : true
                    
                },
                subcategoryupdate:{
                    required : true,
                    minlength:2,
                    lettersonly: true
                },
                subcategoryImageupdate:{
                    
                    extension: "jpg|png|jpeg|gif"
                    
                }
                
            },

            messages: {
                
                categoryupdate :{
                    required : "Select Category Name"
                    
                },
                 subcategoryupdate :{
                    required : "Enter Sub Category Name",
                    minlength : 'Sub Category Name should be 2 digits'
                },
                 subcategoryImageupdate :{
                    
                    extension : "Select Image Only"
                    
                }
               
            }
            
           

        });
</script>
@endsection