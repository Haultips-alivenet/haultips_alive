<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\FrontController;
use Auth;
use Session;
use App\NewsletterSubscriber;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Response;
use DB;

class NewsletterController extends FrontController
{

    public function index(Request $request){
      $rules = array (
        'newsletter_email' => 'required|email|max:255'
      );

      $validator = Validator::make ( Input::all (), $rules );
      if ($validator->fails ())
          return Response::json ($validator->messages());
      else{
          $ne = NewsletterSubscriber::where('newsletter_email', $request->get('newsletter_email'));
          $success = 0;
          if($ne->count()){
            $success = 2;
          }
          else{
            $ne_s = new NewsletterSubscriber;
            $ne_s->newsletter_email = $request->get('newsletter_email');
            if($ne_s->save()) $success = 1;
          }
          return $success;
      }
    }

}


