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
use App\CostEstimation;
use DB;

class CostController extends Controller
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
       
        
        $data["cost"] =DB::table('cost_estimations')->select('*')->paginate(10);
               
        
        $data["page"] = $data["cost"]->toArray();
         if($id){
             $data["costupdate"] = CostEstimation::find($id);
        } else {
           $data["costupdate"]=''; 
        }
       return view('admin.cost.costEstimation',$data);
    }
   
    
    public function store(Request $request)
    {          
        $this->validate($request, [
               'costtype' => 'required|max:255',
               'title' => 'required',
                'price' => 'required'
            ]);
            
            $costtype=$request->costtype;
            $title=$request->title;
            $price=$request->price;
            
            $cost= new CostEstimation;
            $cost->cost_type = $costtype;
            $cost->title = $title;
            $cost->price = $price;
            
            
            $costSucess = $cost->save();  
          
            if($costSucess == 1){
                Session::flash('success', 'Cost Estimation created successfully');                
            }else{
               Session::flash('success', 'Error occur ! Please try again.');
            }            
            return redirect(url('admin/cost/create'));
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
        
        
        $cost = CostEstimation::find($id); 
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
        $query = CostEstimation::where('id', $id)->delete();
        if($query == 1){
            Session::flash('success', 'Cost Estimation deleted successfully');            
        }else{
           Session::flash('success', 'Error occur ! Please try again.');
        } 
        return redirect(url('admin/cost/create'));
    }
}
