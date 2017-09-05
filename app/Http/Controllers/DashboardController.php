<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\ShippingDetail;
use Session;
use DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userData = Session::get('currentUser');
        //$firstName = $userData['first_name'];
        //$lastName = $userData['last_name'];
        $data["user"] =   DB::table('users')
                          ->where('user_type_id',3)
                          ->select('id')
                          ->get();
        $data["partner"]= DB::table('users')
                          ->where('user_type_id',2)
                          ->select('id')
                          ->get();
        $data["delivered"] =   DB::table('shipping_details')
                           ->where('payments_status',1)
                           ->select('id')
                           ->get();
        $data["shipments"]= DB::table('shipping_details')
                           ->select('id')
                           ->get();
        $data["truckbooking"] = DB::table('shipping_details')
                           ->where('payments_status',1)
                           ->where('category_id',1)
                           ->select('id')
                           ->get();
         $data["vehicle"] = DB::table('shipping_details')
                           ->where('payments_status',1)
                           ->where('category_id',3)
                           ->select('id')
                           ->get();
        $data["packers"] = DB::table('shipping_details')
                           ->where('payments_status',1)
                           ->where('category_id',2)
                           ->select('id')
                           ->get();
        $data["Partload"] = DB::table('shipping_details')
                           ->where('payments_status',1)
                           ->where('category_id',4)
                           ->select('id')
                           ->get();
        return view('admin.dashboards.dashboard',$data);
    }
  
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
