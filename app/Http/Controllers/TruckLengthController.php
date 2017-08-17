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
use DB;

class TruckLengthController extends Controller
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
                            ->where('parent_id',5)
                            ->select('category_name','id')
                            ->get();
        
        if($request->trucktypesearch!=''){
             $data["trucklength"] =DB::table('truck_lengths as t')
                            ->join('vehicle_categories as v','t.truck_type_id', '=', 'v.id')
                            ->where('t.truck_type_id',$request->trucktypesearch)
                            ->select('t.*','v.category_name as trucktype')->paginate(10);
        } else  if($request->trucklengthsearch!=''){
              $data["trucklength"] =DB::table('truck_lengths as t')
                            ->join('vehicle_categories as v','t.truck_type_id', '=', 'v.id')
                            ->where('t.truck_length', 'like', "%$request->trucklengthsearch%")
                            ->select('t.*','v.category_name as trucktype')->paginate(10);
            
        } else {
             $data["trucklength"] =DB::table('truck_lengths as t')
                            ->join('vehicle_categories as v','t.truck_type_id', '=', 'v.id')
                            ->select('t.*','v.category_name as trucktype')->paginate(10);
               
        }
        $data["page"] = $data["trucklength"]->toArray();
         if($id){
             $data["truckupdate"] = TruckLengths::find($id);
        } else {
           $data["truckupdate"]=''; 
        }
       return view('admin.truck.trucklength',$data);
    }
    public function checktrucklength(Request $request){
      $data=  DB::table('truck_lengths')
                    ->where('truck_type_id',$request->type)
                    ->where('truck_length',$request->trucklength)
                    ->select('*')
                    ->get();
     //echo count($data);die;
      if(count($data)>0) {
          return 'true';
      } else {
          return 'false';
      }
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
               'trucklength' => 'required'
            ]);
            
            $trucktype=$request->trucktype;
            $trucklength=$request->trucklength;
            
            $truck= new TruckLengths;
            $truck->truck_type_id = $trucktype;
            $truck->truck_length = $trucklength;
            
            $truckSucess = $truck->save();  
          
            if($truckSucess == 1){
                Session::flash('success', 'Truck Length created successfully');                
            }else{
               Session::flash('success', 'Error occur ! Please try again.');
            }            
            return redirect(url('admin/trucklength/create'));
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
        ]);
        
        
        $truck = TruckLengths::find($id); 
        $truck->truck_type_id = $request->trucktypeupdate;
        $truck->truck_length = $request->trucklengthupdate;
        
        $truckSucess = $truck->save();  
        if($truckSucess == 1){
            Session::flash('success', 'Truck Length Updated successfully');            
        }else{
           Session::flash('success', 'Error occur ! Please try again.');
        } 
        return redirect(url('admin/trucklength/create'));
        
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $query = TruckLengths::where('id', $id)->delete();
        if($query == 1){
            Session::flash('success', 'Truck Length deleted successfully');            
        }else{
           Session::flash('success', 'Error occur ! Please try again.');
        } 
        return redirect(url('admin/trucklength/create'));
    }
}
