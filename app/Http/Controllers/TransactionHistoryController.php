<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Session;
use App\VehicleCategory;
use App\User;
use App\TruckLengths;
use App\TruckCapacity;
use App\Helpers\DateHelper;
use DB;

class TransactionHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index(Request $request)
    {
          $categoryname =   DB::table('payment_details as p')
                            ->leftjoin('shipping_details as s','s.id','=','p.shipping_id')
                            ->leftjoin('users as u','u.id','=','s.user_id')
                            ->leftjoin('vehicle_categories as v','v.id','=','s.category_id')
                            ->leftjoin('payment_methods as pm','pm.id','=','s.payment_method_id')
                            ->where('u.first_name', 'like', "%$request->name%")
                            ->where('v.category_name', 'like', "%$request->Order_Category%")
                            ->where('p.status', 'like', "%$request->Payment_Status%")
                            ->select('s.id as shipmentId','s.table_name','p.id','p.amount','p.transaction_id','p.status','p.created_at','u.first_name','u.last_name','u.mobile_number','v.category_name','pm.method')
                            ->paginate(10);
        $user =$categoryname;
        $page = $user->toArray();
        return view('admin.transactionHistory.index')->with([
                    'users' => $user,
                    'page' => $page]);
    }

   
}


