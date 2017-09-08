@extends('layouts.user-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')


<section class="main_signup _login_bg ">
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
               
                <div class="_bx_left  _shipment">
                    <h2 class="heading-title">What Need to Delivered?</h2>
                    <h4 class="pull-left">{{$category->category_name}}</h4>
                   <a href="JavaScript:void(0);" class="pull-right">Change Category</a>

                   <div class="clearfix"></div>
                   <br>
                   <br>
                    <div class="_packer_mover_bx">
                        <div class="row">
                            
                            <?php foreach($subCategories as $subCategory){ ?>
                                <div class="col-md-3 border-right">
                                    <div class="_p_m_bx">
                                       <?php $urlName = $str=preg_replace('/\s+/', '', $subCategory->category_name); ?>
                                        <a href="{{URL :: asset(strtolower($urlName).'/'.urlencode(base64_encode($subCategory->id)))}}" class="wow  bounce">
                                          <span>  <img src="{{ asset('public/user/img/'.$subCategory->category_image)}}" alt=""></span>
                                            <p>{{$subCategory->category_name}}</p>
                                        </a>
                                    </div>
                                </div>
                            <?php }?>
                        </div>
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

