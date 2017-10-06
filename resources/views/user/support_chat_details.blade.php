@extends('layouts.user-dashboard-layout') @section('title') Online Truck Booking | Packers and Movers - HAULTIPS @endsection @section('body')

<div class="col-md-8">
    <div class="_dash_rft">
        <div class="panel _panel" id="#messages-main">
            <div class="ms-body">
                <div class="action-header clearfix">
                    <div class="visible-xs" id="ms-menu-trigger">
                        <i class="fa fa-bars"></i>
                    </div>

                    <div class="pull-left hidden-xs">
                        <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="" class="img-avatar m-r-10">
                        <div class="lv-avatar pull-left">

                        </div>
                        <span>David Parbell</span>
                    </div>

                    <ul class="ah-actions actions">
                        <li>
                            <a href="">
                                <i class="fa fa-trash"></i>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <i class="fa fa-check"></i>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <i class="fa fa-clock-o"></i>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="" data-toggle="dropdown" aria-expanded="true">
                                <i class="fa fa-sort"></i>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-right">
                                <li>
                                    <a href="">Latest</a>
                                </li>
                                <li>
                                    <a href="">Oldest</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="" data-toggle="dropdown" aria-expanded="true">
                                <i class="fa fa-bars"></i>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-right">
                                <li>
                                    <a href="">Refresh</a>
                                </li>
                                <li>
                                    <a href="">Message Settings</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="height_bx">
                    <div class="message-feed media">
                        <div class="pull-left">
                            <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="" class="img-avatar">
                        </div>
                        <div class="media-body">
                            <div class="mf-content">
                                Quisque consequat arcu eget odio cursus, ut tempor arcu vestibulum. Etiam ex arcu, porta a urna non, lacinia pellentesque orci. Proin semper sagittis erat, eget condimentum sapien viverra et. Mauris volutpat magna nibh, et condimentum est rutrum a. Nunc sed turpis mi. In eu massa a sem pulvinar lobortis.
                            </div>
                            <small class="mf-date"><i class="fa fa-clock-o"></i> 20/02/2015 at 09:00</small>
                        </div>
                    </div>

                    <div class="message-feed right">
                        <div class="pull-right">
                            <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="" class="img-avatar">
                        </div>
                        <div class="media-body">
                            <div class="mf-content">
                                Mauris volutpat magna nibh, et condimentum est rutrum a. Nunc sed turpis mi. In eu massa a sem pulvinar lobortis.
                            </div>
                            <small class="mf-date"><i class="fa fa-clock-o"></i> 20/02/2015 at 09:30</small>
                        </div>
                    </div>

                    <div class="message-feed media">
                        <div class="pull-left">
                            <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="" class="img-avatar">
                        </div>
                        <div class="media-body">
                            <div class="mf-content">
                                Etiam ex arcumentum
                            </div>
                            <small class="mf-date"><i class="fa fa-clock-o"></i> 20/02/2015 at 09:33</small>
                        </div>
                    </div>

                    <div class="message-feed right">
                        <div class="pull-right">
                            <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="" class="img-avatar">
                        </div>
                        <div class="media-body">
                            <div class="mf-content">
                                Etiam nec facilisis lacus. Nulla imperdiet augue ullamcorper dui ullamcorper, eu laoreet sem consectetur. Aenean et ligula risus. Praesent sed posuere sem. Cum sociis natoque penatibus et magnis dis parturient montes,
                            </div>
                            <small class="mf-date"><i class="fa fa-clock-o"></i> 20/02/2015 at 10:10</small>
                        </div>
                    </div>

                    <div class="message-feed media">
                        <div class="pull-left">
                            <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="" class="img-avatar">
                        </div>
                        <div class="media-body">
                            <div class="mf-content">
                                Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Etiam ac tortor ut elit sodales varius. Mauris id ipsum id mauris malesuada tincidunt. Vestibulum elit massa, pulvinar at sapien sed, luctus vestibulum eros. Etiam finibus tristique ante, vitae rhoncus sapien volutpat eget
                            </div>
                            <small class="mf-date"><i class="fa fa-clock-o"></i> 20/02/2015 at 10:24</small>
                        </div>
                    </div>
                </div>

                <div class="msb-reply">
                    <textarea placeholder="What's on your mind..."></textarea>
                    <button><i class="fa fa-paper-plane-o"></i></button>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
</script>
@endsection