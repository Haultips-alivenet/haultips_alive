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
           // $cost= new $type;
           // $cost->name = $name;
           // $costSucess = $cost->save();  
           $costSucess= DB::table($type)->insert([['name' => $name]]);
            if($costSucess == 1){
                Session::flash('success', 'Data created successfully');                
            }else{
               Session::flash('success', 'Error occur ! Please try again.');
            }            
            return redirect(url('admin/adminshipment/create'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('admin/users/view')->with([
                    'user' => $user,
                ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $category = Vehicle_categorie::find($id);
       return view('admin/category/create')->with([                    
                    'category' => $category
        ]);
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
       
        $this->validate($request, [
               'costtypeupdate' => 'required',
               'titleupdate' => 'required',
               'priceupdate' => 'required',
        ]);
        
        
        $cost = CostEstimations::find($id); 
        $cost->cost_type = $request->costtypeupdate;
        $cost->title = $request->titleupdate;
        $cost->price = $request->priceupdate;
        
        $truckSucess = $cost->save();  
        if($truckSucess == 1){
            Session::flash('success', 'Cost Estimation Updated successfully');            
        }else{
           Session::flash('success', 'Error occur ! Please try again.');
        } 
        return redirect(url('admin/cost/create'));
        
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $query = CostEstimations::where('id', $id)->delete();
        if($query == 1){
            Session::flash('success', 'Cost Estimation deleted successfully');            
        }else{
           Session::flash('success', 'Error occur ! Please try again.');
        } 
        return redirect(url('admin/cost/create'));
    }
}
