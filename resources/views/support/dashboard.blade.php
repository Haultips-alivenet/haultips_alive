@extends('layouts.admin-layout') @section('title') Admin Panel | Haultips @endsection @section('body')
<div id="page-wrapper">
    <div class="row">
        <div class="col-md-4">
            <a href="#" title="Example tile shortcut" class="tile-box tile-box-shortcut btn-danger">
                <span class="bs-badge badge-absolute">10</span>
                <div class="tile-header">Users Chatting</div>
                <div class="tile-content-wrapper"><i class="glyph-icon fa fa-comments"></i>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="#" title="Example tile shortcut" class="tile-box tile-box-shortcut btn-success">
                <span class="bs-badge badge-absolute">12</span>
                <div class="tile-header">Messages Send in the Last 24 Hours</div>
                <div class="tile-content-wrapper"><i class="glyph-icon fa fa-clock-o"></i>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="#" title="Example tile shortcut" class="tile-box tile-box-shortcut btn-info">
                <span class="bs-badge badge-absolute">23</span>
                <div class="tile-header">Total number Users</div>
                <div class="tile-content-wrapper"><i class="glyph-icon fa fa-users"></i>
                </div>
            </a>
        </div>

    </div>


    

    <!--body wrapper start-->
</div>
<!--body wrapper end-->

@endsection