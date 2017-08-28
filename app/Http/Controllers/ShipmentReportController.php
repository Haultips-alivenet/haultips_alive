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
use App\AdminBedroom;
use App\AdminBox;
use App\ShippingDetail;
use DB;

class ShipmentReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$id=null)
    {
      
        $data["categoryname"] =   DB::table('vehicle_categories')
                            ->where('parent_id',$id)
                            ->select('category_name','id')
                            ->get();
         if($request->category){
        $data["shiping_details"] =   DB::table('shipping_details as s')
                             ->leftjoin('vehicle_categories as v','v.id', '=', 's.category_id')
                             ->leftjoin('vehicle_categories as v1','v1.id', '=', 's.subcategory_id')
                              ->leftjoin('payment_methods as p','p.id', '=', 's.payment_method_id')
                            ->where('s.subcategory_id',$request->category)
                            ->where('s.status',1)
                            ->select('s.id','s.estimated_price','s.status','v.category_name as categoty_name','v1.category_name as subcategory_name','p.method')
                            
                            ->paginate(10);
       
         } else {
           $data["shiping_details"] =   DB::table('shipping_details as s')
                             ->leftjoin('vehicle_categories as v','v.id', '=', 's.category_id')
                             ->leftjoin('vehicle_categories as v1','v1.id', '=', 's.subcategory_id')
                              ->leftjoin('payment_methods as p','p.id', '=', 's.payment_method_id')
                            ->where('s.category_id',$id)
                            ->where('s.status',1)
                            ->select('s.id','s.estimated_price','s.status','v.category_name as categoty_name','v1.category_name as subcategory_name','p.method')
                            ->paginate(10);
         }
        $data["page"] = $data["shiping_details"]->toArray(); 
        $data["id"]=$id;
       return view('admin.shipmentReport.shipmentreport',$data); 
    }
    
    public function details_report($id){
        
        $data["shipping_details"] = ShippingDetail::find($id);
        $data["tables_columns"]=DB::getSchemaBuilder()->getColumnListing($data["shipping_details"]->table_name);
        $data["tables_value"] =   DB::table($data["shipping_details"]->table_name)
                            ->where('shipping_id',$id)
                            ->select('*')
                            ->first();
        $data["pickup"] =   DB::table('shipping_pickup_details')
                            ->where('shipping_id',$id)
                            ->select('pickup_address','pickup_date')
                            ->first();
        $data["delivery"] =   DB::table('shipping_delivery_details')
                            ->where('shipping_id',$id)
                            ->select('delivery_address','delivery_date')
                            ->first(); 
        return view('admin/shipmentReport/view',$data);
    }

    
}
