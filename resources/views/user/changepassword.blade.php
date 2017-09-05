@extends('layouts.user-dashboard-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')

            <div class="col-md-8">
                 <div class="_dash_rft _dash_m">
                    <h3>Change Password</h3> 
                    
                        <div class="_dash_mbx">
                            <div class="form-group">
                            <input type="password" placeholder="Enter your Current Password"  class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="password" placeholder="Enter your New Password"  class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="password" placeholder="Enter your Confirm Password"  class="form-control">
                        </div>
                        
                        </div>
                        <hr>
                        <div class="form-group">
                            
                            <button class="btn btn-color">Submit</button>
                        </div>

                 </div>   
            </div>
@endsection
