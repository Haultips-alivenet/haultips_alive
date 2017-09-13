@extends('layouts.user-dashboard-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')

<div class="col-md-8">
    <div class="_dash_rft">
        <span class="edit_btn"><i class="fa fa-pencil" aria-hidden="true"></i></span>
        <div class="row">
            
            <div class="col-md-9 col-md-offset-1">
           
            <form class="_form row">
             <h4>Your personal information</h4>
                <div class="form-group col-md-12">
                    <div class="col-md-4"><label for="">First Name</label></div>
                    <div class="col-md-8"><div class="_me_dis">{{ $user->first_name }}</div>

                    <div class="_me_input"><input type="text" class="form-control"></div> </div>
                </div>
                <div class="form-group col-md-12">
                    <div class="col-md-4"><label for="">Last Name</label></div>
                    <div class="col-md-8"><div class="_me_dis">{{ $user->last_name }}</div>

                    <div class="_me_input"><input type="text" class="form-control"></div></div>
                </div>
                <div class="form-group col-md-12">
                    <div class="col-md-4"><label for="">Email Id</label></div>
                    <div class="col-md-8"><div class="_me_dis">{{ $user->email }}</div>

                    <div class="_me_input"><input type="text" class="form-control"></div></div>
                </div>
                <div class="form-group col-md-12">
                    <div class="col-md-4"><label for="">Phone No</label></div>
                    <div class="col-md-8"><div class="_me_dis">{{ $user->country_code . '-' . $user->mobile_number }}</div>

                    <div class="_me_input"><input type="text" class="form-control"></div></div>
                </div>
                <div class="clearfix"></div>
                <hr>
                <h4>Your Address</h4>
                <div class="form-group col-md-12">
                    <div class="col-md-4"><label for="">Street</label></div>
                    <div class="col-md-8"><div class="_me_dis">{{ $user_detail->street }}</div>

                    <div class="_me_input"><input type="text" class="form-control"></div></div>
                </div>
                
                <div class="form-group col-md-12">
                    <div class="col-md-4"><label for="">City</label></div>
                   <div class="col-md-8"> <div class="_me_dis">{{ $user_detail->city }}</div>

                   <div class="_me_input"><input type="text" class="form-control"></div></div>
                </div>

                <div class="form-group col-md-12">
                    <div class="col-md-4"><label for="">Enter a Location</label></div>
                    <div class="col-md-8"><div class="_me_dis">{{ $user_detail->location }}</div>

                    <div class="_me_input"><input type="text" class="form-control"></div></div>
                </div>

                <div class="form-group col-md-12">
                    <div class="col-md-4"><label for="">Pin Code</label></div>
                    <div class="col-md-8"><div class="_me_dis">{{ $user_detail->pincode }}</div>

                    <div class="_me_input"><input type="text" class="form-control"></div></div>
                </div>

                <div class="form-group col-md-12">
                    <div class="col-md-4"><label for="">Country</label></div>
                    <div class="col-md-8"><div class="_me_dis">{{ $user_detail->country }}</div>

                    <div class="_me_input"><input type="text" class="form-control"></div></div>
                </div>

                <div class="clearfix"></div>
                <hr>
                 <div class="form-group col-md-12 _me_input">
                     <input type="sumbit" class="btn btn-color" value="Submit">
                 </div>
                </form>
            </div>
        </div>
      </div>
</div>
@endsection
