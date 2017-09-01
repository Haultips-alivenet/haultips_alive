<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Session;
use App\VehicleCategory;
use App\User;
use DB;

class CategoryController extends Controller
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
      
        $category = VehicleCategory::orderBy('id', 'desc');
        if($request->category_name!=''){
            
            $category->where('vehicle_categories.category_name', 'like', "%$request->category_name%");
        }
        if($id){
             $categoryupdate = VehicleCategory::find($id);
        } else {
           $categoryupdate=''; 
        }
        $category = $category->where('parent_id',0)->paginate(10);
        $page = $category->toArray();
        
       return view('admin.category.create')->with([
                    'category' => $category,'categoryupdate'=>$categoryupdate,
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
               'categoryName' => 'required|min:3|unique:vehicle_categories,category_name|max:255|Regex:/^[a-z-.]+( [a-z-.]+)*$/i'
            ]);
           $destinationPath=public_path()."/admin/images/category"; 
            $cname=$request->categoryName;
            $file = $request->file('categoryImage');
            $filename=$file->getClientOriginalName();
            $t=time();
            $filename=$t.'_'.$filename;
            $category= new VehicleCategory;
            $category->category_name = $cname;
            $category->category_image = $filename;
            $categorySucess = $category->save();  
            $request->file('categoryImage')->move($destinationPath,$filename);
            if($categorySucess == 1){
                Session::flash('success', 'Category created successfully');                
            }else{
               Session::flash('success', 'Error occur ! Please try again.');
            }            
            return redirect(url('admin/category/create'));
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
               'categoryNameupdate' => 'required|min:3|unique:vehicle_categories,category_name,'.$id,
        ]);
        
        $cname = $request->categoryNameupdate;
        $file = $request->file('categoryImageupdate');
        
            
        $category = VehicleCategory::find($id); 
        $category->category_name = $cname;
        if($file) {
           $destinationPath=public_path()."/admin/images/category"; 
            $filename=$file->getClientOriginalName();
            $t=time();
            $filename=$t.'_'.$filename; 
            $category->category_image = $filename;
            $request->file('categoryImageupdate')->move($destinationPath,$filename);
        }
        $categorySucess = $category->save();  
        if($categorySucess == 1){
            Session::flash('success', 'Category Updated successfully');            
        }else{
           Session::flash('success', 'Error occur ! Please try again.');
        } 
        return redirect(url('admin/category/create'));
        
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $query = VehicleCategory::where('id', $id)->delete();
        if($query == 1){
            Session::flash('success', 'Category deleted successfully');            
        }else{
           Session::flash('success', 'Error occur ! Please try again.');
        } 
        return redirect(url('admin/category/create'));
    }
}
