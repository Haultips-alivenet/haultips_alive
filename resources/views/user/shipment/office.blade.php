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
               
               <div class="col-md-8">
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
                                            <input name="radio1" id="radio1" value="option1" checked="" type="radio">
                                            <label for="radio1">
                                                Yes
                                            </label>                                               
                                        </div> 
                                        <div class="radio radio-inline">
                                            <input name="radio1" id="radio2" value="option2" checked="" type="radio">
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
                                                <input type="text" name="french-hens" value="0"  id="qty1">
                                                <div class="_btn_act">
                                                <a href="JavaScript:void(0);" class="a_sign" onclick="increaseValue()"  id="add1">+</a>
                                                <a href="JavaScript:void(0);" class="a_sign" onclick="decreaseValue()" id="minus1">-</a>
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
                                                <input type="text" name="french-hens" value="0"  id="qty1">
                                                <div class="_btn_act">
                                                <a href="JavaScript:void(0);" class="a_sign" onclick="increaseValue()"  id="add1">+</a>
                                                <a href="JavaScript:void(0);" class="a_sign" onclick="decreaseValue()" id="minus1">-</a>
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
                                                <input type="text" name="french-hens" value="0"  id="qty1">
                                                <div class="_btn_act">
                                                <a href="JavaScript:void(0);" class="a_sign" onclick="increaseValue()"  id="add1">+</a>
                                                <a href="JavaScript:void(0);" class="a_sign" onclick="decreaseValue()" id="minus1">-</a>
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
                        <textarea name="" id="" cols="55" rows="5"  class="form-control"></textarea>
                        <small>You have 1200 characters left</small>
                    </div>
                </div>

                <div class="_add_btn_btm">
                    <button class="btn btn-border">Back</button><button class="btn btn-color">Continue</button>
                </div>
            </div>
    
            <div class="col-md-6">
                <i class="fa fa-question pull-left wow bounceIn" aria-hidden="true"></i>
                <div class="_info_txt">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis 
                </div>   
            </div>
        </div>
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
                }

                reader.readAsDataURL(input.files[i]);
            }
        }

    };

    $('#uploadFile').on('change', function() {
      var img_list = imagesPreview(this, 'div.gallery');
      $('<div>', {
    id: 'foo',
    href: 'http://google.com',
    title: 'Become a Googler'
}).appendTo('.gallery');
    });
});
</script>
</script>
@endsection
