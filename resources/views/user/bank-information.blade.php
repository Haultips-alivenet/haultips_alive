@extends('layouts.user-dashboard-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')
<div class="col-md-8">
    <div class="_dash_rft _bnkac">
        <div class="row">
            <div class="col-md-12">
                <h3>Account Information</h3>

                @if(Session::has('alert_msg'))
                    <div class="alert alert-{{ Session::get('alert_type') }}">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{ Session::get('alert_msg') }}
                    </div>
                @endif
                <br>
            @if(count($bank_infos))
                @foreach($bank_infos as $bank_info)
                <div class="bnk_list">
                    <span class="edit_btn" onclick="window.location.href='{{ url('user/bank-infomation/delete/' . $bank_info->id) }}';"><i class="fa fa-times" aria-hidden="true"></i></span>
                     <div class="form-group">
                         <label for="">
                            <i class="fa fa-university" aria-hidden="true"></i>Bank Name
                        </label>
                         <div class="_bn">{{ $bank_info->name }}</div>
                     </div>
                     <div class="form-group">
                        <label for="">
                            <i class="fa fa-credit-card" aria-hidden="true"></i>Account Number
                        </label>
                         <div class="_bn_ac">{{ $bank_info->number }}</div>
                     </div>
                     <div class="form-group">
                        <label for="">
                            <i class="fa fa-university" aria-hidden="true"></i>IFSC Code
                        </label>
                        <div class="_bn_ifsc">{{ $bank_info->code }}</div>
                     </div>
                </div>
                @endforeach
                {!! $bank_infos->render() !!}
            @else
                <div class="bnk_list">No Bank Information Found!</div>
            @endif

                <br>
                <div class="form-group text-center">
                    <input type="submit" class="btn btn-color" value="Add New Bank Account" onclick="window.location.href='{{ url('user/bank-infomation/add') }}'">
                </div>
            </div>   
        </div>
    </div>
</div>
@endsection

@section('script')

@endsection

