@extends('layouts.admin-layout')
@section('title')
    Admin Panel | Haultips
@endsection

@section('body')
<?php  error_reporting(0); ?>
    <div id="page-wrapper">
        <div class="graphs">
            <h3 class="blank1">Admin</h3>
                <div class="xs tabls">
                    <div class="panel panel-warning" >
                        
                                {!! Form::open(array('url'=>'admin/adminshipment/shipList','id'=>'menu','method'=>'post')) !!}
                                    <div class="row">  
                                        <div class="form-group col-md-4 grid_box1">
                                              <select name="type" id="costtype" class="form-control select2" >
                                        <option value=""  >Select Type</option> 
                                        <?php foreach($type as $value) { ?>
                                        <option value="<?php echo $value->name; ?>" {{Input::get('type')==$value->name ? 'selected' : ''}}><?php echo $value->details; ?></option>
                                        <?php } ?>
                                       
                                    </select>
                                        </div>
                                       
                                                                     
                                        <div class="form-group col-sm-4">
                                            {!! Form::submit('Search',['class'=>"btn btn-success"]) !!}
                                            <a href="{{ url('admin/adminshipment/shipList') }}" class="btn btn-success" title="Refresh"><i class="fa fa-refresh"></i></a>                                            
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
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($tableList)>0)
                                    <?php $i=$page['from']; ?>
                                        @foreach($tableList as $value)
                                            <tr>
                                                <td><?= $i++ ?></td>
                                                <td>{{$value->name}}</td>
                                                <td>
                                                    <a href="{{URL :: asset('admin/adminshipment/'.$value->id)}}/update?name={{Input::get('type')}}" class="btn btn-success" title='edit'><i class="fa fa-edit"></i></a>
                                                    <a onclick="return confirm('Do you Want to Delete?');return false;" href="{{URL :: asset('admin/adminshipment/'.$value->id)}}/delete?name={{Input::get('type')}}" class="btn btn-success" title='delete'><i class="fa fa-trash-o"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr><td><i>No User Found</i></td></tr>
                                    @endif
                                </tbody>
                            </table>
                            <?php if($tableList) { ?>
                            {!! $tableList->appends(Request::except('page'))->render() !!}
                            <?php } ?>
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