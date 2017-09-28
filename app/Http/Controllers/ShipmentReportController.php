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
use App\PaymentDetail;
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
        
        //echo $tbl->table_name;die;
         if($request->category){
        $data["shiping_details"] =   DB::table('shipping_details as s')
                             ->leftjoin('vehicle_categories as v','v.id', '=', 's.category_id')
                             ->leftjoin('vehicle_categories as v1','v1.id', '=', 's.subcategory_id')
                              ->leftjoin('payment_methods as p','p.id', '=', 's.payment_method_id')
                              ->leftjoin('shipping_quotes as q','q.shipping_id', '=', 's.id')
                            ->where('s.subcategory_id',$request->category)
                            ->where('s.status',1)
                            ->orderBy('s.created_at',"desc") 
                            ->select('s.id','s.estimated_price','s.table_name','s.status','s.payments_status','v.category_name as categoty_name','v1.category_name as subcategory_name','p.method','q.quote_status')
                            ->paginate(10);
       
         } if($request->category && $request->delivery_title) {
             $tbl =  DB::table('shipping_details')
                            ->where('status',1)
                            ->where('subcategory_id',$request->category)
                            ->select('table_name')
                            ->first();
             
             $data["shiping_details"] =   DB::table('shipping_details as s')
                             ->leftjoin('vehicle_categories as v','v.id', '=', 's.category_id')
                             ->leftjoin('vehicle_categories as v1','v1.id', '=', 's.subcategory_id')
                              ->leftjoin('payment_methods as p','p.id', '=', 's.payment_method_id')
                              ->leftjoin('shipping_quotes as q','q.shipping_id', '=', 's.id')
                              ->leftjoin($tbl->table_name.' as tblName','tblName.shipping_id', '=','s.id')
                            ->where('s.subcategory_id',$request->category)
                            ->where('tblName.delivery_title','like',"%$request->delivery_title%")
                            ->where('s.status',1)
                            ->orderBy('s.created_at',"desc") 
                            ->select('s.id','s.estimated_price','s.table_name','s.status','s.payments_status','v.category_name as categoty_name','v1.category_name as subcategory_name','p.method','q.quote_status')
                            ->paginate(10);
             
         } else {
         
           $data["shiping_details"] =   DB::table('shipping_details as s')
                             ->leftjoin('vehicle_categories as v','v.id', '=', 's.category_id')
                             ->leftjoin('vehicle_categories as v1','v1.id', '=', 's.subcategory_id')
                              ->leftjoin('payment_methods as p','p.id', '=', 's.payment_method_id')
                              ->leftjoin('shipping_quotes as q','q.shipping_id', '=', 's.id')
                            ->where('s.category_id',$id)                            
                            ->where('s.status',1) 
                            ->orderBy('s.created_at',"desc") 
                            // ->where('q.quote_status',0)->orWhere('q.quote_status','1')
                            ->select('s.id','s.estimated_price','s.table_name','s.status','s.payments_status','v.category_name as categoty_name','v1.category_name as subcategory_name','p.method','q.quote_status')
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
        $data["table_name"]=$data["shipping_details"]->table_name;
        return view('admin/shipmentReport/view',$data);
    }
    public function bids_report($id){
        
        
        
        $data["shipping_quotes"] =   DB::table('shipping_quotes as s')
                                    ->leftjoin('users as u','s.carrier_id','=','u.id')
                                    ->where('shipping_id',$id)
                                    ->select('s.quote_price','s.quote_status','s.lowest_quote_price','u.first_name','u.last_name')
                                    ->get();
        
        return view('admin/shipmentReport/bidview',$data);
    }
    
    public function cod_payments($ids){
         
        $idd=explode("_",$ids);
        $id=$idd[0];
        $data["category_id"]=$idd[1];
        $data["shipping_quotes"] =   DB::table('shipping_details as s')
                                    ->leftjoin('shipping_quotes as sq','s.id','=','sq.shipping_id')
                                    ->where('sq.shipping_id',$id)
                                     ->where('sq.quote_status',1)
                                    ->select('sq.quote_price','sq.quote_status','s.table_name','s.id')
                                    ->first();
        return view('admin/shipmentReport/cod_payments',$data);
    }
    
    public function cod_payments_save($ids){
        $idd=explode("_",$ids);
        $id=$idd[0];
        $category_id=$idd[1];
         if(Auth::user()->user_type_id <> 1 || !Auth::check()) return redirect('shipment/'.$category_id.'/report');

         $amt =  DB::table('shipping_quotes')
                    ->where('shipping_id',$id)
                     ->where('quote_status',1)
                     ->select('quote_price')
                     ->first();
          // Update shipping details
          ShippingDetail::where('id', $id)->update(['payments_status' => 1]);
           $transaction_id=$this->random_num(20);
          // insert payment details
          $pay_det = new PaymentDetail;
          $pay_det->shipping_id = $id;
          $pay_det->transaction_id = $transaction_id;
          $pay_det->amount = $amt->quote_price;
          $pay_det->card_type = '';
          $pay_det->name_on_card = '';
          $pay_det->card_number = '';
          $pay_det->expiry_date = '';
          $pay_det->account_number = '';
          $pay_det->status = 'cod success';
          //$pay_det->created_at = $response['addedon'];
          $pay_det->save();
          return redirect('shipment/'.$category_id.'/report');
         
    }
    protected function random_num($size) {
	$alpha_key = '';
	$keys = range('a', 'z');

	for ($i = 0; $i < 2; $i++) {
		$alpha_key .= $keys[array_rand($keys)];
	}

	$length = $size - 2;

	$key = '';
	$keys = range(0, 9);

	for ($i = 0; $i < $length; $i++) {
		$key .= $keys[array_rand($keys)];
	}

	return $alpha_key . $key;
    }
    
}
