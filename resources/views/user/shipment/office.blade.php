@extends('layouts.user-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')


<section class="_inner_pages">
    <div class="container">
        <div class="row">
            <div class="_inner_bx">          
                <h2>Office</h2>
                <br>
                <br>

                <div class="clearfix"></div>
               {!! Form::open(array('url'=>'office','class'=>'form-horizontal','id'=>'office-shipment', 'method'=>'POST', 'files'=>true,)) !!}
               <div class="col-md-8">ajax-loader
                   <div class="inputbox-mandat-loading-1"><img src="{{ asset('public/admin/images/ajax-loader')}}" alt=""></div>    
                   
                   <div class="col-md-6">                             
                            <div class="form-group">
                                <label for="">Delivery Title</label>
                                <input type="text" name="title" value=""  id="title" class="office-data">
                             </div>
                        </div>
                <div class="select_opt">
                    <div class="row">
                        <div class="col-md-6">                             
                            <div class="form-group">
                                <label for="">Collection floor</label>
                                {!! Form::select('collectionFloor', ['First Floor', 'Second Floor', 'Third Floor', 'Fourth Floor', 'Fifth Floor', 'Sixth Floor', 'Seventh Floor', 'Eight Floor'], null, ['class'=>'selectpicker']) !!}
                             </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Delivery Floor</label>
                                 {!! Form::select('deliveryFloor', ['First Floor', 'Second Floor', 'Third Floor', 'Fourth Floor', 'Fifth Floor', 'Sixth Floor', 'Seventh Floor', 'Eight Floor'], null, ['class'=>'selectpicker']) !!}
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <br>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-4"><label for="">Lift/Elevatior</label></div>
                                    <div class="col-sm-8">
                                        <div class="radio radio-inline">
                                            <input name="radio1" id="radio1" value="1" checked="" type="radio">
                                            <label for="radio1">
                                                Yes
                                            </label>                                               
                                        </div> 
                                        <div class="radio radio-inline">
                                            <input name="radio1" id="radio2" value="0" checked="" type="radio">
                                           <label for="radio2">
                                               No
                                           </label>
                                       </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>    
               </div>

  
         <div class="row">
             <div class="col-md-12">
                <h4><i>Get the best bids by filling out the information below.</i></h4>
                <h5>Delivery Title</h5>
                <div class="clearfix"></div>
                <p>(e.g..3 Large Boxes) This will become the title of your listing, so be as descriptive as possible.You do not have to include collection or  delivery information here..</p>

                <div class="panel _pnl">
                    <div class="panel-heading">
                        <span class="pull-left clickable"><i class="glyphicon glyphicon-minus"></i>General</span>
                    </div>
                    <div class="panel-body col-md-8">
                        <div class="row">
                            <ul>
                                <?php foreach($general as $generalData){ ?>
                                    <li>
                                        <div class="ch_bx">
                                                <label for="name">{{$generalData->name}}</label>
                                                <input type="text" name="general[]" value="0"  id="general{{$generalData->id}}">
                                                <div class="_btn_act">
                                                <a href="JavaScript:void(0);" class="a_sign" onclick="increaseValue('general<?php echo  $generalData->id ?>')" id="add{{$generalData->id}}">+</a>
                                                <a href="JavaScript:void(0);" class="a_sign" onclick="decreaseValue('general<?php echo  $generalData->id ?>')" id="minus{{$generalData->id}}">-</a>
                                                </div>
                                        </div>  
                                    </li>
                                <?php } ?>                                       
                            </ul>
                        </div>
                    </div>
                </div>


                <div class="panel _pnl">
                <div class="panel-heading">
                    <span class="pull-left clickable"><i class="glyphicon glyphicon-minus"></i>Equipment</span>
                </div>
                    <div class="panel-body col-md-8">
                        <div class="row">
                            <ul>
                                <?php foreach($equipment as $equipmentData){ ?>
                                    <li>
                                        <div class="ch_bx">
                                                <label for="name">{{$equipmentData->name}}</label>
                                                <input type="text" name="equipment[]" value="0"  id="equipment{{$equipmentData->id}}">
                                                <div class="_btn_act">
                                                <a href="JavaScript:void(0);" class="a_sign" onclick="increaseValue('equipment<?php echo  $equipmentData->id ?>')"  id="add{{$equipmentData->id}}">+</a>
                                                <a href="JavaScript:void(0);" class="a_sign" onclick="decreaseValue('equipment<?php echo  $equipmentData->id ?>')" id="minus{{$equipmentData->id}}">-</a>
                                                </div>
                                        </div>  
                                    </li>
                                <?php } ?>                                       
                            </ul>
                        </div>
                    </div>
                </div>


                <div class="panel _pnl">
                    <div class="panel-heading">
                        <span class="pull-left clickable"><i class="glyphicon glyphicon-minus"></i>Miscellaneous Boxes</span>
                    </div>
                    <div class="panel-body col-md-8">
                        <div class="row">
                            <ul>
                                <?php foreach($miscellaneous as $miscellaneousData){ ?>
                                    <li>
                                        <div class="ch_bx">
                                                <label for="name">{{$miscellaneousData->name}}</label>
                                                <input type="text" name="box[]" value="0"  id="misc{{$miscellaneousData->id}}">
                                                <div class="_btn_act">
                                                <a href="JavaScript:void(0);" class="a_sign" onclick="increaseValue('misc<?php echo  $miscellaneousData->id ?>')"  id="add{{$miscellaneousData->id}}">+</a>
                                                <a href="JavaScript:void(0);" class="a_sign" onclick="decreaseValue('misc<?php echo  $miscellaneousData->id ?>')" id="minus{{$miscellaneousData->id}}">-</a>
                                                </div>
                                        </div>  
                                    </li>
                                <?php } ?>                                       
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                    
        <div class="row">
            <div class="col-md-12">
                
                <ul class="img_up_list">
                    
                </ul>
                <div class="clearfix"></div>

                <div class="form-group _fl_upd">
                    <label for="">Upload pictures:</label>
                    <div id="imagePreview"></div>
                    <span> <input id="uploadFile" type="file" multiple  name="image" class="_fl_f" />
                    Add Photo
                    </span>
                </div>
            </div>  <div class="gallery"></div>


            <div class="col-md-6">
                <div class="_add_cmt_bx">
                    <h4>Additional information (Optional) </h4>
                    <p>Additional information:</p>
                    <div class="form-group">
                        <textarea name="additonalInfo" id="" cols="55" rows="5"  class="form-control"></textarea>
                        <small>You have 1200 characters left</small>
                    </div>
                </div>

                <div class="_add_btn_btm">
                    <button class="btn btn-border">Back</button>                    
                    <button type="button" class="btn btn-color" onclick="validate_office('next')">Continue</button>
                </div>
            </div>
    
            <div class="col-md-6">
                <i class="fa fa-question pull-left wow bounceIn" aria-hidden="true"></i>
                <div class="_info_txt">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis 
                </div>   
            </div>
        </div>
               {!! Form::close() !!}
    </div>
    </div>
    </div>
</section>

@endsection
@section('script')
<script type="text/javascript">
$(function() {
    // Multiple images preview in browser
    var imagesPreview = function(input, placeToInsertImagePreview) {

        if (input.files) {
            var filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function(event) {
                    $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                    $('img').wrap('<li />');
                }

                reader.readAsDataURL(input.files[i]);
            }
        }

    };

    $('#uploadFile').on('change', function() {    
         imagesPreview(this, 'ul.img_up_list');  
        
        });
});

function validate_office(action_type){ 
    var actionUrl  = $("#office-shipment").attr('action');
      $(".inputbox-mandat-loading-1").show();
      var file_data = $("#uploadFile").prop("files")[0];
      var ajaxData = new FormData();
    
       if(file_data!='' && file_data!=null && file_data!='undefined'  && file_data!=undefined){
          ajaxData.append("file", file_data);
       }
       
       ajaxData.append("mode", 'office-details');
       ajaxData.append("action_type", action_type);
        
        $('.office-data').each(function(){
            ajaxData.append($(this).attr('name'), encodeURIComponent($(this).val()));
        });

    $.ajax({
        type : 'post',
        async : true,
        cache: false,
        contentType: false,
        processData: false,
        url : actionUrl,//set the dynamic url for ajax
        data: ajaxData,
        error : function(result) {

        },
        success: function(result){ 
           
            $(".inputbox-mandat-loading-1").hide();
            $(".inputbox-mandat-loading-2").hide();
    
            var serverResult = JSON.parse(result);   
            var errorsyntax = 'error-quicksetup-registration-'; 
            var inputsyntax = 'quicksetup-registration-'; 
            $('#websiteHeadingMessage').show();
            $('#error-quicksetup-registration-alias').text('');
            $('#error-quicksetup-registration-alias').hide();
            if(serverResult['status'] == 0){
             
             var focusSet = 0;
             
              $('.quicksetup-registration-value-1').each(function(){
                    var elementObj = $(this);
                    var elementObjId = elementObj.attr('id');
                    var actualNameTemp = elementObjId.split('-');
                    var actualName = actualNameTemp.pop();

                     if(serverResult['field'] == 'dataerror' && 
                        serverResult['data'][actualName]!=undefined &&
                        serverResult['data'][actualName]!=null && 
                        serverResult['data'][actualName]!=''){

                        $('#'+errorsyntax+actualName).text(serverResult['data'][actualName]);
                        $('#'+errorsyntax+actualName).show();
                        if(focusSet ==0){
                         $('#'+inputsyntax+actualName).focus();    
                         focusSet = 1;
                        }
                        
                    }else{
                        $('#'+errorsyntax+actualName).text('');
                        $('#'+errorsyntax+actualName).hide();
                    }
              });
              if(serverResult['field'] == 'dataerror' && 
                 serverResult['data']['wedsite_banner']!=undefined &&
                 serverResult['data']['wedsite_banner']!=null && 
                 serverResult['data']['wedsite_banner']!=''){
             
                    $('#'+errorsyntax+'wedsite_banner').text(serverResult['data']['wedsite_banner']);
                    $('#'+errorsyntax+'wedsite_banner').show();
                    if(focusSet ==0){
                        $('#'+inputsyntax+'wedsite_banner').focus();    
                        focusSet = 1;
                    }
                        
                 }else{
                        $('#'+errorsyntax+'wedsite_banner').text('');
                        $('#'+errorsyntax+'wedsite_banner').hide();
                 }
                 
               if(serverResult['field'] == 'dataerror' && 
                  serverResult['websiteurlhtml']!=undefined &&
                  serverResult['websiteurlhtml']!=null && 
                  serverResult['websiteurlhtml']!=''){
                  
                  $('#error-quicksetup-registration-alias').text('This website name is already in use.');
                  $('#error-quicksetup-registration-alias').show();  
                  $('.suggested-wedsite-url').show();  
                  $('#websiteHeadingMessage').hide();
                  if($('#quicksetup-registration-groom_first_name').val() != '' && $('#quicksetup-registration-bride_first_name').val() != ''){
                    $('#quicksetup-registration-alias').val($('#quicksetup-registration-groom_first_name').val().toLowerCase()+'-weds-'+$('#quicksetup-registration-bride_first_name').val().toLowerCase());   
                    $('#quicksetup-registration-alias-reflection').text($('#quicksetup-registration-alias').val().toLowerCase());
                    setCookie('quicksetup-registration-alias',$('#quicksetup-registration-alias').val(),1);
                  }
                  $('.suggested-wedsite-url').html(serverResult['websiteurlhtml']);
                }
               quicksetupprenext(serverResult['action_type']);
         }
       } 
    });

}

</script>
</script>
@endsection

