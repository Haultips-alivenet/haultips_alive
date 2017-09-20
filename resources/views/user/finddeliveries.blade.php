@extends('layouts.user-layout')
@section('title')
    Admin Panel | Haultips
@endsection

@section('body')

<?php //print_r($diliveries); ?>
<section class="main_pages">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3">

                <div class="_fn_de_lft">
                    <div class="form-horizontal _fh">
                        <div class="form-group">
                            <h3> Clean Search</h3>
                            <div class="input-group" onclick="cleansearch();" style="cursor:pointer;">
                                <span class="input-group-addon">Clean Search</span>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <h3>Refine Search</h3>
                        <div class="_srch_list">
                            <ul>
                                <?php  $i=1; foreach($categories as $value) { ?>
                                <li>

                                    <div class="checkbox">
                                        <input id="checkbox{{$i}}" onclick="getdata({{$value->id}});" <?php for($k=0;$k<count(@$cid);$k++) { if(@$cid[$k]==$value->id) { echo "checked";  } } ?> name="category[]" value="{{$value->id}}" type="checkbox" >
                                        <label for="checkbox{{$i}}">{{$value->category_name}}</label>
                                       <!-- <i class="fa fa-plus" onclick="list_toggle({{$value->id}})"></i>-->
                                    </div>

                                    <ul class="test-dwn{{$value->id}}" style="display: none;"></ul>

                                </li>
                                <?php $i++; } ?>
                               
                            </ul>
                        </div>

                    </div>

                    <div class="clearfix"></div>

                    <div class="_collection">
                        <form class="form-inline">
                            <h3>Collection</h3>

                            <ul>
                                <li><a href="#">City </a></li>
                                <li><a href="#">All</a></li>
                            </ul>
                            <div class="clearfix"></div>
                            <div class="input-group col-md-12">
                                <input type="text" class="  search-query form-control" name="pickupaddress" id="pickupaddress" placeholder="Search" value="{{@$p_add}}"/>
                                <span class="input-group-btn">
                                <button class="btn btn-cl" type="button" onclick="getpickupdata();">
                                    <span class=" glyphicon glyphicon-arrow-right"></span>
                                </button>
                                </span>
                            </div>
                            <small>e.g. Richmond, Surrey or TW9</small>

                           <!-- <div class="form-group _lin">
                                <label for="exampleInputName2">Radius</label>

                                <select name="" id="" class="selectpicker" style="width: 70px;">
                                    <option value="">100</option>
                                    <option value="">200</option>
                                    <option value="">300</option>
                                </select>
                                Km
                            </div>-->
                        </form>

                    </div>
                    
                    
                    <div class="_delivery">
                        {!! Form::open(array('url'=>'user/find/deliveries','class'=>'form-inline','id'=>'all_search_form','method'=>'post')) !!}
                            <h3>Delivery</h3>
                            <ul>
                                <li><a href="#">City</a></li>
                                <li><a href="#">All</a></li>
                            </ul>
                            <div class="clearfix"></div>
                            <div class="input-group col-md-12">
                                <input type="text" class="search-query form-control" name="deliveryaddress" id="deliveryaddress" placeholder="Search" value="{{@$d_add}}"/>
                                <span class="input-group-btn">
                                    <button class="btn btn-cl" type="button" onclick="getdeliverydata();">
                                        <span class="  glyphicon glyphicon-arrow-right"></span>
                                </button>
                                </span>
                            </div>
                            <small>e.g. Richmond, Surrey or TW9</small>
                            
                            <input type="hidden" name="categoryIdAll" id="categoryIdAll">
                            <input type="hidden" name="orderByAll" id="orderByAll">
                            <input type="hidden" name="pickupaddressAll" id="pickupaddressAll" value="{{@$p_add}}">
                            <input type="hidden" name="deliveryaddressAll" id="pickupaddressAll" value="{{@$d_add}}">
                           
                            
                            {!! Form::close() !!}
                    </div>
                     
                   <!-- <div class="_price">
                        <form class="form-inline">
                            <h3>Price</h3>

                            <div class="clearfix"></div>

                            <div class="input-group _ig">
                                <div class="col-md-6 no-padding"> <span class="pull-left" style="padding-right:13px;">Price</span>
                                    <input type="text" class="form-control"> </div>
                                <div class="col-md-6 no-padding"> <span class="pull-left" style="padding-right: 13px;">- To - </span>
                                    <input type="text" class="form-control"> </div>

                            </div>

                            <div class="slider_dv">
                                <b>INR 10</b>
                                <input id="ex2" type="text" class="span2" value="" data-slider-min="10" data-slider-max="1000" data-slider-step="5" data-slider-value="[250,450]" /> <b>INR 1000</b>
                            </div>

                        </form>

                    </div>--->

                </div>

            </div>
            
            <div class="col-lg-9 col-md-9">
                        @if(Session::has('success'))
                            <div class="alert alert-success">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                {{Session::get('success')}}
                            </div>
                        @endif
                <div class="_fn_mp_rft">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <span class="pull-left clickable"><i class="glyphicon glyphicon-minus"></i>View Map</span>

                        </div>
                        <div id="map_container" class="panel-body">
                            <div id="map"></div>
                            <div class="clearfix"></div>

                        </div>
                        <div class="_fr_bm_fn">
                            <h3>Show shipments that:</h3>
                            <hr>
                            <h4>In Map Area</h4>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4">
                                        <div class="radio radio-success radio-inline">
                                            <input name="radio1_2" id="radio1_2" value="option2" type="radio">
                                            <label for="radio1_2">Start <i class="glyphicon glyphicon-map-marker" aria-hidden="true"></i>
                                            </label> <span> OR</span>
                                        </div>

                                        <div class="radio radio-danger radio-inline">

                                            <input name="radio1_2" id="radio2_3" value="option2" type="radio">
                                            <label for="radio2_3">End <i class="glyphicon glyphicon-map-marker" aria-hidden="true"></i>
                                            </label>
                                        </div>

                                    </div>
                                    <div class="col-lg-4 col-md-4">

                                        <div class="radio radio-success radio-inline">
                                            <input name="radio1_4" id="radio1_4" value="option2" type="radio">
                                            <label for="radio1_4">Start <i class="glyphicon glyphicon-map-marker" aria-hidden="true"></i>
                                            </label> <span> OR</span>
                                        </div>

                                        <div class="radio radio-danger radio-inline">
                                            <input name="radio1_4" id="radio2_4" value="option2" type="radio">
                                            <label for="radio2_4">End <i class="glyphicon glyphicon-map-marker" aria-hidden="true"></i>
                                            </label>
                                        </div>

                                    </div>

                                    <div class="col-lg-2 col-md-2">

                                        <div class="radio radio-success radio-inline">
                                            <input name="radio1_5" id="radio1_5" value="option2" type="radio">
                                            <label for="radio1_5">Start <i class="glyphicon glyphicon-map-marker" aria-hidden="true"></i>
                                            </label>
                                        </div>

                                    </div>
                                    <div class="col-lg-2 col-md-2">
                                        <div class="radio radio-danger radio-inline">
                                            <input name="radio1_5" id="radio1_6" value="" type="radio">
                                            <label for="radio1_6">End <i class="glyphicon glyphicon-map-marker" aria-hidden="true"></i>
                                            </label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="_fr_bm_frm_srch">
                            <div class="col-md-3">
                                <div class="text-right _tt"> Zoom to area:</div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" placeholder="Search" value="" class="form-control">
                                        <div class="input-group-addon">Search</div>
                                    </div>
                                </div>
                                e.g. India,New Delhi,Mumbai,Kolkata
                            </div>
                        </div>

                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="_fnd_de_data">
                    <div class="_result_tb">
                      <!--  <ul>
                            <li>Results: </li>
                            <li><a href="javascript:void(0);">1 -</a></li>
                            <li> <a href="javascript:void(0);">  50  of  525  </a></li>
                            <li>|</li>
                            <li> <a href="javascript:void(0);">   Next</a></li>

                        </ul>-->

                        <div class="input-group _pr">
                            <span class="pull-left">Sort by</span>
                            <select class="selectpicker" name="orderby" id="orderby" onchange="getorderbydata(this.value);">
                                <option value="">-Select Sort-</option>
                                
                                <option {{$orderBy=="origin_asc" ? 'selected' : ''}} value="origin_asc">Origin: A-Z</option>
                                <option {{$orderBy=="origin_desc" ? 'selected' : ''}} value="origin_desc">Origin: Z-A</option>
                                <option {{$orderBy=="destination_asc" ? 'selected' : ''}} value="destination_asc">Destination: A-Z</option>
                                <option {{$orderBy=="destination_desc" ? 'selected' : ''}} value="destination_desc">Destination: Z-A</option>
                            </select>
                        </div>
                    </div>
                    <table class="table table-striped table-hover _fn_list">
                        <thead>
                            <tr>

                                <th style="width:20%">Title</th>
                                <th style="width:20%">Min Bids Price</th>
                                <th style="width:15%">Origin</th>
                                <th style="width:20%">Destination</th>
                                <th style="width:10%">Distance</th>
                                <th style="width:15%">Ending</th>
                            </tr>
                        </thead>

                        <tbody style="display: table-row-group;">
                            <?php foreach($diliveries as $value) { ?>
                            <tr>
                                <td> <span class="_quote"><a href="{{URL :: asset('user/find/deliveries/details/'.$value["shippingId"])}}">{{$value["title"]}}</a></span></td>
                                <td><span class="_quote"> INR {{$value["minimumBid"]}}</span> </td>
                                <td><span>{{$value["pickupAddress"]}}</span></td>
                                <td><span>{{$value["deliveryAddress"]}}</span></td>
                                <td><span class="_wet">{{$value["distance"]}}</span></td>
                                <td><span class="_date">{{date('m-d-Y',strtotime($value["postingDate"]))}}</span></td>
                            </tr>
                            <?php } ?>
                          

                        </tbody>
                    </table>
                    
                    <?php if($pagess) { ?>
                            {!! $pagess->appends(Request::except('page'))->render() !!}
                    <?php } ?>
                    
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
 <!--<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>-->
<script>
    $( document ).ready(function() {
        <?php //for($k=0;$k<count(@$cid);$k++) { ?>
            //list_toggle({{@$cid[$k]}});
        <?php   // } ?>
       
    });
   $('.carousel').carousel();
   $('#menu-toggle').click(function(){
   $('#sidebar-wrapper').toggle();
   });
   new WOW().init();
   $("#ex2").slider({});

function list_toggle(val)
 {
  
      $.ajax({ 
        type: 'get',
        url: '{{url('getsubcategory')}}',
        data: 'id='+val,
        dataType: 'json',
        //cache: false,

        success: function(data) {
       
        console.log(data.subcategory);
       var _options='';
        
        for(i=0; i<data.subcategory.length; i++){
             
           _options +='<li><label>';
           _options +='<input name="subcategory[]"  onclick="getsubcategorydata();" value="'+data.subcategory[i].id+'" type="checkbox"> '+data.subcategory[i].category_name+'</label>';
           _options +='</li>';
        }
      
        $('.test-dwn'+val).html(_options);
        $('.test-dwn'+val).slideToggle(); 
        }

   });
     
 }
function getdata(id){
     var cbc = document.getElementsByName('category[]');
     var orderby = $('#orderby').val();
     var pickupaddress = $('#pickupaddress').val();
     var deliveryaddress = $('#deliveryaddress').val();
	
		  var result = '';
		  for(var i=0; i<cbc.length; i++) 
		  {
			 if(cbc[i].checked ) result += (result.length > 0 ? "," : "") + cbc[i].value;
		  }
	
   
    if(result) {
    $('#categoryIdAll').val(result);
    }
    if(orderby) {
    $('#orderByAll').val(orderby);
    }
    if(pickupaddress) {
    $('#pickupaddressAll').val(pickupaddress);
    }
    if(deliveryaddress) {
    $('#deliveryaddressAll').val(deliveryaddress);
    }
    document.getElementById("all_search_form").submit();
}
function getorderbydata(orderby){
    var cbc = document.getElementsByName('category[]');
    var pickupaddress = $('#pickupaddress').val();
    var deliveryaddress = $('#deliveryaddress').val();
        var result = '';
        for(var i=0; i<cbc.length; i++) 
        {
               if(cbc[i].checked ) result += (result.length > 0 ? "," : "") + cbc[i].value;
        }
   
    $('#orderByAll').val(orderby);
    if(result) {
   $('#categoryIdAll').val(result);
    }
    if(pickupaddress) {
    $('#pickupaddressAll').val(pickupaddress);
    }
    if(deliveryaddress) {
    $('#deliveryaddressAll').val(deliveryaddress);
    }
  document.getElementById("all_search_form").submit();
  
}
function getpickupdata(){
    var cbc = document.getElementsByName('category[]');
    var pickupaddress = $('#pickupaddress').val();
    var deliveryaddress = $('#deliveryaddress').val();
    var orderby = $('#orderby').val();
        var result = '';
        for(var i=0; i<cbc.length; i++) 
        {
               if(cbc[i].checked ) result += (result.length > 0 ? "," : "") + cbc[i].value;
        }

    if(result) {
    $('#categoryIdAll').val(result);
    }
    if(orderby) {
    $('#orderByAll').val(orderby);
    }
    if(pickupaddress) {
    $('#pickupaddressAll').val(pickupaddress);
    }
    if(deliveryaddress) {
    $('#deliveryaddressAll').val(deliveryaddress);
    }
    document.getElementById("all_search_form").submit();
   
}
function getdeliverydata(){
    var cbc = document.getElementsByName('category[]');
    var pickupaddress = $('#pickupaddress').val();
    var deliveryaddress = $('#deliveryaddress').val();
    var orderby = $('#orderby').val();
        var result = '';
        for(var i=0; i<cbc.length; i++) 
        {
               if(cbc[i].checked ) result += (result.length > 0 ? "," : "") + cbc[i].value;
        }

    if(result) {
    $('#categoryIdAll').val(result);
    }
    if(orderby) {
    $('#orderByAll').val(orderby);
    }
    if(pickupaddress) {
    $('#pickupaddressAll').val(pickupaddress);
    }
    if(deliveryaddress) {
    $('#deliveryaddressAll').val(deliveryaddress);
    }
    document.getElementById("all_search_form").submit();
}
function getsubcategorydata(){
     var cbc = document.getElementsByName('category[]');
     var cbc1 = document.getElementsByName('subcategory[]');
	
            var result = '';
            for(var i=0; i<cbc.length; i++) 
            {   
                   if(cbc[i].checked ) result += (result.length > 0 ? "," : "") + cbc[i].value;
            }
            var result1 = '';
            for(var j=0; j<cbc1.length; j++) 
            {
                   if(cbc1[j].checked ) result1 += (result1.length > 0 ? "," : "") + cbc1[j].value;
            }
	
        if(result && result1){
            result=result+"_"+result1;
        } else if(result)  {
             result=result+"_0";
        } else {
             result="0_"+result1;
        }
        
    url='{{url('user/find/deliveries')}}';
    if(result) {
    url += '/'+result;
    } 
    
    location = url;
}
function cleansearch(){
    url='{{url('user/find/deliveries')}}';
    location = url; 
}
</script>

