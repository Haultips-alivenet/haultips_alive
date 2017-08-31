@extends('layouts.user-layout')
@section('title')
    Admin Panel | Haultips
@endsection

@section('body')


<section class="main_pages">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3">
                
                <div class="_fn_de_lft">
                    <div class="form-horizontal">
                        <div class="form-group">
                         <h3> Clean Search</h3>
                        <div class="input-group">
                            <input type="text" class="form-control" value="" placeholder="">
                            <span class="input-group-addon">Search</span>
                        </div>
                        <hr>
                        <h3>Refine Search</h3>
                        <div class="_srch_list">
                            <ul>
                                <li>
                                
                                <div class="checkbox">
                                <input id="checkbox1" type="checkbox">
                                <label for="checkbox1">Vehicle Shifting(0)</label>
                                </div>
                                <i class="fa fa-plus" onclick="set_toggle()"></i>
                                <ul>
                                <li><label><input name="" value="1" type="checkbox"> Cars &amp; Light Trucks (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> Caravans &amp; Campers (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> Trailers (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> Mobile Homes (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> Vehicle Parts (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> Airplanes (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> Antique Vehicles (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> Heavy Trucks &amp; Construction Vehicles (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> Tractors &amp; Agricultural Equipment (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> Other Vehicles (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> Motorcycles (0)</label> </li> 

                                </ul>


                                </li>
                                <li>
                                

                                <div class="checkbox">
                                <input id="checkbox2" type="checkbox">
                                <label for="checkbox2">Packers &amp; Movers (0)</label>
                                </div>

                                <i class="testt-show fa fa-plus"></i>

                                <ul class="cat-list test-dwn10" style="display: none;">
                                <li>

                              
                                <div class="checkbox">
                                <input id="checkbox3" type="checkbox">
                                <label for="checkbox3">Small Studio (0)</label>
                                </div>


                                 </li>
                                <li><label><input name="" value="1" type="checkbox"> Large Studio or 1 Bed Flat/Apt (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> 2 Bed Apt (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> 3 Bed Apt or 2 Bed House (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> 3 Bed House (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> 4 Bed House (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> 5+ Bed House (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> Office (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> Mobile Homes (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> Tradeshow Equipment (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> Mobile Homes (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> Other Household &amp; Office Moves (0)</label> </li> 
                                </ul>

                                </li>
                               
                                
                                

                                <li>
                                <div class="checkbox">
                                <input id="checkbox5" type="checkbox">
                                <label for="checkbox5">Part Load (0)</label>
                                </div>
                                <i class="testt-show fa fa-plus" onclick=""></i>

                                <ul class="cat-list test-dwn47" style="display: none;"><li><label><input name="48" value="1" type="checkbox"> New Commercial Goods (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> Used Commercial Goods (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> Wine, Liquor, Beer &amp; Spirits (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> Non-Perishable Foods &amp; Beverages (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> Other Less-than-Truckload Goods (0)</label> </li> 
                                </ul>
                                </li>


                                <li>
                                <div class="checkbox">
                                <input id="checkbox8" type="checkbox">
                                <label for="checkbox8">Truck Booking (0)</label>
                                </div>
                                <i class="testt-show fa fa-plus" onclick=""></i>

                                <ul class="cat-list " style="display: none;"><li>


                                <label><input name="57" value="1" type="checkbox"> Wood Paper Products (0)</label> </li>
                                <li><label>


                                <input name="" value="1" type="checkbox"> Liquids, Gases &amp; Chemicals (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> Construction Material, Equipment &amp; Machinery (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> Commodities Dry Bulk (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> General Freight (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> Mixed Freight (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> Utilities (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> Pharmaceutical Products (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> Fertilisers (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> Plastics &amp; Rubber (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> Textiles, Leather (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> Miscellaneous Manufactured Products (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> Tradeshow Equipment (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> Office Equipment (0)</label> </li>
                                <li><label><input name="" value="1" type="checkbox"> Other Business &amp; Industrial Goods (0)</label> </li>
                                </ul>
                                </li>
                            </ul>
                        </div>


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
                            <input type="text" class="  search-query form-control" placeholder="Search" />
                            <span class="input-group-btn">
                                <button class="btn btn-cl" type="button">
                                    <span class=" glyphicon glyphicon-search"></span>
                                </button>
                            </span>
                        </div>
                          <small>e.g. Richmond, Surrey or TW9</small>

                           
                            <div class="form-group _lin">
                            <label for="exampleInputName2">Radius</label>
                           
                            <select name="" id="" class="form-control">
                                <option value="">100</option>
                                <option value="">200</option>
                                <option value="">300</option>
                            </select>
                            Km
                            </div>
                            </form>

                    </div>
                    <div class="_delivery">
                    <form class="form-inline">
                        <h3>Delivery</h3>
                        <ul>
                            <li><a href="#">City</a></li>
                            <li><a href="#">All</a></li>
                        </ul>
                        <div class="clearfix"></div>
                        <div class="input-group col-md-12">
                                <input type="text" class="  search-query form-control" placeholder="Search" />
                                <span class="input-group-btn">
                                    <button class="btn btn-cl" type="button">
                                        <span class=" glyphicon glyphicon-search"></span>
                                    </button>
                                </span>

                            </div>

                                <small>e.g. Richmond, Surrey or TW9</small>


                            
                            <div class="form-group _lin">
                            <label for="exampleInputName2">Radius</label>
                             <select name="" id="" class="form-control">
                                <option value="">100</option>
                                <option value="">200</option>
                                <option value="">300</option>
                            </select> km
                            </div>
                            </form>

                    </div>
                    <div class="_price">
                    <form class="form-inline">
                        <h3>Price</h3>
                        
                        <div class="clearfix"></div>

                        <div class="input-group">
                          <div class="col-md-6 no-padding"> <span class="pull-left" style="padding-right:13px;">Price</span><input type="text" class="form-control"> </div>
                          <div class="col-md-6 no-padding"> <span class="pull-left" style="padding-right: 13px;">- To - </span> <input type="text" class="form-control"> </div>

                        </div>

<b>€ 10</b> <input id="ex2" type="text" class="span2" value="" data-slider-min="10" data-slider-max="1000" data-slider-step="5" data-slider-value="[250,450]"/> <b>€ 1000</b>

                        </form>  

                    </div>

                </div>

            </div>
            <div class="col-lg-9 col-md-9">
                <div class="_fn_mp_rft">
                <div class="panel panel-primary">
                <div class="panel-heading">
                <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i>View Map</span>
                    <h3 class="panel-title">Panel 1</h3>
                    
                </div>
                    <div id="map_container"  class="panel-body">
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
                                    <input name="radio1_2" id="radio1_2" value="option2"  type="radio">
                                    <label for="radio1_2">Start <i class="fa fa-map-marker" aria-hidden="true"></i>
</label>                              <span> OR</span>
                                    </div>


                                    <div class="radio radio-danger radio-inline">
                                 
                                    <input name="radio1_2" id="radio2_3" value="option2"  type="radio">
                                    <label for="radio2_3">End <i class="fa fa-map-marker" aria-hidden="true"></i>
</label>
                                    </div>

                                </div>
                                <div class="col-lg-4 col-md-4">
                                    
                                <div class="radio radio-success radio-inline">
                                <input name="radio1_4" id="radio1_4" value="option2"  type="radio"> 
                                <label for="radio1_4">Start <i class="fa fa-map-marker" aria-hidden="true"></i>
</label>                         <span> OR</span>
                                </div>

                                <div class="radio radio-danger radio-inline">
                                <input name="radio1_4" id="radio2_4" value="option2"  type="radio">
                                <label for="radio2_4">End <i class="fa fa-map-marker" aria-hidden="true"></i>
</label>
                                </div>

                                </div>

                                <div class="col-lg-2 col-md-2">
                                    
                                    <div class="radio radio-success radio-inline">
                                    <input name="radio1_5" id="radio1_5" value="option2"  type="radio">
                                    <label for="radio1_5">Start <i class="fa fa-map-marker" aria-hidden="true"></i>
</label>
                                    </div>

                                   
                                </div>
                                <div class="col-lg-2 col-md-2">
                                    <div class="radio radio-danger radio-inline">
                                    <input name="radio1_5" id="radio1_6" value=""  type="radio">
                                    <label for="radio1_6">End <i class="fa fa-map-marker" aria-hidden="true"></i>
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
                        <div class="col-md-9">
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

                </div>
                    <table class="table table-striped table-hover">
                    <thead>
                    <tr>

                    <th style="width:30%">Featured Listings</th>
                    <th style="width:20%">Price</th>
                    <th style="width:15%">Origin</th>
                    <th style="width:20%">Destination</th>
                    <th style="width:10%">Distance</th>
                    <th style="width:10%">Ending</th>
                    </tr>
                    </thead>


                    <tbody  style="display: table-row-group;"></tbody>

                    <tbody  style="display: table-row-group;">
                    <tr>
                    <td> <img src="public/user/img/furnichure_icon.png" alt="" class="pull-left"> <span class="_details">Furniture
                    Weight: 40 kg</span></td>
                    <td><span class="_quote">1 quote Low: INR 2,000</span> </td>
                    <td><span>New Delhi Saitabihar</span></td>
                    <td><span>New Delhi Saitabihar</span></td>
                    <td><span class="_wet">14 km</span></td>
                    <td><span class="_date">16-08-17</span></td>
                    </tr>  


                    <tr>
                    <td> <img src="public/user/img/furnichure_icon.png" alt="" class="pull-left"> <span class="_details">Furniture
                    Weight: 40 kg</span></td>
                    <td><span class="_quote">1 quote Low: INR 2,000</span> </td>
                    <td><span>New Delhi Saitabihar</span></td>
                    <td><span>New Delhi Saitabihar</span></td>
                    <td><span class="_wet">14 km</span></td>
                    <td><span class="_date">16-08-17</span></td>
                    </tr>


                    <tr>
                    <td> <img src="public/user/img/furnichure_icon.png" alt="" class="pull-left"> <span class="_details">Furniture
                    Weight: 40 kg</span></td>
                    <td><span class="_quote">1 quote Low: INR 2,000</span> </td>
                    <td><span>New Delhi Saitabihar</span></td>
                    <td><span>New Delhi Saitabihar</span></td>
                    <td><span class="_wet">14 km</span></td>
                    <td><span class="_date">16-08-17</span></td>
                    </tr> 

                    <tr>
                    <td> <img src="public/user/img/furnichure_icon.png" alt="" class="pull-left"> <span class="_details">Furniture
                    Weight: 40 kg</span></td>
                    <td><span class="_quote">1 quote Low: INR 2,000</span> </td>
                    <td><span>New Delhi Saitabihar</span></td>
                    <td><span>New Delhi Saitabihar</span></td>
                    <td><span class="_wet">14 km</span></td>
                    <td><span class="_date">16-08-17</span></td>
                    </tr> 

                    <tr>
                    <td> <img src="public/user/img/furnichure_icon.png" alt="" class="pull-left"> <span class="_details">Furniture
                    Weight: 40 kg</span></td>
                    <td><span class="_quote">1 quote Low: INR 2,000</span> </td>
                    <td><span>New Delhi Saitabihar</span></td>
                    <td><span>New Delhi Saitabihar</span></td>
                    <td><span class="_wet">14 km</span></td>
                    <td><span class="_date">16-08-17</span></td>
                    </tr> 

                    <tr>
                    <td> <img src="public/user/img/furnichure_icon.png" alt="" class="pull-left"> <span class="_details">Furniture
                    Weight: 40 kg</span></td>
                    <td><span class="_quote">1 quote Low: INR 2,000</span> </td>
                    <td><span>New Delhi Saitabihar</span></td>
                    <td><span>New Delhi Saitabihar</span></td>
                    <td><span class="_wet">14 km</span></td>
                    <td><span class="_date">16-08-17</span></td>
                    </tr> 

                    <tr>
                    <td> <img src="public/user/img/furnichure_icon.png" alt="" class="pull-left"> <span class="_details">Furniture
                    Weight: 40 kg</span></td>
                    <td><span class="_quote">1 quote Low: INR 2,000</span> </td>
                    <td><span>New Delhi Saitabihar</span></td>
                    <td><span>New Delhi Saitabihar</span></td>
                    <td><span class="_wet">14 km</span></td>
                    <td><span class="_date">16-08-17</span></td>
                    </tr> 

                    <tr>
                    <td> <img src="public/user/img/furnichure_icon.png" alt="" class="pull-left"> <span class="_details">Furniture
                    Weight: 40 kg</span></td>
                    <td><span class="_quote">1 quote Low: INR 2,000</span> </td>
                    <td><span>New Delhi Saitabihar</span></td>
                    <td><span>New Delhi Saitabihar</span></td>
                    <td><span class="_wet">14 km</span></td>
                    <td><span class="_date">16-08-17</span></td>
                    </tr>  

                    </tbody>
                    </table>


                    <div class="row">
                        <div class="col-md-7">
                    <div class="page-nation">
                    <ul class="pagination pagination-large">
                    <li class="disabled"><span>«</span></li>
                    <li class="active"><span>1</span></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li><a href="#">6</a></li>
                    <li><a href="#">7</a></li>
                    <li><a href="#">8</a></li>
                    <li><a href="#">9</a></li>
                    <li class="disabled"><span>...</span></li><li>
                    <li><a rel="next" href="#">Next</a></li>

                    </ul>
                    </div>
                            
                            
                        </div>
                        <div class="col-md-5"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


