@extends('layouts.admin-layout')
@section('title')
    Admin Panel | Haultips
@endsection

@section('body')
<?php //print_r($UserVehicleDetails); ?>
<style>
    ._box{
       color: #333;
        padding: 20px;
        display: none;
        margin-top: 20px;
    }
    .inner_box{
       color: #333;
        padding: 20px;
        display: none;
        margin-top: 20px;
    }
    
    .checkbox input{ left: 0;}
    .inner{
      border:1px solid #ddd;
      padding:10px;
      margin-bottom:5px;
    }
    ._box{
       margin-left:10px;
       border:1px solid #0d0;
    }
    .inner_box{ border-top:1px dashed #ddd;}
    </style>
    <div id="page-wrapper">
        <div class="graphs">
            <h3 class="blank1">Update Partner</h3>
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
                    <div class="tab-pane active" id="horizontal-form">
                        {!! Form::model($user,['route'=>['admin.partner.update',$user->id], 'method'=>'patch','class'=>'form-horizontal','id'=>'newUser'])  !!}
                       
                            
                            <div id="msgStatus"></div>
                             
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">First Name</label>
                                <div class="col-sm-8">
                                     {!! Form :: text('firstName',$user->first_name,['class'=>'form-control1', 'id'=>'firstname'])  !!}
                                </div>                                
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Last Name</label>
                                <div class="col-sm-8">
                                    {!! Form :: text('lastName',$user->last_name,['class'=>'form-control1', 'id'=>'lastname'])  !!}
                                </div>                                
                            </div>
                             <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Mobile</label>
                                <div class="col-sm-8">
                                    {!! Form :: text('mobile',$user->mobile_number,['class'=>'form-control1', 'id'=>'mobile','readonly'])  !!}
                                </div>                                
                            </div>
                            <div class="form-group">
				<label class="col-sm-2 control-label">Email</label>
				<div class="col-sm-8">	
                                     {!! Form :: text('email',$user->email,['class'=>'form-control1', 'id'=>'email'])  !!}
				</div>
                            </div>
                             <div class="form-group">
                                <label class="col-sm-2 control-label">State</label>
                                <div class="col-sm-8">
                                    
                                    <select name="state" id="state" class="form-control" onchange="getCityByStateId(this.value, 'city')">
                                    <option value="">Select a state</option>
                                        @foreach($states as $state)
                                        <option value="{{ $state->id }}" <?php if($user->state==$state->id) { echo "selected"; } ?>>{{ $state->name }}</option>
                                        @endforeach
                                </select>
                                </div>
                            </div>
                             <div class="form-group">
                                <label class="col-sm-2 control-label">City</label>
                                <div class="col-sm-8">
                                   
                                    <select name="city" id="city" class="form-control" >
                                    <option value="">Select a city</option>
                                      @foreach($city as $city)
                                        <option value="{{ $city->id }}" <?php if($user->city==$city->id) { echo "selected"; } ?>>{{ $city->name }}</option>
                                        @endforeach
                                   </select>
                                </div>
                            </div>
                             <div class="form-group">
                                <label class="col-sm-2 control-label">Total Vehicle</label>
                                <div class="col-sm-8">
                                    
                                    {!! Form :: text('total_vehicle',$user->total_vehicle,['class'=>'form-control1', 'id'=>'total_vehicle'])  !!}
                                </div>
                            </div>
                             <div class="form-group">
                                <label class="col-sm-2 control-label">Attached Vehicle</label>
                                <div class="col-sm-8">
                                    
                                     {!! Form :: text('attached_vehicle',$user->attached_vehicle,['class'=>'form-control1', 'id'=>'attached_vehicle'])  !!}
                                </div>
                            </div>
                             <div class="form-group">
                                <label class="col-sm-2 control-label"></label>
                                <?php $i=1; foreach($carrier_types as $types) {?>
                                    <div class="col-sm-3">
									<!--check-bottom-->
                                <div class="">
                                    <input type="checkbox" class="carrer_group" name="carrer_type<?php echo $i; ?>" id="carrer_type<?php echo $i; ?>"  value="{{$types->id}}" <?php if($user->carrier_type_id==3) { echo "checked"; } else if($user->carrier_type_id==$types->id) { echo "checked"; }?>>

                            <label for="carrer_type<?php echo $i; ?>"><span></span>{{$types->carrier_type}}</label>
                                </div>

                                
                                 
                                </div>
                                <?php $i++; } ?>
                            </div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-8">
                                <div id="transport_div" class="checkbox_align" <?php if($user->carrier_type_id==1) { ?>style="display: none" <?php } ?>>                
                               <?php foreach($categoryname as $types) { ?>                
                                <div class="checkbox">
                               <label><input type="checkbox" name="trucktype[]" id="trucktype" onclick="gettrucklength({{$types->id}});" value="{{$types->id}}" >  {{$types->category_name}}</label>
                                </div>
                                <div class="_box {{$types->id}}" id="trucklength_div{{$types->id}}"></div>
                              <?php } ?>
                            </div>  
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-sm-8 col-sm-offset-2">
                            {!! Form :: submit("Update Partner",["class"=>"btn-success btn",'id'=>'user']) !!}
                            </div>
                        {!! Form::close() !!}
					
                    </div>
                </div>
        </div>
        </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    $(document).ready(function(){
    $('input[type="checkbox"]').click(function(){
       var inputValue = $(this).attr("value");
        $("."+ inputValue).toggle();
    });
});
$('#carrer_type2').click(function(){
   
this.checked?$('#transport_div').show():$('#transport_div').hide(); //time for show
});
$('#newUser').validate({

            rules: {
                firstName:{
                    required : true,
                    minlength:2
                },
                lastName:{
                    required : true,
                    minlength:2
                },
                email:{
                    required : true,
                    email : true
                },

                mobile:{
                    required : true,
                    minlength:10,
                    number:true
                },

                password:{
                    required : true
                },

                cpassword:{
                    required : true
                },
                state:{
                    required : true
                    
                },
                 city:{
                    required : true
                    
                },
                 total_vehicle:{
                    required : true,
                    minlength:1,
                    number:true
                },
                 attached_vehicle:{
                    required : true,
                    minlength:1,
                    number:true
                },
				 carrer_type1: {
                    require_from_group: [1, ".carrer_group"]
                  },
                  carrer_type2: {
                    require_from_group: [1, ".carrer_group"]
                  }
               
            },

            messages: {
                
                firstName :{
                    required : "Enter your First name",
                    minlength : 'First Name should be 2 digits'
                },
                lastName :{
                    required : "Enter your Last name",
                    minlength : 'Last Name should be 2 digits'
                },
                email :{
                    required : "Enter your email",
                    email : "Enter Valid email"
                },
                mobile :{
                    required : 'Enter your Mobile Number',
                    minlength : 'Mobile Number should be 10 digits'
                },                
                password :{
                    required : "Enter your Password",
                },                
                cpassword :{
                    required : "Enter your Confirm Password",
                },
                state :{
                    required : "Enter your State",
                    minlength : 'State should be 2 digits'
                },
                city :{
                    required : "Enter your City",
                    minlength : 'City should be 2 digits'
                },
                total_vehicle :{
                    required : "Enter your Total Vehicle",
                    minlength : 'Total Vehicle should be 1 digits'
                },
                attached_vehicle :{
                    required : "Enter your Attached Vehicle",
                    minlength : 'Attached Vehicle should be 1 digits'
                }
            }
            
           

        });
      function gettrucklength(id){
          //alert(id);
           $.ajax({ 
        type: 'get',
        url: '{{url('gettrucklength')}}',
        data: 'id='+id,
        dataType: 'json',
        //cache: false,

        success: function(data) {
        
        console.log(data.truck_lengths);
       var _options='';
        
        for(i=0; i<data.truck_lengths.length; i++){
           
           _options +='<div class="inner">';
               _options +='<div class="checkbox">';
               _options +='<label><input type="checkbox" name="trucklength_'+id+'[]" id="trucklength'+ data.truck_lengths[i].id+'" onclick="gettruckcapacity('+ data.truck_lengths[i].id+');" value="'+ data.truck_lengths[i].id+'"> '+ '<button class="btn alert-success">'+data.truck_lengths[i].truck_length+'</button>' +'</label>';
              _options +='</div>';
               _options +='<div class="inner_box '+data.truck_lengths[i].id+'" id="truckcapacity_div'+ data.truck_lengths[i].id+'">';
              
               _options +='</div>';
               _options +='</div>';
               
        }
      
        //alert(id);
        $('#trucklength_div'+id).html(_options);
        }

   });
        }
        
    function gettruckcapacity(id){
    
   $.ajax({ 
        type: 'get',
        url: '{{url('gettruckcapacity')}}',
        data: 'id='+id,
        dataType: 'json',
        //cache: false,

        success: function(data) {
       var _options='';
       for(i=0; i<data.truck_capacity.length; i++){
                 _options +='<label><input type="checkbox" name="truckcapacity_'+id+'[]" id="truckcapacity'+ data.truck_capacity[i].id+'" value="'+ data.truck_capacity[i].id+'"> '+ '<button class="btn alert-info">'+data.truck_capacity[i].truck_capacity+ '</button>'+'</label>';
              
             
       }
         $("."+ id).toggle();
         $('#truckcapacity_div'+id).html(_options);
       
        }

   });
    }        
    function getCityByStateId(state_id, elm){
  var ajax_url = "{{ url('geo-location/city') }}/" + state_id;
  $.ajax({
      url: ajax_url,
      dataType: "json",
      success: function(msg) {
        var dt = '<option value="">Select a city</option>';
        var obj = JSON.parse(JSON.stringify(msg));
        $.each(obj, function(key, value) {
          dt += '<option value="' + value.id + '">' + value.name + '</option>';
        }); 
    
        $('#' + elm).html(dt);
      },
      error : function(msg, status) {
          alert(msg+status);
      }
  });
}     
</script>
@endsection