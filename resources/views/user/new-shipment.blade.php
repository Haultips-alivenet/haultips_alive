@extends('layouts.user-dashboard-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')
<div class="col-md-8">
    <div class="_bx_left  _ship_inner">
        <h2 class="heading-title">What Need to Delivered?</h2>
        <ul>
        @foreach($vehicle_cat as $cat)
            <li>
                <div class="bx_img ">
                    <img src="{{ asset('public/user/img/' . $cat->category_image) }}" alt="" class="wow  bounce" style="visibility: visible; animation-name: bounce;">
                </div>
                <div class="discription">
                    <h4>{{ $cat->category_name }}</h4>
                    <div class="readmore"> 
                        <a href="{{ url('subCategory/' . $cat->id) }}">Read More <img src="{{ asset('img/readmore.png') }}" alt=""></a>
                    </div>
                </div>
            </li>
        @endforeach
        </ul>
    </div>
</div>
@endsection

@section('script')

@endsection

