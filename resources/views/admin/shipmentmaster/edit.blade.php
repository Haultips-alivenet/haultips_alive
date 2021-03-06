@extends('layouts.admin-layout')
@section('title')
    Admin Panel | Haultips
@endsection

@section('body')
<?php //print_r($edit_name); ?>
    <div id="page-wrapper">
        <div class="graphs">
            <h3 class="blank1">Equipments</h3>
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
                        {!! Form::open(array('url'=>'admin/adminshipment-update','class'=>'form-horizontal','id'=>'costestimation','files' => true)) !!}
                            
                            <div id="msgStatus"></div>
                             
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Equipments Type</label>
                                <div class="col-sm-8">
                                    <select name="type" id="costtype" class="form-control select2" >
                                        <option value=""  >Select Type</option> 
                                        <?php foreach($type as $value) { ?>
                                        <option value="<?php echo $value->name; ?>" {{$edit_type==$value->name ? 'selected' : ''}} ><?php echo $value->details; ?></option>
                                        <?php } ?>
                                       
                                    </select>
                                </div> 
                            </div>
                            
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-8">
                                    {!! Form :: hidden('id',$edit_name->id,['class'=>'form-control1', 'id'=>'id'])  !!}
                                     {!! Form :: text('name',$edit_name->name,['class'=>'form-control1', 'id'=>'name'])  !!}
                                </div>  
                               
                            </div>
                           
                           
                            <div class="col-sm-8 col-sm-offset-2">
                            {!! Form :: submit("Update",["class"=>"btn-success btn",'id'=>'trucklen']) !!}
                             
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