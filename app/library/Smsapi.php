<?php 
namespace App\library;
use Twilio;
use Services_Twilio;
//use Mail;

class Smsapi {
    function sendsms_api($number='',$msg="Hello")
    {
        try{
            $AccountSid = "AC399baf52713d41c1992fc12c74e5c511"; //twilio account id
            $AuthToken = "61e8d74be4d6d84d2a700ed865fb43f4";    //twilio account token
            $client = new Services_Twilio($AccountSid, $AuthToken);
            $sms = $client->account->messages->create(array(
                //'To' => "+917876222011",
                //'To' => "+".$number,
                'To' => $number,
                'From' => "+18552792560",
                'Body' => $msg,
            ));
            return 1;
        }
        catch (\Exception $e) {
            return 0;
        }
       // echo "ebdgd";
        /*if($sms){
            return 1;
        }else{
            return 0;
        }*/

    }


//    public function sendusermail($pageuser,$data,$subject,$from_email,$from_name,$uemail)
//    {
//        Mail::send($pageuser, $data, function($messa) use ($subject,$from_email,$from_name,$uemail)  {
//            $messa->from($from_email, $from_name);
//            $messa->to($uemail)->subject($subject);
//          
//
//        });
//    }


}
?>