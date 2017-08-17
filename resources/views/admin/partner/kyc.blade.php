@extends('layouts.admin-layout')
@section('title')
    Admin Panel | Haultips
@endsection

@section('body')
<?php //print_r($category); ?>
    <div id="page-wrapper">
        <div class="graphs">
            <h3 class="blank1">Upload Documents</h3>
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
                        {!! Form::open(array('url'=>'admin/subCategory-Save','class'=>'form-horizontal','id'=>'newCategory','files' => true)) !!}
                            
                            <div id="msgStatus"></div>
                             <div class="form-group" style="display:none">
                                     {!! Form :: text('userType','3',['class'=>'form-control1', 'id'=>'userType'])  !!}                                                              
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Status</label>
                                <div class="col-sm-8">
                                    <select name="categoryName" id="status" class="form-control select2">
                                        <option value=""  >Select Status</option>  
                                         <option value="1"  >Approve</option>  
                                         <option value="0"  >UnApprove</option>  
                                        
                                    </select>
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
                    
                </div>
           
        </div>
    </div>
@endsection

@section('script')
<script>
$('#newCategory').validate({

            rules: {
                categoryName:{
                    required : true
                    
                },
                subCategoryName:{
                    required : true,
                    minlength:2
                },
                subcategoryImage:{
                    required : true
                    
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
                    required : "Select Image For Sub Category"
                    
                }
               
            }
            
           

        });
</script>
@endsection