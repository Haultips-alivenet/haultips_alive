@extends('layouts.user-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')
<?php //print_r($Containerlength); ?>

 {!! Form::open(array('url'=>'truckbooking','class'=>'form-horizontal','id'=>'truckbooking_form','files' => true)) !!}


<section class="_inner_pages containerfirstphase">
    <div class="container">
        <div class="row">
            <div class="_inner_bx">
                <h2>Truck Booking</h2>
                <div class="col-md-9">

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="">Delivery Title</label>

                            <input type="text" onkeyup="delivery_titlehide();" name="delivery_title" id="delivery_title" class="form-control">
                            <div id='errname' style='display:none;color:red;'>Please Enter delivery title</div>
                        </div>
                         <input type="hidden" name="trucktypeid" id="trucktypeid" value="{{$trucktype[0]->id}}">
                        <input type="hidden" name="id" id="id" value="1">
                       
                    </div>

                </div>
                <div class="col-md-9">

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="locationTextField">Enter loading point</label>

                            <input type="text" name="pickupaddress"  id="pickupaddress" class="form-control">

                        </div>

                        <div class="col-md-6">
                            <label for="">Enter trip destination</label>

                            <input type="text" name="deliveryaddress" id="deliveryaddress" class="form-control">

                        </div>
                    </div>

                </div>

                <div class="col-md-9" style="margin-top: 25px;">

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="">Pick-up Date</label>
                            <div class="row">
                                <div class="col-md-12">

                                    <div class='input-group date datetimepicker8'>
                                        <input type="text" name="pickupdate" id="pickupdate" class="form-control datetimepicker6 ">
                                        <span class="input-group-addon" style="background: transparent; border-radius: 0;"> <img src="{{asset('public/user/img/date-time-icon.png')}}" alt=""></span>
                                    </div>

                                </div>
                               
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="">Delivery Date </label>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class='input-group date datetimepicker7'>
                                        <input type="text" name="deliverydate" id="deliverydate" class="form-control datetimepicker7">
                                        <span class="input-group-addon" style="background: transparent; border-radius: 0;"> <img src="{{asset('public/user/img/date-time-icon.png')}}" alt=""></span>
                                    </div>

                                </div>
                               
                            </div>
                        </div>
                    </div>

                </div>
                <div class="clearfix"></div>
                <div class="_choose_truck">

                    <h2>Choose truck</h2>
                    <ul class="nav nav-tabs">
                        <?php $i=1; foreach($trucktype as $val) { ?>
                        <?php if(strtolower($val->category_name)=="open") { $trucklogo="open-icon.png"; } else if(strtolower($val->category_name)=="trailer") { $trucklogo="trailer-icon.png"; } else if(strtolower($val->category_name)=="container") { $trucklogo="container.png"; } ?>
                        <li <?php if(strtolower($val->category_name)=="open") { ?> class="active" <?php } ?>>
                            <a data-toggle="tab" onclick="gettrucktype({{$val->id}},{{$i}});" href="#<?php echo strtolower($val->category_name); ?>"><img src="{{asset('public/user/img/'.$trucklogo)}}" alt=""><?php echo $val->category_name; ?></a>
                        </li>
                        <?php $i++; } ?>
                    </ul>

                    <div class="tab-content">
                        <?php $i=1; foreach($trucktype as $val) { ?>
                        <div id="<?php echo strtolower($val->category_name); ?>"  class="tab-pane fade <?php if($val->category_name=="Open") { ?> in active <?php } ?>">
                            <h3 ><?php echo $val->category_name; ?></h3>
                            <?php if(strtolower($val->category_name)=="open") { $trucktype=$openlength; } else if(strtolower($val->category_name)=="trailer") { $trucktype=$Trailerlength; } else if(strtolower($val->category_name)=="container") { $trucktype=$Containerlength; } ?>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="_open_sel_bx">
                                         <div class="form-group">
                                            <label for="">Length</label>
                                            <select name="length{{$i}}" id="length{{$i}}" onchange="getcapacity(this.value,{{$i}});" class="form-control">
                                                <option value="">Select Length</option>
                                                <?php foreach($trucktype as $value) { ?>
                                                <option value="<?php echo $value->id; ?>"><?php echo $value->truck_length; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="">Capacity</label>
                                            <select name="capacity{{$i}}" id="capacity{{$i}}" class="form-control">
                                                <option value="">Select Capacity</option>
                                                
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-7">
                                    <div class="_open_img">
                                        <img src="{{asset('public/user/img/'.$val->category_image)}}" alt="">
                                        <div class="op_futr">
                                            <h4 class="text-center"> Capacity : 200 Ton</h4>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <?php $i++; } ?>
                         <div class="col-md-6">
                      <div class="_add_cmt_bx">
<h4>Additional information (Optional) </h4>
                        <p>Additional information:</p>
                        <div class="form-group">
                            <textarea cols="55" rows="5" id="additional_detail" name="additional_detail" class="form-control"></textarea>
                            
                        </div>
                    </div>
                         <div class="_add_btn_btm">
                        <a href="{{ url('/') }}" class="btn btn-border">Back</a>
                         {!! Form :: submit("Submit",["class"=>"btn btn-color",'id'=>'']) !!}
                    </div>
                      </div>
                    </div>
                </div>
            </div>
</section>
 <?php 
function getlatlong($add){
    echo $add;
}
?>
 {!! Form::close() !!}
@endsection
@section('script')
<script>
    $('#truckbooking_form').validate({

            rules: {
                delivery_title:{
                    required : true,
                },
                pickupdate:{
                    required : true,
                },
                deliverydate:{
                    //required : true,
                    
                },
                pickupaddress:{
                    required : true,
                    
                },
                deliveryaddress:{
                    required : true,
                    
                }
                
                
            },

            messages: {
                
                 delivery_title :{
                    required : "Please Enter Delivery Title"
                   
                },
                pickupdate :{
                    required : "Select Pickup Date"
                   
                },
                deliverydate :{
                    //required : "Select Delivery Date"
                   
                },
                pickupaddress :{
                    required : "Enter Pickup Address"
                   
                },
                deliveryaddress :{
                    required : "Enter Delivery Address"
                   
                }
            }
            
        });
  function gettrucktype(id,val){
      
     $("option:selected").attr("selected", false);
     $('#trucktypeid').val(id);
     $('#id').val(val);
  }      
 function getcapacity(id,val){
    $.ajax({ 
        type: 'get',
        url: '{{url('getcapacity')}}',
        data: 'id='+id,
        dataType: 'json',
        //cache: false,

        success: function(data) {
            
       var _options='<option value="">Select Capacity</option>';
       for(i=0; i<data.truck_capacity.length; i++){
          
                 //_options +='<label><input type="checkbox" name="truckcapacity_'+id+'[]" id="truckcapacity'+ data.truck_capacity[i].id+'" value="'+ data.truck_capacity[i].id+'"> '+ data.truck_capacity[i].truck_capacity +'</label>';
              _options +='<option value="'+data.truck_capacity[i].id+'">'+data.truck_capacity[i].truck_capacity+'</option>'
             
       }
      
       
            $('#capacity'+val).html(_options);
        
       
        }

   });
 }
function delivery_titlehide(){
    $('#errname').hide();
}
function weightide(){
    $('#errweight').hide(); 
}

   $('.carousel').carousel();
   $('#menu-toggle').click(function(){
       
   $('#sidebar-wrapper').toggle();
   });
   new WOW().init();


   $(".choose_m a").click(function(){
      
       $('#errmaterial').hide();
     var a=$(this).attr("matid");
     $('#material_id').val(a);
  $(this).parent().addClass("selected").siblings().removeClass("selected"); 
});
</script>


@endsection

