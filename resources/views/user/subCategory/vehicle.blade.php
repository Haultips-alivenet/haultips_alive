@extends('layouts.user-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')



<section class="categories">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="_cate_up_img">
                <img src="img/img_upload_icon.png" alt="" class="wow pulse">
                <div class="clearfix"></div>
                    <div class="_ul_d">
                    Upload Images
                        <input type="file" class="_btn_f" title="Upload images">
                    </div>
                </div>

                <div class="_cate_up_dv">
                    <h2>Delivery Title</h2>
                   
                    
                        <form action="" class="form">
                            
                       <div class="form-group row">
                            <div class="col-md-5">
                                <label for="">Model</label>
                                </div>
                                <div class="col-md-7">
                            <input type="text" class="form-control">
                            </div>
                       </div>
                       <div class="form-group row">
                       <div class="col-md-5">
                            <label for="">Velich Model color</label>
                            </div>
                            <div class="col-md-7">
                            <input type="text" class="form-control">
                            </div>
                       </div>

                       <div class="form-group row">
                       <div class="col-md-5">
                            <label for="">Images</label>
                            </div>
                            <div class="col-md-7">
                            <input type="text" class="form-control">
                            </div>
                       </div>

                        </form>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <hr>
            <div class="row">
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
</section>

@endsection
@section('script')
<script>
 
</script>
@endsection

