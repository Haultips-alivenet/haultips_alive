<?php



namespace App\Http\Controllers\Front;



use Illuminate\Http\Request;



use App\Http\Requests;

use App\Http\Controllers\FrontController;

use Auth;

use Session;

use App\VehicleCategory;

use App\User;

use App\UserDetail;

use App\TruckLengths;

use App\AdminBedroom;

use App\AdminBox;

use App\TblQuesMaster;

use App\ShippingDetail;

use App\ShippingQuote;

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

    $data["latest_post"]  = DB::table('shipping_details as sd')

                    ->leftJoin('shipping_pickup_details as sp','sd.id','=','sp.shipping_id')

                    ->orderBy('sd.created_at', 'desc')

                    ->limit(10)

                    ->select('sd.id','sd.table_name','sd.created_at','sp.pickup_address')

                    ->get();       

     $data["categories"] = VehicleCategory::where('status',1)->where('parent_id',0)->select('id','category_name','category_image')->get();

        return view('user.index',$data);

       

    }

    public function userdashboard()

    {

        return view('user.userdashboard');

    }

    

    public function getquesAns(Request $request){

        $tempArr = Session::get('currentUser');

         $quesDetail = TblQuesMaster::select('tbl_ques_masters.id as que_master_id','tq.id as qies_id','tq.question','tq.created_at as quesTime','u.first_name as cfname','u.last_name as clname','ta.answer','ta.created_at as ansTime','uc.first_name as ufname','uc.last_name as ulname')

                                    ->leftJoin('tbl_questions as tq','tq.ques_master_id','=','tbl_ques_masters.id')

                                    ->leftJoin('users as u','u.id','=','tbl_ques_masters.carrier_id')

                                    ->leftJoin('tbl_answers as ta','ta.ques_id','=','tq.id')

                                    ->leftJoin('users as uc','uc.id','=','tbl_ques_masters.user_id')

                                    ->where('tbl_ques_masters.shipping_id', $request->id)

                                    ->where('tbl_ques_masters.carrier_id', $request->cid)

                                    ->get();

            $i=1;

            $a='';

            foreach($quesDetail as $detail){

                        

                $a.='<h5><b>'.$i.'. '.$detail['question'].'</b></h5>';

                 $a.='Ans '.$detail['answer'];

                 $a.='<br>';

                 $ids="'".$detail["que_master_id"]."_".$detail["qies_id"]."'";

                 if($detail['answer']=="" && $tempArr["user_type_id"]!=2){

                     $a.='<div class="quest_txt">';

                     $a.='<h4> <span class="pull-right" onclick="getreply('.$i.');"><i class="fa fa-plus-circle"></i></span></h4>';

                     $a.='<div class="clearfix"></div>';

                     $a.='<div class="_question_tb_0'.$i.'" style="display: none">';

                     $a.='<div class="input-group">';

                     $a.='<input type="text" name="answerof'.$detail["qies_id"].'" id="answerof'.$detail["qies_id"].'"  class="form-control input-sm" maxlength="64" placeholder="Please enter your Answer" />';

                     $a.='<span class="input-group-addon btn-primary" onclick="reply_ans('.$ids.');">

                        Submit <i class="fa fa-question" aria-hidden="true"></i>

                      </span>';

                     $a.='</div>';

                     $a.='</div>';

                     $a.='</div>';

                     

                 }

                 

                 

                 $i++;

            }

        echo $a;

    }



    public function notification()

    {

        return view('user.notification');

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

        

        $request->session()->put('category_id', $catId);

        $category = VehicleCategory::where('status',1)->where('id',$catId)->select('category_name')->first();

        $subCategories = VehicleCategory::where('status',1)->where('parent_id',$catId)->select('id','category_name','category_image')->get();

        if($catId=='4') { 

            $data["materials"]= DB::table('materials')

                    ->select('*')

                    ->get();

             return view('user/shipment/partload',$data);

        } else if($catId=='1') {

           

            $arr = \App\VehicleCategory::where("category_name","=","open")->lists('id')->all(); 

            $arr1 = \App\VehicleCategory::where("category_name","=","Trailer")->lists('id')->all(); 

            $arr2 = \App\VehicleCategory::where("category_name","=","Container")->lists('id')->all(); 

            $arr3 = \App\VehicleCategory::where("category_name","=","Truck Booking")->lists('id')->all(); 

            $data["openlength"]= DB::table('truck_lengths')

                            ->whereIn('truck_type_id',$arr)

                            ->select('id','truck_length')

                            ->get();

            $data["Trailerlength"]= DB::table('truck_lengths')

                            ->whereIn('truck_type_id',$arr1)

                            ->select('id','truck_length')

                            ->get();

             $data["Containerlength"]= DB::table('truck_lengths')

                            ->whereIn('truck_type_id',$arr2)

                            ->select('id','truck_length')

                            ->get();

             $data["trucktype"]= DB::table('vehicle_categories')

                            ->where('parent_id',$arr3)

                            ->select('id','category_name','category_image')

                            ->get();

             return view('user/shipment/truckbooking',$data);

        } else {

        return view('user/subCategory/index', ['category'=>$category, 'subCategories'=>$subCategories]);

        }

    }

   public function mobileCheck(Request $request){

        $mobile = $request->checkMobile;

        $query = User::where('mobile_number', $mobile)->select('id')->first();

        if($query){

             echo "true";

        } else {

            echo "false";

        }

       

    }

	public function about_us(){ 

        return view('user/about_us');

    }

    public function how_it_work(){ 

        return view('user/how_it_work');

    }
     public function privacy_policy()
    {
        return view('user.privacy_policy');
    }
}



