<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Session;
use App\Vehicle_categorie;
use App\User;
use DB;

class SubCategoryController extends Controller
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
        $categoryname =   DB::table('vehicle_categories')
                            ->where('parent_id',0)
                            ->select('category_name','id')
                            ->get();
        
        if($request->subcategory_namesearch!=''){
        $category =DB::table('vehicle_categories as v')
                            ->join('vehicle_categories as v1','v.parent_id', '=', 'v1.id')
                            ->where('v.parent_id','!=',0)
                            ->where('v.category_name', 'like', "%$request->subcategory_namesearch%")
                            ->select('v.*','v1.category_name as cname')->paginate(10);
        } else if($request->categoryNamesearch!=''){
             $category =DB::table('vehicle_categories as v')
                            ->join('vehicle_categories as v1','v.parent_id', '=', 'v1.id')
                            ->where('v.parent_id','!=',0)
                            ->where('v.parent_id',$request->categoryNamesearch)
                            ->select('v.*','v1.category_name as cname')->paginate(10);
            
        } else {
             $category =DB::table('vehicle_categories as v')
                            ->join('vehicle_categories as v1','v.parent_id', '=', 'v1.id')
                            ->where('v.parent_id','!=',0)
                            ->select('v.*','v1.category_name as cname')->paginate(10);
               
        }
        $page = $category->toArray();
         if($id){
             $categoryupdate = Vehicle_categorie::find($id);
        } else {
           $categoryupdate=''; 
        }
       return view('admin.category.subcreate')->with([
                    'category' => $category,'categoryupdate'=>$categoryupdate,
                    'categoryname'=>$categoryname,
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
               'categoryName' => 'required|max:255',
               'subCategoryName' => 'required|min:3|unique:Vehicle_categories,category_name|max:255|Regex:/^[a-z-.]+( [a-z-.]+)*$/i'
            ]);
            $destinationPath="public\admin\images\category";
            $subcategory=$request->subCategoryName;
            $category_id=$request->categoryName;
            $file = $request->file('subcategoryImage');
            $filename=$file->getClientOriginalName();
            $t=time();
            $filename=$t.'_'.$filename;
            $category= new Vehicle_categorie;
            $category->category_name = $subcategory;
            $category->parent_id = $category_id;
            $category->category_image = $filename;
            $categorySucess = $category->save();  
            $request->file('subcategoryImage')->move($destinationPath,$filename);
            if($categorySucess == 1){
                Session::flash('success', 'Sub Category created successfully');                
            }else{
               Session::flash('success', 'Error occur ! Please try again.');
            }            
            return redirect(url('admin/subcategory/create'));
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
               'subcategoryupdate' => 'required|min:3|unique:Vehicle_categories,category_name,'.$id,
               'categoryupdate' => 'required',
        ]);
        
        $cname = $request->subcategoryupdate;
        $c_id = $request->categoryupdate;
        $file = $request->file('subcategoryImageupdate');
        
            
        $category = Vehicle_categorie::find($id); 
        $category->category_name = $cname;
        $category->parent_id = $c_id;
        if($file) {
            $destinationPath="public\admin\images\category";
            $filename=$file->getClientOriginalName();
            $t=time();
            $filename=$t.'_'.$filename; 
            $category->category_image = $filename;
            $request->file('subcategoryImageupdate')->move($destinationPath,$filename);
        }
        $categorySucess = $category->save();  
        if($categorySucess == 1){
            Session::flash('success', 'Sub Category Updated successfully');            
        }else{
           Session::flash('success', 'Error occur ! Please try again.');
        } 
        return redirect(url('admin/subcategory/create'));
        
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $query = Vehicle_categorie::where('id', $id)->delete();
        if($query == 1){
            Session::flash('success', 'Category deleted successfully');            
        }else{
           Session::flash('success', 'Error occur ! Please try again.');
        } 
        return redirect(url('admin/subcategory/create'));
    }
}
