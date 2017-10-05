<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
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
    
    
}
