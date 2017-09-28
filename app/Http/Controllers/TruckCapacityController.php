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
use DB;

class TruckCapacityController extends Controller
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
        $data["categoryname"] =   DB::table('vehicle_categories')
                            ->where('parent_id',1)
                            ->select('category_name','id')
                            ->get();
      
        if($request->trucktype_search && $request->trucklength_search){
             $data["truckcapacity"] =DB::table('truck_capacities as c')
                       ->join('truck_lengths as l','c.truck_length_id', '=', 'l.id')
                       ->join('vehicle_categories as v','l.truck_type_id', '=', 'v.id')
                       ->where('v.id',$request->trucktype_search)
                       ->where('l.truck_length','like',"%$request->trucklength_search%")
                       ->select('c.*','l.truck_length','v.category_name')->paginate(10);
               
        } else if($request->trucktype_search){
             $data["truckcapacity"] =DB::table('truck_capacities as c')
                       ->join('truck_lengths as l','c.truck_length_id', '=', 'l.id')
                       ->join('vehicle_categories as v','l.truck_type_id', '=', 'v.id')
                      ->where('v.id',$request->trucktype_search)
                       ->select('c.*','l.truck_length','v.category_name')->paginate(10);
               
        } else {
        $data["truckcapacity"] =DB::table('truck_capacities as c')
                       ->join('truck_lengths as l','c.truck_length_id', '=', 'l.id')
                       ->join('vehicle_categories as v','l.truck_type_id', '=', 'v.id')
                       ->select('c.*','l.truck_length','v.category_name')->paginate(10);
               
        }
        $data["page"] = $data["truckcapacity"]->toArray();
         if($id){
             $data["truckupdate"] = TruckCapacity::find($id);
             $data["truck_lengths"]  = DB::table('truck_lengths')
                    ->where('truck_type_id',$data["truckupdate"]->truck_type_id)
                    ->select('truck_length','id')
                    ->get();
           
        } else {
           $data["truckupdate"]=''; 
        }
       return view('admin.truck.truccapacity',$data);
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
       //print_r($data["truck_capacity"]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {          
        $this->validate($request, [
               'trucktype' => 'required|max:255',
               'trucklength' => 'required',
                'truckcapacity' => 'required'
            ]);
            
            $trucklength=$request->trucklength;
            $truckcapacity=$request->truckcapacity;
            $trucktype=$request->trucktype;
            
            $truck= new TruckCapacity;
            $truck->truck_type_id = $trucktype;
            $truck->truck_length_id = $trucklength;
            $truck->truck_capacity = $truckcapacity;
            
            
            $truckSucess = $truck->save();  
          
            if($truckSucess == 1){
                Session::flash('success', 'Truck Capacity created successfully');                
            }else{
               Session::flash('success', 'Error occur ! Please try again.');
            }            
            return redirect(url('admin/truckcapacity/create'));
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
               'trucktypeupdate' => 'required',
               'trucklengthupdate' => 'required',
               'truckcapacityupdate' => 'required',
        ]);
        
        
        $truck = TruckCapacity::find($id); 
        $truck->truck_type_id = $request->trucktypeupdate;
        $truck->truck_length_id = $request->trucklengthupdate;
        $truck->truck_capacity = $request->truckcapacityupdate;
        
        $truckSucess = $truck->save();  
        if($truckSucess == 1){
            Session::flash('success', 'Truck Capacity Updated successfully');            
        }else{
           Session::flash('success', 'Error occur ! Please try again.');
        } 
        return redirect(url('admin/truckcapacity/create'));
        
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $query = TruckCapacity::where('id', $id)->delete();
        if($query == 1){
            Session::flash('success', 'Truck Capacity deleted successfully');            
        }else{
           Session::flash('success', 'Error occur ! Please try again.');
        } 
        return redirect(url('admin/truckcapacity/create'));
    }
}
