<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\ShippingDetail;
use Session;
use DB;

class SupportDashboardController extends Controller
{
   
    public function index()
    {
       
        return view('support.dashboard');
    }
    public function inbox()
    {
        $tempArr = Session::get('currentUser');
//            $data["chatdetails"] =   DB::table('support_chats as c')
//                                ->leftjoin('users as u','c.user_id','=','u.id')
//                                ->where('c.support_id',$tempArr["id"])
//                                ->groupBy('c.user_id')
//                                ->orderBy('c.created_at',"desc")
//                                ->select('c.id','c.message','u.first_name','c.created_at','c.user_id')
//                                ->paginate(10);
         $data["chatdetails"] =  DB::select(DB::raw('select m.id,m.message,m.first_name,m.created_at,m.user_id from (select c.id,c.message,u.first_name,c.created_at,c.user_id from support_chats as c left join users as u on c.user_id=u.id where c.support_id='.$tempArr["id"].' order by c.id desc) m group by m.user_id desc  order by m.id desc'));
        return view('support.inbox',$data);
        
     
        
        
    }
    
    public function chatdetails($id){
        
        $tempArr = Session::get('currentUser');
        $data["profile_pic"] = DB::table('user_details')->where('user_id',$tempArr["id"])->select('image')->first();
        $data["chatdetails"] =   DB::table('support_chats as c')
                            ->leftjoin('users as u','c.user_id','=','u.id')
                            ->leftjoin('user_details as ud','ud.user_id','=','u.id')
                            ->where('c.user_id',$id)
                            ->orderBy('c.id',"asc")
                            ->select('c.id','u.first_name','c.created_at','c.message','ud.image','c.user_type_id')
                            ->get();
        $data["channel_id"]=$id;
        return view('support.chatdetails',$data);
    }
    
}


