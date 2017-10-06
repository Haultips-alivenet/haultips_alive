<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\UserDetail;
use Session;
use DB;

class ChatController extends Controller
{
    
    public function sendMessageU(Request $request){
        $pusher = App::make('pusher');
        return ($pusher->trigger( 'support-channel',
                          'my-event', 
                          array('message' => $request->chat_message, 'fname' => Auth::user()->first_name), $request->socket_id)) ? 1 : 0;

    }

    public function sendMessageS(Request $request){
        $pusher = App::make('pusher');
        return ($pusher->trigger( 'my-channel-2',
                          'my-event', 
                          array('message' => $request->chat_message, 'fname' => Auth::user()->first_name), $request->socket_id)) ? 1 : 0;

    }

    public function chatBoxU(){
    	return view('user.chatbox');
    }

    public function chatBoxS(){
    	return view('user.support-chatbox');
    }
    public function user_support(){
        
        $tempArr = Session::get('currentUser');
        $data["profile_pic"] = DB::table('user_details')->where('user_id',$tempArr["id"])->select('image')->first();
        $data["chatdetails"] =   DB::table('support_chats as c')
                            ->leftjoin('users as u','c.user_id','=','u.id')
                            ->leftjoin('user_details as ud','ud.user_id','=','u.id')
                            ->where('c.user_id',$id)
                            ->orderBy('c.id',"asc")
                            ->select('c.id','u.first_name','c.created_at','c.message','ud.image','c.user_type_id')
                            ->get();
        $user_detail = UserDetail::where('user_id', Auth::User()->id)->first();
        $data['profile_pic'] = $this->setDefaultImage('public/uploads/userimages/', ($user_detail) ? $user_detail->image : '', 'u');
        return view('user.support_chat_details',$data);
    }
    public function setDefaultImage($path = '', $file = '', $type = 'n'){
        $filename = base_path($path . $file);
        // n=>Normal no image, u=>User icon image
        $type_arr = array('n' => 'not-available.jpg', 'u' => 'customer_img.png');
        if (file_exists($filename) && !empty($file))
            return url($path . $file);     
        else
            return asset('public/user/img/' . $type_arr[$type]);
    }
}
