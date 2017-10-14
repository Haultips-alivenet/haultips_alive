<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\UserDetail;
use App\SupportChat;
use Session;
use App\User;
use DB;

class ChatController extends Controller
{
    
//    public function sendMessageU(Request $request){
//        $pusher = App::make('pusher');
//        return ($pusher->trigger( 'support-channel',
//                          'my-event', 
//                          array('message' => $request->chat_message, 'fname' => Auth::user()->first_name), $request->socket_id)) ? 1 : 0;
//
//    }
//
//    public function sendMessageS(Request $request){
//        $pusher = App::make('pusher');
//        return ($pusher->trigger( 'my-channel-2',
//                          'my-event', 
//                          array('message' => $request->chat_message, 'fname' => Auth::user()->first_name), $request->socket_id)) ? 1 : 0;
//
//    }
    public function send_message_by_user(Request $request){
        
         $support_data =   DB::table('support_chats')
                            ->where('user_id',Auth::User()->id)
                            ->select('support_id')
                            ->first();
         if($support_data) {
            $support_id = $support_data->support_id;
         } else {
             $support_data_in_user =   User::orderByRaw("RAND()")->where('user_type_id',4)->select('id')->first();
                             
                           
             $support_id = $support_data_in_user->id;
         }
        $chat = new SupportChat;
        $chat->support_id=$support_id;
        $chat->user_id=Auth::User()->id;
        $chat->message=$request->chat_message;
        $chat->user_type_id=Auth::User()->user_type_id;
        $Sucess = $chat->save(); 
        $pusher = App::make('pusher');
        return ($pusher->trigger( 'support-channel'.$support_id,
                          'my-event', 
                          array('message' => $request->chat_message, 'support_id'=>$support_id ,'user_id' => $request->user_id,'profile_pic'=> $request->profile_pic ,'fname' => Auth::user()->first_name), $request->socket_id)) ? 1 : 0;

    }
    public function send_message_by_support(Request $request){
        $chat = new SupportChat;
        $chat->support_id=Auth::User()->id;
        $chat->user_id=$request->channel_id;
        $chat->message=$request->chat_message;
        $chat->user_type_id=Auth::User()->user_type_id;
        $Sucess = $chat->save(); 
        $pusher = App::make('pusher');
        return ($pusher->trigger( 'my-channel-'.$request->channel_id,
                          'my-event', 
                          array('message' => $request->chat_message,'user_id' => Auth::User()->id,'profile_pic'=> $request->profile_pic, 'fname' => Auth::user()->first_name), $request->socket_id)) ? 1 : 0;

    }
    public function chatBoxU(){
    	return view('user.chatbox');
    }

    public function chatBoxS(){
    	return view('user.support-chatbox');
    }
    public function user_support(){
        
        
       
        $data["chatdetails"] =   DB::table('support_chats as c')
                            ->leftjoin('users as u','c.user_id','=','u.id')
                            ->leftjoin('user_details as ud','ud.user_id','=','c.support_id')
                            ->where('c.user_id',Auth::User()->id)
                            ->orderBy('c.id',"asc")
                            ->select('c.id','u.first_name','c.created_at','c.message','ud.image','c.user_type_id')
                            ->get();
        $user_detail = UserDetail::where('user_id', Auth::User()->id)->first();
        $data["name"]= Auth::User()->first_name;
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
