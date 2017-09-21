<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Session;
use App\VehicleCategory;
use App\Material;
use App\User;
use DB;

class MaterialsController extends Controller
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
      
        $materials = Material::orderBy('id', 'desc');
        if($request->materials_name!=''){
            
            $materials->where('materials.name', 'like', "%$request->materials_name%");
        }
        if($id){
             $materialsupdate = Material::find($id);
        } else {
           $materialsupdate=''; 
        }
        $materials = $materials->paginate(10);
        $page = $materials->toArray();
        
       return view('admin.materials.create')->with([
                    'materials' => $materials,'materialsupdate'=>$materialsupdate,
                    'page' => $page]);
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
               'materialsName' => 'required|min:3|unique:materials,name|max:255|Regex:/^[a-z-.]+( [a-z-.]+)*$/i'
            ]);
           $destinationPath=public_path()."/admin/images/materials"; 
            $cname=$request->materialsName;
            $file = $request->file('materialsImage');
            $filename=$file->getClientOriginalName();
            $t=time();
            $filename=$t.'_'.$filename;
            $category= new Material;
            $category->name = $cname;
            $category->image = $filename;
            $categorySucess = $category->save();  
            $request->file('materialsImage')->move($destinationPath,$filename);
            if($categorySucess == 1){
                Session::flash('success', 'Materials created successfully');                
            }else{
               Session::flash('success', 'Error occur ! Please try again.');
            }            
            return redirect(url('admin/materials/create'));
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
       $category = VehicleCategory::find($id);
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
               'materialNameupdate' => 'required|min:3|unique:materials,name,'.$id,
        ]);
        
        $cname = $request->materialNameupdate;
        $file = $request->file('materialsImageupdate');
        
            
        $category = Material::find($id); 
        $category->name = $cname;
        if($file) {
           $destinationPath=public_path()."/admin/images/materials"; 
            $filename=$file->getClientOriginalName();
            $t=time();
            $filename=$t.'_'.$filename; 
            $category->image = $filename;
            $request->file('materialsImageupdate')->move($destinationPath,$filename);
        }
        $categorySucess = $category->save();  
        if($categorySucess == 1){
            Session::flash('success', 'Materials Updated successfully');            
        }else{
           Session::flash('success', 'Error occur ! Please try again.');
        } 
        return redirect(url('admin/materials/create'));
        
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $query = Material::where('id', $id)->delete();
        if($query == 1){
            Session::flash('success', 'Material deleted successfully');            
        }else{
           Session::flash('success', 'Error occur ! Please try again.');
        } 
        return redirect(url('admin/materials/create'));
    }
    
    public function changesttus($ids){
        $id=explode("_",$ids);
        if($id[1]==1) {
            $status='0';
        } else {
            $status='1';
        }
        $category = Material::find($id[0]); 
        $category->status = $status;
        $categorySucess = $category->save();  
        if($categorySucess == 1){
            Session::flash('success', 'Materials Status Updated successfully');            
        }else{
           Session::flash('success', 'Error occur ! Please try again.');
        } 
        return redirect(url('admin/materials/create'));
    }
}
