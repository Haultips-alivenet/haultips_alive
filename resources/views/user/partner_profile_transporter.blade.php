@extends('layouts.user-dashboard-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')
<?php //print_r($vechileData); ?>

 

 {!! Form::open(array('url'=>'profile/transporter/update','class'=>'form-horizontal','id'=>'truckbooking_form','files' => true)) !!}
<div class="col-md-8">
                 <div class="_dash_lftt _dash_m">
                     <h3>Transporter Information</h3>
                      @if(Session::has('success'))
                            <div class="alert alert-success">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                {{Session::get('success')}}
                            </div>
                        @endif   
                    <div class="_trans_p">
                        <div class="form-group">
                            <div class="col-md-6">
                                <label for="">Total Vehicles</label>
                                <input type="text" name="total_vehicles" id="total_vehicles" class="form-control" placeholder="Enter the number" value="{{$user->total_vehicle}}">

                            </div>
                            <div class="col-md-6">
                                <label for="">Attched Vehicles</label>
                                <input type="text" name="attched_vehicles" id="attched_vehicles" class="form-control" value="{{$user->attached_vehicle}}" placeholder="Enter the number">
                            </div>
                        </div>
                    </div>
                     <div class="clearfix"></div><br></br>
                     <div class="col-md-12">
              <div class="_choose_truck">

                   
                    <ul class="nav nav-tabs">
                        <?php $i=1; foreach($trucktype as $val) { ?>
                        <?php if(strtolower($val->category_name)=="open") { $trucklogo="open-icon.png"; } else if(strtolower($val->category_name)=="trailer") { $trucklogo="trailer-icon.png"; } else if(strtolower($val->category_name)=="container") { $trucklogo="container.png"; } ?>
                        <li <?php if(strtolower($val->category_name)=="open") { ?> class="active" <?php } ?>>
                            <a data-toggle="tab" onclick="gettrucktype({{$val->id}},{{$i}});" href="#<?php echo strtolower($val->category_name); ?>"><img src="{{asset('public/user/img/'.$trucklogo)}}" alt=""><?php echo $val->category_name; ?></a>
                        </li>
                        <input type="hidden" name="subcategory_type[]" value="{{$val->id}}">
                        <?php $i++; } ?>
                    </ul>

                    <div class="tab-content">
                        <?php $i=1; foreach($trucktype as $val) { ?>
                        <div id="<?php echo strtolower($val->category_name); ?>"  class="tab-pane fade <?php if($val->category_name=="Open") { ?> in active <?php } ?>">
                            <h3 ><?php //echo $val->category_name; ?></h3>
                            <?php if(strtolower($val->category_name)=="open") { $trucktype=$openlength; $truckcapacity=$openlength_capacity; } else if(strtolower($val->category_name)=="trailer") { $trucktype=$Trailerlength; $truckcapacity=$Trailerlength_capacity; } else if(strtolower($val->category_name)=="container") { $trucktype=$Containerlength; $truckcapacity=$Containerlength_capacity; } ?>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="_open_sel_bx">
                                         <div class="form-group">
                                            <label for="">Length</label><br><br>
                                            <select name="length{{$val->id}}[]" id="length{{$i}}" onchange="getcapacity(this.value,{{$i}});" class="form-control select2-selection select2-selection--multiple" multiple="multiple" style="width: 443px;" data-placeholder="Select Length">
                                                <option value="">Select Length</option>
                                                <?php foreach($trucktype as $value) { ?>
                                                <option value="<?php echo $value->id; ?>" <?php foreach($vechileData as $vech_val) { if($vech_val->vehicle_length_id==$value->id) { echo "selected"; }}?>><?php echo $value->truck_length; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        
                                       <!-- <div class="form-group">
                                            <label for="">Capacity</label>
                                            <select name="{{$val->category_name}}capacity[]" id="capacity{{$i}}" class="form-control select2-selection select2-selection--multiple" multiple="multiple" style="width: 443px;" data-placeholder="Select Capacity">
                                                <option value="">Select Capacity</option>
                                                <?php //foreach($truckcapacity as $value) { ?>
                                                 <option value="<?php //echo $value->id; ?>" <?php //foreach($vechileData as $vech_val) { if($vech_val->vehicle_capacity_id==$value->id) { echo "selected"; }}?>><?php //echo $value->truck_capacity; ?></option>
                                                <?php //} ?>
                                            </select>
                                        </div>-->
                                    </div>

                                </div>
                               
                            </div>
                        </div>
                        <?php $i++; } ?> <br><br>
                         <div class="col-md-6">
                         {!! Form :: submit("Update",["class"=>"btn btn-color",'id'=>'']) !!}
                         
                   
                      </div>
                        <div class="col-md-6">
                       
                          <button onclick="window.history.back()" class="btn btn-border">Back</button>
                   
                      </div>
                    </div>
                </div>
        </div>
                 </div>   
            </div>
 
 {!! Form::close() !!}
@endsection
@section('script')

<script>
     $(".select2-selection").select2({ tags: true,
      placeholder: function(){
        $(this).data('placeholder');
    }
  
});

function getcapacity(id,val){
     id = $('#length'+val).val();
  
    $.ajax({ 
        type: 'get',
        url: '{{url('parner/profile/getcapacity')}}',
        data: 'id='+id,
        dataType: 'json',
        //cache: false,

        success: function(data) {
         //   alert(data.truck_capacity.length);
       var _options='<option value="">Select Capacity</option>';
       for(i=0; i<data.truck_capacity.length; i++){
          
                
              _options +='<option value="'+data.truck_capacity[i].id+'">'+data.truck_capacity[i].truck_capacity+'</option>'
             
       }
      
       
            $('#capacity'+val).html(_options);
        
       
        }

   });
 }
</script>


@endsection

