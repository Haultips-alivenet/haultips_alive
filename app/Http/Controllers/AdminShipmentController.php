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
use DB;

class AdminShipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,$id=null)
    {
       
        
        $data["type"] =DB::table('admin_general_shipments_types')->select('*')->get();
        
       return view('admin.shipmentmaster.create',$data);
    }
   
    
    public function store(Request $request)
    {          
        $this->validate($request, [
               'type' => 'required|max:255',
               'name' => 'required'
                
            ]);
            
            $type=$request->type;
            $name=$request->name;
            $costSucess= DB::table($type)->insert([['name' => $name]]);
             if($costSucess == 1){
                Session::flash('success', 'Data created successfully');                
            }else{
               Session::flash('success', 'Error occur ! Please try again.');
            }            
            return redirect(url('admin/adminshipment/create'));
    }

    public function shipList(Request $request)
    {
       if($request->type){
           $data["tableList"] =DB::table($request->type)->select('*')->paginate(10);
           $data["page"] = $data["tableList"]->toArray(); 
           //$data["page"]["next_page_url"]=$data["page"]["next_page_url"].'&type='.$request->type;
       } else {
           $data["tableList"]=array();
           
       }
       
       //echo $data["page"]["next_page_url"];
       //print_r($data["page"]);die;
     //print_r($data["tableList"]->render());die;
      $data["type"] =DB::table('admin_general_shipments_types')->select('*')->get();
        
       return view('admin.shipmentmaster.shiplist',$data);
    }
   
    public function show($id)
    {
        $user = User::find($id);
        return view('admin/users/view')->with([
                    'user' => $user,
                ]);
    }

 
    public function edit(Request $request,$id)
    {
      
       $data["edit_type"] =$request->name;
       $data["edit_name"] =DB::table($request->name)->select('id','name')->where('id',$id)->first();
      $data["type"] =DB::table('admin_general_shipments_types')->select('*')->get();
       return view('admin/shipmentmaster/edit',$data);
    }

    
    public function update(Request $request)
    {
       //print_r($_POST);die;
        $this->validate($request, [
               'type' => 'required',
               'name' => 'required',
              
        ]);
        
      $truckSucess =  DB::table($request->type)
            ->where('id', $request->id)
            ->update(['name' => $request->name]);
     
        if($truckSucess == 1){
            Session::flash('success', 'Data Updated successfully');            
        }else{
           Session::flash('success', 'Error occur ! Please try again.');
        } 
        return redirect(url('admin/adminshipment/shipList?type='.$request->type));
        
        
    }

   
    public function destroy(Request $request,$id)
    {
        //echo $id;die;
        $query =DB::table($request->name)->where('id',$id)->delete();
        if($query == 1){
            Session::flash('success', 'Data deleted successfully');            
        }else{
           Session::flash('success', 'Error occur ! Please try again.');
        } 
        return redirect(url('admin/adminshipment/shipList?type='.$request->name));
    }
}
