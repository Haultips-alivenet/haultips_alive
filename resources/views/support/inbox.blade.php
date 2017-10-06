@extends('layouts.admin-layout') @section('title') Admin Panel | Haultips @endsection @section('body')
<div id="page-wrapper">
    <div class="row">
        <div class="col-md-12">
            <div class="mailbox-content">
                <div class="mail-toolbar clearfix">
                    <div class="float-left">
                        <div class="btn btn_1 btn-default mrg5R">
                            <i class="fa fa-refresh"> </i>
                        </div>
                        <div class="dropdown">
                            <a href="#" title="" class="btn btn-default" data-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-cog icon_8"></i>
                                <i class="fa fa-chevron-down icon_8"></i>
                                <div class="ripple-wrapper"></div>
                            </a>
                            <ul class="dropdown-menu float-right">
                                <li>
                                    <a href="#" title="">
                                        <i class="fa fa-pencil-square-o icon_9"></i> Edit
                                    </a>
                                </li>
                                <li>
                                    <a href="#" title="">
                                        <i class="fa fa-calendar icon_9"></i> Schedule
                                    </a>
                                </li>
                                <li>
                                    <a href="#" title="">
                                        <i class="fa fa-download icon_9"></i> Download
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="#" class="font-red" title="">
                                        <i class="fa fa-times" icon_9=""></i> Delete
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="clearfix"> </div>
                    </div>
                    <div class="float-right">

                        <span class="text-muted m-r-sm">Showing 20 of 346 </span>
                        <div class="btn-group m-r-sm mail-hidden-options" style="display: inline-block;">
                            <div class="btn-group">
                                <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-folder"></i> <span class="caret"></span></a>
                                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                    <li><a href="#">Social</a></li>
                                    <li><a href="#">Forums</a></li>
                                    <li><a href="#">Updates</a></li>
                                    <li class="divider"></li>
                                    <li><a href="#">Spam</a></li>
                                    <li><a href="#">Trash</a></li>
                                    <li class="divider"></li>
                                    <li><a href="#">New</a></li>
                                </ul>
                            </div>
                            <div class="btn-group">
                                <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-tags"></i> <span class="caret"></span></a>
                                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                    <li><a href="#">Work</a></li>
                                    <li><a href="#">Family</a></li>
                                    <li><a href="#">Social</a></li>
                                    <li class="divider"></li>
                                    <li><a href="#">Primary</a></li>
                                    <li><a href="#">Promotions</a></li>
                                    <li><a href="#">Forums</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="btn-group">
                            <a class="btn btn-default"><i class="fa fa-angle-left"></i></a>
                            <a class="btn btn-default"><i class="fa fa-angle-right"></i></a>
                        </div>

                    </div>
                </div>
                <table class="table table-fhr">
                    <tbody>
                        <?php foreach($chatdetails as $value) { ?>
                        <tr class="unread checked">
                            <td class="hidden-xs">
                                <input type="checkbox" class="checkbox">
                            </td>
                            <td>
                              <a href="{{url('support/user/chatdetails/'.$value->user_id)}}">{{$value->first_name}}</a>
                            </td>
                            <td>
                            </td>
                            <td>
                                {{date('d-M',strtotime($value->created_at))}}
                            </td>
                        </tr>
                        <?php } ?>    
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<!--body wrapper end-->

@endsection