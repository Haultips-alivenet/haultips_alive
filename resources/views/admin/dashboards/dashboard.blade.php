@extends('layouts.admin-layout')
@section('title')
    Admin Panel | Haultips
@endsection

@section('body')
<div id="page-wrapper">
                <div class="graphs">
                        <div class="col_3">
                                <div class="col-md-3 widget widget1">
                                        <div class="r3_counter_box">
                                                <i class="lnr lnr-user"></i>
                                                <div class="stats">
                                                  <h5>{{count($user)}}</h5>
                                                  <div class="grow">
                                                        <p>Total Customer</p>
                                                  </div>
                                                </div>
                                        </div>
                                </div>
                                <div class="col-md-3 widget widget1">
                                        <div class="r3_counter_box">
                                                <i class="lnr lnr-users"></i>
                                                <div class="stats">
                                                  <h5>{{count($partner)}}</h5>
                                                  <div class="grow grow1">
                                                        <p>Total Partner</p>
                                                  </div>
                                                </div>
                                        </div>
                                </div>
                                <div class="col-md-3 widget widget1">
                                        <div class="r3_counter_box">
                                                <i class="fa fa-eye"></i>
                                                <div class="stats">
                                                 <h5>{{count($delivered)}}</h5>
                                                  <div class="grow grow3">
                                                        <p>Delivered</p>
                                                  </div>
                                                </div>
                                        </div>
                                 </div>
                                 <div class="col-md-3 widget">
                                        <div class="r3_counter_box">
                                                <i class="fa fa-usd"></i>
                                                <div class="stats">
                                                 <h5>{{count($shipments)}}</h5>
                                                  <div class="grow grow2">
                                                        <p>Shipment</p>
                                                  </div>
                                                </div>
                                        </div>
                                </div>
                                <div class="clearfix"> </div>
                        </div>

        <!-- switches -->
<div class="switches">
        <div class="col-4">
                <div class="col-md-3">
                        <div class="switch-right-grid box">
                        <img src="images/truck_booking.jpg" alt="">
                                <div class="switch-right-grid1">
                                <aside class="code">{{count($truckbooking)}}</aside>
                                        <h3>Truck Booking Deliveries</h3>
                                        <p>Duis aute irure dolor in reprehenderit.</p>

                                </div>
                        </div>

                </div>
                <div class="col-md-3">
                        <div class="switch-right-grid box2">
                        <img src="images/vehicle_shifting.jpg" alt="">
                                <div class="switch-right-grid1 ">

                                        <aside class="code">{{count($vehicle)}}</aside>
                                        <h3>Vehicle Shifting Deliveries</h3>
                                        <p>Duis aute irure dolor in reprehenderit.</p>

                                </div>
                        </div>

                </div>
                <div class="col-md-3">
                        <div class="switch-right-grid box3">
                        <img src="images/packers_movers.jpg" alt="">
                                <div class="switch-right-grid1">
                                <aside class="code">{{count($packers)}}</aside>
                                        <h3>Packers & Movers Deliveries</h3>
                                        <p>Duis aute irure dolor in reprehenderit.</p>

                                </div>
                        </div>

                </div>
                <div class="col-md-3">
                        <div class="switch-right-grid box4">
                        <img src="images/part_loader.jpg" alt="">
                                <div class="switch-right-grid1">
                               <aside class="code">{{count($Partload)}}</aside>
                                        <h3>Part Load Deliveries</h3>
                                        <p>Duis aute irure dolor in reprehenderit.</p>

                                </div>
                        </div>

                </div>
                <div class="clearfix"></div>
        </div>
</div>
<!-- //switches -->

                </div>
			<!--body wrapper start-->
</div>
			 <!--body wrapper end-->
		
 @endsection 
