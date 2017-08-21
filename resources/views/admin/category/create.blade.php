@extends('layouts.admin-layout')
@section('title')
    Admin Panel | Haultips
@endsection

@section('body')
    <div id="page-wrapper">
        <div class="graphs">
            <h3 class="blank1">New Category</h3>
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
                      
                          {!! Form::model($categoryupdate,['route'=>['admin.category.update',$categoryupdate->id],'files'=>true, 'method'=>'patch','class'=>'form-horizontal'])  !!}
                            
                            <div id="msgStatus"></div>
                             <div class="form-group" style="display:none">
                                     {!! Form :: text('userType','3',['class'=>'form-control1', 'id'=>'userType'])  !!}                                                              
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Category Name</label>
                                <div class="col-sm-8">
                                     {!! Form :: text('categoryNameupdate',$categoryupdate->category_name,['class'=>'form-control1', 'id'=>'categoryNameupdate'])  !!}
                                </div> 
                                
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Category Image</label>
                                <div class="col-sm-4">
                                     {!! Form :: file('categoryImageupdate','',['class'=>'form-control1', 'id'=>'categoryImageupdate'])  !!}
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
                        {!! Form::open(array('url'=>'admin/Category-Save','class'=>'form-horizontal','id'=>'newCategory','files' => true)) !!}
                            
                            <div id="msgStatus"></div>
                             <div class="form-group" style="display:none">
                                     {!! Form :: text('userType','3',['class'=>'form-control1', 'id'=>'userType'])  !!}                                                              
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Category Name</label>
                                <div class="col-sm-8">
                                     {!! Form :: text('categoryName','',['class'=>'form-control1', 'id'=>'categoryname'])  !!}
                                </div>                                
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Category Image</label>
                                <div class="col-sm-8">
                                     {!! Form :: file('categoryImage','',['class'=>'form-control1', 'id'=>'categoryImage'])  !!}
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
                        
                                {!! Form::open(array('url'=>'admin/category/create','id'=>'menu','method'=>'get')) !!}
                                    <div class="row">  
                                        <div class="form-group col-md-4 grid_box1">
                                            {!! Form::text('category_name',Input::get('category_name'),['class'=>"form-control",'placeholder'=>'Category Name']) !!}
                                        </div>
                                       
                                                                     
                                        <div class="form-group col-sm-4">
                                            {!! Form::submit('Search',['class'=>"btn btn-success"]) !!}
                                            <a href="{{ url('admin/category/create') }}" class="btn btn-success" title="Refresh"><i class="fa fa-refresh"></i></a>                                            
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
                                                <td>{{$value->category_name}}</td>
                                                <td><img src="{{asset('public/admin/images/category/'.$value->category_image)}}" alt='foo' width='50' height='30'/></td>
                                               
                                                <td>
                                                    <a href="{{URL :: asset('admin/category/'.$value->id)}}/update" class="btn btn-success" title='edit'><i class="fa fa-edit"></i></a>
                                                    <a onclick="return confirm('Do you Want to Delete Category?');return false;" href="{{URL :: asset('admin/category/'.$value->id)}}/delete" class="btn btn-success" title='delete'><i class="fa fa-trash-o"></i></a>
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
$('#newCategory').validate({

            rules: {
                categoryName:{
                    required : true,
                    minlength:2
                }
                
            },

            messages: {
                
                categoryName :{
                    required : "Enter Category Name",
                    minlength : 'Category Name should be 2 digits'
                }
               
            }
            
           

        });
</script>
@endsection