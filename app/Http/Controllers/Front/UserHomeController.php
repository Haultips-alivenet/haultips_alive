<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\FrontController;
use Auth;
use Session;
use App\VehicleCategory;
use App\User;
use App\TruckLengths;
use App\AdminBedroom;
use App\AdminBox;
use DB;

class UserHomeController extends FrontController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
             
     $categories = VehicleCategory::where('status',1)->where('parent_id',0)->select('id','category_name','category_image')->get();
        return view('user.index',['categories'=>$categories]);
       
    }
     public function userdashboard()
    {
        return view('user.userdashboard');
    }
    public function faq()
    {
        return view('user.faq');
    }
    public function notification()
    {
        return view('user.notification');
    }
    public function changepassword()
    {
        return view('user.changepassword');
    }
     public function transactionhistory()
    {
        return view('user.transactionhistory');
    }
    public function gettrucklength(Request $request){
        
       $data["truck_lengths"]  = DB::table('truck_lengths')
                    ->where('truck_type_id',$request->id)
                    ->select('truck_length','id')
                    ->get();
      echo json_encode($data);
    }
     public function gettruckcapacity(Request $request){
        
       $data["truck_capacity"]  = DB::table('truck_capacities')
                    ->where('truck_length_id',$request->id)
                    ->select('truck_capacity','id')
                    ->get();
      echo json_encode($data);
    }
    
    public function subCategory(Request $request){
        $catId = $request->id; 
        $category = VehicleCategory::where('status',1)->where('id',$catId)->select('category_name')->first();
        $subCategories = VehicleCategory::where('status',1)->where('parent_id',$catId)->select('id','category_name','category_image')->get();
        
        return view('user/subCategory/index', ['category'=>$category, 'subCategories'=>$subCategories]);
    }
}