@extends('layouts.admin-layout')
@section('title')
    Admin Panel | Haultips
@endsection

@section('body')
<?php //print_r($category); ?>
    <div id="page-wrapper">
        <div class="graphs">
            <h3 class="blank1">Admin</h3>
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
                    @if(Session::has('success'))
                            <div class="alert alert-success">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                {{Session::get('success')}}
                            </div>
                        @endif
                   
                    <div class="tab-pane active" id="horizontal-form">
                        {!! Form::open(array('url'=>'admin/adminshipment-Save','class'=>'form-horizontal','id'=>'costestimation','files' => true)) !!}
                            
                            <div id="msgStatus"></div>
                             
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Cost Type</label>
                                <div class="col-sm-8">
                                    <select name="type" id="costtype" class="form-control select2" >
                                        <option value=""  >Select Type</option> 
                                        <?php foreach($type as $value) { ?>
                                        <option value="<?php echo $value->name; ?>" ><?php echo $value->details; ?></option>
                                        <?php } ?>
                                       
                                    </select>
                                </div> 
                            </div>
                            
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-8">
                                     {!! Form :: text('name','',['class'=>'form-control1', 'id'=>'name'])  !!}
                                </div>  
                               
                            </div>
                           
                           
                            <div class="col-sm-8 col-sm-offset-2">
                            {!! Form :: submit("Save",["class"=>"btn-success btn",'id'=>'trucklen']) !!}
                             
                            </div>
                              
                        {!! Form::close() !!}
					
                    </div>
                   
                </div>
          
        </div>
    </div>
@endsection

@section('script')
<script>
  
   
$('#costestimation').validate({

            rules: {
                type:{
                    required : true
                    
                },
                name:{
                    required : true
                   
                }
                
            },

            messages: {
                
                type :{
                    required : "Select Type"
                    
                },
                 name :{
                    required : "Plese Enter Name",
                    minlength : 'Truck Length should be 2 digits'
                }
               
            }
            
           

        });
</script>
@endsection