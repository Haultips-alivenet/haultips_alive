@extends('layouts.user-layout')
@section('title')
    Admin Panel | Haultips
@endsection

@section('body')

<div class="banner">
    <div id="carousel-slider" class="carousel slide carousel-fade" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
  </ol>
 
  <!-- Wrapper for slides -->
  <div class="carousel-inner">
    <div class="item active">
      <img src="public/user/img/banner/slider-img.png" alt="...">
      <div class="carousel-caption">
        <div class="heading-banner">Treasure trove, Book & Haul all over the wonderful states of India </div>
        <p>Haultips deduce that shippers need decisive service at cutthroat rates. With Haultip’s ingenious online transit marketplace, merchants can salvage time and money without immolating the high assistance standards that they insistence.</p>
        <button class="btn color-btn">List Your Delivery</button>
      </div>
    </div>
    <div class="item">
      <img src="public/user/img/banner/slider-img.png" alt="...">
      <div class="carousel-caption">
        <div class="heading-banner">Treasure trove, Book & Haul all over the wonderful states of India </div>
        <p>Haultips deduce that shippers need decisive service at cutthroat rates. With Haultip’s ingenious online transit marketplace, merchants can salvage time and money without immolating the high assistance standards that they insistence.</p>
        <button class="btn color-btn">List Your Delivery</button>
      </div>
    </div>
    <div class="item">
      <img src="public/user/img/banner/slider-img.png" alt="...">
      <div class="carousel-caption">
        <div class="heading-banner">Treasure trove, Book & Haul all over the wonderful states of India </div>
        <p>Haultips deduce that shippers need decisive service at cutthroat rates. With Haultip’s ingenious online transit marketplace, merchants can salvage time and money without immolating the high assistance standards that they insistence.</p>
        <button class="btn color-btn">List Your Delivery</button>
      </div>
    </div>
  </div>
 
  <!-- Controls -->
  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left"></span>
  </a>
  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right"></span>
  </a>
</div> <!-- Carousel -->
</div>
<div class="section deliverd">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8">
                <div class="_bx_left">
                    <h2 class="heading-title">What Need to Delivered?</h2>
                    <ul>
                        <li>
                            <div class="bx_img "><img src="public/user/img/track-booking.png" alt="" class="wow  bounce"></div>
                            <div class="discription">
                                <h4>Truck Booking</h4>
                                <div class="readmore"> <a href="#">Read More <img src="public/user/img/readmore.png" alt=""></a></div>
                            </div>
                        </li>
                        <li>
                            <div class="bx_img"><img src="public/user/img/packer-mover.png" alt="" class="wow  bounce"></div>
                            <div class="discription">
                                <h4>Packers & Movers</h4>
                                <div class="readmore"> <a href="#">Read More <img src="public/user/img/readmore.png" alt=""></a></div>
                            </div>
                        </li>
                        <li>
                            <div class="bx_img"><img src="public/user/img/vehicle-shifting.png" alt="" class="wow  bounce"></div>
                            <div class="discription">
                                <h4>Vehicle Shifting</h4>
                                <div class="readmore"> <a href="#">Read More <img src="public/user/img/readmore.png" alt=""></a></div>
                            </div>
                        </li>
                        <li>
                            <div class="bx_img"><img src="public/user/img/part-load.png" alt="" class="wow  bounce"></div>
                            <div class="discription">
                                <h4>Part Load</h4>
                                <div class="readmore"> <a href="#">Read More <img src="public/user/img/readmore.png" alt=""></a></div>
                            </div>
                        </li>
                    </ul>
                </div>


            </div>
             <div class="col-lg-4 col-md-4">
                 
                <div class="_bx_rft">
                    <h2>Latest Post <div class="_lp_icon"><img src="public/user/img/pin.png" alt=""></div></h2>
                    <div class="clearfix"></div>
                    <div class="_post_list">
                        <div class="_p_l_img"><img src="public/user/img/post_icon.png" alt=""></div>
                        <div class="_p_l_txt">
                        <div class="_p_l_date">20/07-2017 <img src="public/user/img/cale_icon.png" alt=""></div>
                            <h5>General motors car</h5>
                            
                            <div class="_p_l_txt_up">
                             <p>Pickup address</p>
                                Lorem ipsum dolor sit amet, consectetur elit.
                            </div>
                            <div class="_p_l_txt_d">
                                <p>Devices Mobile/Tab</p>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. 
                            </div>

                        </div>
                    </div>
                     <div class="_post_list">
                        <div class="_p_l_img "><img src="public/user/img/post_icon1.png" alt=""></div>
                        <div class="_p_l_txt">
                        <div class="_p_l_date">20/07-2017 <img src="public/user/img/cale_icon.png" alt=""></div>
                            <h5>General motors car</h5>
                            
                            <div class="_p_l_txt_up">
                             <p>Pickup address</p>
                                Lorem ipsum dolor sit amet, consectetur  elit.
                            </div>
                            <div class="_p_l_txt_d">
                                <p>Delivery address</p>
                                Lorem ipsum dolor sit amet, consectetur . 
                            </div>

                        </div>
                    </div>
                     <div class="_post_list">
                        <div class="_p_l_img"><img src="public/user/img/post_icon2.png" alt=""></div>
                        <div class="_p_l_txt">
                        <div class="_p_l_date">20/07-2017 <img src="public/user/img/cale_icon.png" alt=""></div>
                            <h5>Devices Mobile/Tab</h5>
                            
                            <div class="_p_l_txt_up">
                             <p>Pickup address</p>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                            </div>
                            <div class="_p_l_txt_d">
                                <p>Delivery address</p>
                                Lorem ipsum dolor sit amet, consectetur . 
                            </div>

                        </div>
                    </div>
                    <div class="_bx_footer">More</div>
                </div>

             </div>
        </div>
    </div>
</div>
<div class="welcome">
    <div class="container">
        <div class="row">
          <div class="col-lg-5 col-md-5">
              <div class="_bx_wel_img wow  zoomIn">
                 <img src="public/user/img/truck-img.png" alt="" class="img_0">
                 <img src="public/user/img/truck-img1.png" alt="" class="img_1">
              </div>
          </div> 
          <div class="col-lg-7 col-md-7">
             <div class="_bx_wel_content">
                 <h2>Welcome to <span>Haultips</span></h2>
                 <p>Neque porro quisquam est qui dolora sit e porro quisquam est qui
 dolora sit amet, adipisci velit...</p>
                  <div class="_bx_wel_about">
                    <div class="col-md-12 board">
                    <!-- <h2>Welcome to IGHALO!<sup>&trade;</sup></h2>-->
                    <div class="board-inner">
                        <ul class="nav nav-tabs" id="myTab">
                          
                            <li class="active">
                                <a href="#home" aria-controls="home" role="tab" data-toggle="tab" title="User Experience">Vision &amp; Mission
                      <span class="round-tabs one">                           
                      </span>
                                </a></li>

                            <li><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab" title="Sketch">Our Strength
                     <span class="round-tabs two">
                         <i class="icon icon-pencil"></i>
                     </span>
                            </a>
                            </li>
                            <li><a href="#prototyping" aria-controls="prototyping" role="tab" data-toggle="tab" title="Prototyping">Quality Assurance
                     <span class="round-tabs three">
                          <i class="icon icon-layers"></i>
                     </span> </a>
                            </li>


                        </ul></div>

                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="home">

                            <h3 class="head">Our Vision &amp; Mission</h3>
                            <p class="narrow">
                                Lorem ipsum dolor sit amet, his ea mollis fabellas principes. Quo mazim facilis tincidunt ut, utinam saperet facilisi an vim.
                                Nam ultrices et neque vel interdum! Nam tincidunt ut leo a molestie. Duis elit felis, imperdiet et sem et, convallis volutpat quam.
                            </p>
<p class="narrow">
Lorem ipsum dolor sit amet, his ea mollis fabellas principes. Quo mazim facilis tincidunt ut, utinam saperet facilisi an vim.
                                Nam ultrices et neque vel interdum! Nam tincidunt ut leo a molestie.
                            </p>

                            
                            <p>
                                <a href="" class="btn btn-theme"> Read More<span style="margin-left:10px;" class="glyphicon glyphicon-arrow-right"></span></a>
                            </p>
                        </div>
                        <div class="tab-pane fade" id="profile">
                            <h3 class="head ">Our Strength</h3>
                            <p class="narrow ">
                                Lorem ipsum dolor sit amet, his ea mollis fabellas principes. Quo mazim facilis tincidunt ut, utinam saperet facilisi an vim.
                                Nam ultrices et neque vel interdum! Nam tincidunt ut leo a molestie. Duis elit felis, imperdiet et sem et, convallis volutpat quam.
                            </p>
<p class="narrow ">
                                Lorem ipsum dolor sit amet, his ea mollis fabellas principes. Quo mazim facilis tincidunt ut, utinam saperet facilisi an vim.</p>
                            <p>
                                <a href="" class="btn btn-theme"> Read More<span style="margin-left:10px;" class="glyphicon glyphicon-arrow-right"></span></a>
                            </p>

                        </div>
                        <div class="tab-pane fade" id="prototyping">
                            <h3 class="head">Quality Assurance</h3>
                            <p class="narrow ">
                                Lorem ipsum dolor sit amet, his ea mollis fabellas principes. Quo mazim facilis tincidunt ut, utinam saperet facilisi an vim.
                                Nam ultrices et neque vel interdum! Nam tincidunt ut leo a molestie. Duis elit felis, imperdiet et sem et, convallis volutpat quam.
                            </p>
<p class="narrow">
                                Lorem ipsum dolor sit amet, his ea mollis fabellas principes. Quo mazim facilis tincidunt ut, utinam saperet facilisi an vim.
                                Nam ultrices et neque vel interdum! Nam tincidunt ut leo a molestie. Duis elit felis, imperdiet et sem et, convallis volutpat quam.
                            </p>
                            <p>
                                <a href="" class="btn btn-theme"> Read More <span style="margin-left:10px;" class="glyphicon glyphicon-arrow-right"></span></a>
                            </p>
                        </div>
                       
                        <div class="clearfix"></div>
                    </div>

                </div>
                </div>
             </div> 

          </div>  
        </div>
    </div>
</div>
<section class="devilery_gallery">
    <div class="container-fluid">
        <div class="row">
          <div class="col-lg-6 col-md-6">
               <div class="_de_ga_bx_lft">
                   <div class="row">
                       <div class="col-md-6 no-padding">
                         <div class="_de_ga_bx_lft_img">
                         <div class="_icon">
                             <img src="public/user/img/icon_01.png" alt="">
                         </div> 
                             <img src="public/user/img/gallery01.png" alt="" class="wow zoomIn">
                         </div>  
                       </div>
                       <div class="col-md-6 no-padding">
                           <div class="_de_ga_bx_lft_txt">
                               <h3>Household Shifting</h3>
                               <p>Nam ultrices et neque vel interdum! Nam tincidunt ut leo a molestie. Duis elit felis, imperdiet et sem et, convallis volutpat quam.</p>
                               <a href="#" class="btn btn-them">Read More</a>
                           </div>

                       </div>
                   </div>
               </div> 
          </div>


           <div class="col-lg-6 col-md-6">
               <div class="_de_ga_bx_lft">
                   <div class="row">
                       <div class="col-md-6 no-padding">
                         <div class="_de_ga_bx_lft_img">
                         <div class="_icon">
                             <img src="public/user/img/icon_02.png" alt="">
                         </div> 
                             <img src="public/user/img/gallery02.png" alt="" class="wow zoomIn">
                         </div>  
                       </div>
                       <div class="col-md-6 no-padding">
                           <div class="_de_ga_bx_lft_txt" style="background:#373737;">
                               <h3>Household Shifting</h3>
                               <p>Nam ultrices et neque vel interdum! Nam tincidunt ut leo a molestie. Duis elit felis, imperdiet et sem et, convallis volutpat quam.</p>
                               <a href="#" class="btn btn-them">Read More</a>
                           </div>

                       </div>
                   </div>
               </div> 
          </div>
          

        </div>

        <div class="row">

        <div class="col-lg-6 col-md-6">
               <div class="_de_ga_bx_lft">
                   <div class="row">

                   <div class="col-md-6 no-padding">
                           <div class="_de_ga_bx_lft_txt" style="background:#373737;">
                           <div class="_icon">
                             <img src="public/user/img/icon_03.png" alt="">
                         </div>
                               <h3>Household Shifting</h3>
                               <p>Nam ultrices et neque vel interdum! Nam tincidunt ut leo a molestie. Duis elit felis, imperdiet et sem et, convallis volutpat quam.</p>
                               <a href="#" class="btn btn-them">Read More</a>
                           </div>

                       </div>
                       <div class="col-md-6 no-padding">
                         <div class="_de_ga_bx_lft_img">
                          
                             <img src="public/user/img/gallery03.png" alt="" class="wow zoomIn">
                         </div>  
                       </div>
                       
                   </div>
               </div> 
          </div>
        

          <div class="col-lg-6 col-md-6">
               <div class="_de_ga_bx_lft">
                   <div class="row">
                   <div class="col-md-6 no-padding">
                           <div class="_de_ga_bx_lft_txt">
                           <div class="_icon">
                             <img src="public/user/img/icon_04.png" alt="">
                         </div> 
                               <h3>Household Shifting</h3>
                               <p>Nam ultrices et neque vel interdum! Nam tincidunt ut leo a molestie. Duis elit felis, imperdiet et sem et, convallis volutpat quam.</p>
                               <a href="#" class="btn btn-them">Read More</a>
                           </div>

                       </div>
                       <div class="col-md-6 no-padding">
                         <div class="_de_ga_bx_lft_img">
                         
                             <img src="public/user/img/gallery04.png" alt="" class="wow zoomIn">
                         </div>  
                       </div>
                       
                   </div>
               </div> 
          </div>


           
          

        </div>



    </div>
</section>
<section class="how_works">
    <div class="container">
        <div class="row">
       <div class="txt-c">
            <h2 class="text-center">How it <span>works</span></h2>
        <p>Neque porro quisquam est qui Shifting Process Shifting Processdolora sit amet, adipisci velit...</p>
       </div>

            <div class="col-lg-4 col-md-4 ">
                <div class="_h_ws_bx">
                  <div class="_t_icon">1</div>
                   <h3>Packing Material</h3> 
                   <p>When you need to relocate your goods and services, in order to ensure that they are not damaged in any way, choosing right kind of the packing material is important.</p>
                   <img src="public/user/img/work-img-1.png" alt="" class="wow  bounce">
                </div>

            </div>
            <div class="col-lg-4 col-md-4 _b_top">
                <div class="_h_ws_bx">
                <div class="_t_icon">2</div>
                   <h3>Packing Tips</h3> 
                   <p>When you need to relocate your goods and services, in order to ensure that they are not damaged in any way, choosing right kind of the packing material is important.</p>
                   <img src="public/user/img/work-img-2.png" alt="" class="wow  bounce">
                </div>

            </div>
            <div class="col-lg-4 col-md-4 _b_top">
                <div class="_h_ws_bx">
                <div class="_t_icon">3</div>
                   <h3>Transportation Process</h3> 
                   <p>When you need to relocate your goods and services, in order to ensure that they are not damaged in any way, choosing right kind of the packing material is important.</p>
                   <img src="public/user/img/work-img-3.png" alt="" class="wow  bounce">
                </div>

            </div>
        </div>
    </div>
</section>
<section class="any_tm_chosse">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-md-6 no-padding">
                <div class="_any_tm_lft">
                    <div class="_any_tm_lft_txt">
                        <h2>Move any where <span>any time</span></h2>
                    <p>Neque porro quisquam est qui Processdolora sit amet, adipisci velit...</p>
                    <p>Sed mi velit, luctus ac ornare et, mollis et nibh? Vivamus gravida enim eget dolor lobortis congue. In hac habitasse platea dictumst. Donec a dui euismod, pellentesque odio id, congue nulla</p>
                    </div>
                    <div class="feature_img">
                <img src="public/user/img/feature_img.png" alt="" class="wow slideInLeft">
            </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 no-padding">
                <div class="_any_tm_rft">
                <div class="_any_tm_rft_txt">
                    
               
                    <h2>Why Choose <span>Us ?</span></h2>
                    <ul class="_choose_list">
                        <li> 
                            <div class="_w_icon wow slideInRight" ><img src="public/user/img/icon_05.png" alt="" ></div>
                        <h4>Fast Transport Services</h4>
                            <div class="_d_txt">Neque porro quisquam est qui Shifting Process Shifting 
Processdolora sit amet, adipisci velit...</div>  
                         </li>
                        <li>
                        <div class="_w_icon wow slideInRight" ><img src="public/user/img/icon_06.png" alt="" ></div>
                         <h4>Sefety And Reliability</h4>
                            <div class="_d_txt">Neque porro quisquam est qui Shifting Process Shifting 
Processdolora sit amet, adipisci velit...</div>  
                         </li>
                         <li> 
                            <div class="_w_icon wow slideInRight"><img src="public/user/img/icon_07.png" alt="" ></div>
                         <h4>Shipping World Wide</h4>
                            <div class="_d_txt">Neque porro quisquam est qui Shifting Process Shifting 
Processdolora sit amet, adipisci velit...</div>  
                         </li>
                    </ul>
                </div>
                 </div>
            </div>
        </div>
    </div>
</section>
<section class="feature">
    <div class="container">
        <div class="row">
            <div class="_fea_txt_bx">
                <h2>Our Features</h2>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard 
ever since the 1500s</p>
<div class="cleafixed"></div>

            <div class="col-lg-3 col-md-3">
                <div class="_fea_txt_3rd">
                    <div class="f_icon"><img src="public/user/img/f_icon01.png" alt="" class="wow zoomIn"></div>
                    <h4>100% Safe Delivery</h4>
                    <p>Here are a few helpful suggestions that you may take on board, and below.</p>

                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="_fea_txt_3rd">
                    <div class="f_icon"><img src="public/user/img/f_icon02.png" alt="" class="wow zoomIn"></div>
                     <h4>Weather Insuarance</h4>
                    <p>Here are a few helpful suggestions that you may take on board, and below.</p>

                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="_fea_txt_3rd">
                    <div class="f_icon"><img src="public/user/img/f_icon03.png" alt="" class="wow zoomIn"></div>
                     <h4>Fast & On Time</h4>
                    <p>Here are a few helpful suggestions that you may take on board, and below.</p>

                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="_fea_txt_3rd">
                    <div class="f_icon"><img src="public/user/img/f_icon04.png" alt="" class="wow zoomIn"></div>
                     <h4>Support For Your Vehicles</h4>
                    <p>Here are a few helpful suggestions that you may take on board, and below.</p>

                </div>
            </div>
            
            </div>
        </div>
    </div>
</section>
@endsection
