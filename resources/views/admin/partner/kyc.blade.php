@extends('layouts.admin-layout')
@section('title')
    Admin Panel | Haultips
@endsection

@section('body')
<?php //print_r($category); ?>
    <div id="page-wrapper">
        <div class="graphs">
            <h3 class="blank1">Upload Documents</h3>
            <hr>
             <div class="grid_3 fulldiv">
<div class="col-md-8">

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
                        {!! Form::open(array('url'=>'admin/partnerDocumentsUpload','class'=>'form-horizontal','id'=>'docupload','files' => true)) !!}
                            
                            <div id="msgStatus"></div>
                             <div class="form-group" style="display:none">
                                     {!! Form :: text('userType','3',['class'=>'form-control1', 'id'=>'userType'])  !!}                                                              
                            </div>
                            <!--<div class="form-group">
                                <label for="name" class="col-sm-3 control-label">Status</label>
                                <div class="col-sm-8">
                                    <select name="status" id="status" class="form-control1 select2">
                                        <option value=""  >Select Status</option>  
                                         <option value="1"  >Approve</option>  
                                         <option value="0"  >UnApprove</option>  
                                        
                                    </select>
                                  
                                </div> 
                            </div>-->
                            <div class="form-group">
                                <label for="name" class="col-sm-3 control-label">RC Photo</label>
                                <div class="col-sm-8">
                                     <div class="_upload_f btn-warning">{!! Form :: file('rc_photo','',['class'=>'form-control1', 'id'=>'rc_photo'])  !!}
                                   Upload RC Photo
                                     </div>
									   <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                </div>                                
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-3 control-label">Company PanCard</label>
                                <div class="col-sm-8">
                                     <div class="_upload_f btn-warning">{!! Form :: file('pancard','',['class'=>'form-control1', 'id'=>'pancard'])  !!}
                                   Upload PanCard
                                     </div>
                                </div>                                
                            </div>
                             <div class="form-group">
                                <label for="name" class="col-sm-3 control-label">Business Card</label>
                                <div class="col-sm-8">
                                     <div class="_upload_f btn-warning">{!! Form :: file('businesscard','',['class'=>'form-control1', 'id'=>'businesscard'])  !!}
                                    Upload Card
                                     </div>
                                </div>                                
                            </div>
                            <div class="col-sm-8 col-sm-offset-3">
                            {!! Form :: submit("Save",["class"=>"btn-success btn",'id'=>'upload']) !!}
                            <a href="{{ url('admin/partnerList') }}" class="btn-success btn" title="Back"><i class="fa fa-backward" style="color: #fff;">  Back</i></a>                                            
                            </div>
                        {!! Form::close() !!}
					
                    </div>
                        <?php if($kycdata) { ?>
                    </div></div>        
                    <div class="clearfix"></div>
                    <div class="mt25 table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr class="warning">
                                      
                                        <th>RC Photo</th>
                                        <th>Company PanCard</th>
                                        <th>Business Card</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><img src="{{asset('public/admin/kyc/'.$kycdata->rc_photo)}}" alt='foo' width='100' /></td>
                                        <td><img src="{{asset('public/admin/kyc/'.$kycdata->pancart)}}" alt='foo' width='100'/></td>
                                        <td><img src="{{asset('public/admin/kyc/'.$kycdata->business_card)}}" alt='foo' width='100' /></td>
                                         <td><a onclick="return confirm('Do you Want to Change status?');return false;" href="{{URL :: asset('admin/partner/changesttus/'.$user_id.'_'.$kycdata->documents_status)}}">{{($kycdata->documents_status=='1')?'Approved' : 'UnApproved'}}</a></td>
                                    </tr>
                                     
                                </tbody>
                            </table>
                                                                  
                        </div>
                        <?php } ?>
                </div>
           
        </div>
    </div>
@endsection

@section('script')
<script>
$('#docupload').validate({

            rules: {
                status:{
                    required : true
                    
                },
                rc_photo:{
                    required : true,
                    extension: "jpg|png|jpeg|gif"
                    
                },
                pancard:{
                    required : true,
                    extension: "jpg|png|jpeg|gif"
                    
                },
                businesscard:{
                    required : true,
                    extension: "jpg|png|jpeg|gif"
                    
                }
               
                
            },

            messages: {
                
                status :{
                    required : "Select Status"
                    
                },
                rc_photo :{
                    required : "Select RC Photo",
                    extension : "Select Image Only"
                    
                },
                pancard :{
                    required : "Select Pan Card",
                    extension : "Select Image Only"
                    
                },
                businesscard :{
                    required : "Select Business Card",
                    extension : "Select Image Only"
                    
                }
               
            }
            
           

        });
</script>
@endsection