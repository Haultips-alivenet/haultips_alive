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
                    @if(Auth::user()->user_type_id == 3)
                        <a href="{{ url('subCategory/' . $cat->id) }}">Read More <img src="{{ asset('img/readmore.png') }}" alt=""></a>
                    @endif
                    @if(Auth::user()->user_type_id == 2)
                        <a href="javascript:void(0);" onclick="findDeliv('{{ $cat->id }}')">Find Delivery <img src="{{ asset('img/readmore.png') }}" alt=""></a>
                        <input type="hidden" name="category[]" value="{{ $cat->id }}">
                        <br>
                        <a href="javascript:allDeliv();" class="btn btn-color"></a>

                    @endif
                    </div>
                </div>
            </li>
        @endforeach
        </ul>
    </div>
</div>

{!! Form::open(array('url'=>'user/find/deliveries', 'id'=>'find_deliv_form')) !!}
    {!! Form::hidden('categoryIdAll', '', array('id'=>'categoryIdAll')) !!}
    {!! Form::hidden('orderByAll', '', array('id'=>'orderByAll')) !!}
    {!! Form::hidden('pickupaddressAll', '', array('id'=>'pickupaddressAll')) !!}
    {!! Form::hidden('deliveryaddressAll', '', array('id'=>'deliveryaddressAll')) !!}
{!!Form::close()!!}

@endsection

@section('script')
<script type="text/javascript">
function findDeliv(cat_id){
    $('#categoryIdAll').val(cat_id);
    $('#find_deliv_form').submit();
}

function allDeliv(){
    var cbc = document.getElementsByName('category[]');
    var result = '';
    for(var i=0; i<cbc.length; i++) 
    {
        if(cbc[i].checked ) result += (result.length > 0 ? "," : "") + cbc[i].value;
    }
    if(result) {
        $('#categoryIdAll').val(result);
        $('#find_deliv_form').submit();
    }
}
</script>
@endsection

