@extends('layouts.admin-layout')
@section('title')
    Admin Panel | Haultips
@endsection

@section('body')
   <div id="page-wrapper">
        <div class="graphs">
            <h3 class="blank1">Users</h3>
                <div class="xs tabls">
                    <div class="panel panel-warning" >
                        
                                {!! Form::open(array('url'=>'admin/userList','id'=>'menu','method'=>'get')) !!}
                                    <div class="row">  
                                        <div class="form-group col-md-2 grid_box1">
                                            {!! Form::text('name',Input::get('name'),['class'=>"form-control",'placeholder'=>'Name']) !!}
                                        </div>
                                        <div class="form-group col-md-2 grid_box1">
                                            {!! Form::text('email',Input::get('email'),['class'=>"form-control",'placeholder'=>'Email']) !!}
                                        </div>
                                        <div class="form-group col-md-2 grid_box1">
                                            {!! Form::text('mobile',Input::get('mobile'),['class'=>"form-control",'placeholder'=>'Mobile']) !!}
                                        </div>
                                        <div class="form-group col-md-2 grid_box1">
                                            <select name="status" id="status" class="form-control select2">
                                                <option value=""  >Select Status</option>                                                
                                                <option   value="1"  {{Input::get('status')=='1' ? 'selected' : ''}}>Active</option>
                                                <option   value="0" {{Input::get('status')=='0' ? 'selected' : ''}} >In Active</option>
                                            </select>
                                        </div>                                        
                                        <div class="form-group col-sm-3">
                                            {!! Form::submit('Search',['class'=>"btn btn-success"]) !!}
                                            <a href="{{ url('admin/userList') }}" class="btn btn-success" title="Refresh"><i class="fa fa-refresh"></i></a>                                            
                                        </div>                                        
                                    </div>
                                    {!! Form::close() !!}
                        
                        <div class="panel-heading">
                            <h2>Users List</h2>
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
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Status</th>
                                        <th>Registered Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($users)>0)
                                    <?php $i=$page['from']; ?>
                                        @foreach($users as $user)
                                            <tr>
                                                <td><?= $i++ ?></td>
                                                <td><a href="{{URL :: asset('admin/users/'.$user->id)}}"  class="btn btn-xs btn-link">{{$user->first_name." ".$user->last_name}}</a></td>
                                                <td>{{$user->email}}</td>
                                                <td>{{$user->mobile_number}}</td>
                                                <td>{{($user->status=='1')?'Active' : 'Inactive'}}</td>
                                                <td>{{date('F d, Y', strtotime($user->created_at))}}</td>
                                                <td>
                                                    <a href="{{URL :: asset('admin/users/'.$user->id)}}/edit" class="btn btn-success" title='edit'><i class="fa fa-edit"></i></a>
                                                    <a href="{{URL :: asset('admin/users/'.$user->id)}}/delete" class="btn btn-success" title='delete'><i class="fa fa-trash-o"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr><td><i>No User Found</i></td></tr>
                                    @endif
                                </tbody>
                            </table>
                            <?php  echo $users->render();  ?>                                        
                        </div>
                    </div>						
                </div>
        </div>
</div>
@endsection

