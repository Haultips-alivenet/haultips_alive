<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use App\AdminDinningRoom;
use DB;

class ShippingDetail extends Model
{
 
    public static function distance($lat1, $lon1, $lat2, $lon2, $unit) {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
          return round(($miles * 1.609344));
        } else {
              return round($miles);
            }

      }    
      
      public static function getDineInData($data, $table) {
        $dineInData = array();
        $item = array();
        $itemName = '';
        $dineInData = explode(",",$data);
        foreach($dineInData as $dineIn){
            $item = explode("-",$dineIn);
            $dineInName = DB::table($table)->select('name')->where('id',$item[0])->first();
            $itemName.= ($itemName == '') ? $item[1].' '.$dineInName->name : ','.$item[1].' '.$dineInName->name;            
        }
        return $itemName;
      }
      
      public static function getCategoryName($id, $searchField, $selectField, $table) {
        
        $itemName = '';
        $dineInName = DB::table($table)->select($selectField)->where($searchField, $id)->first();
        $itemName =  $dineInName->$selectField;            
        
        return $itemName;
      }
       public static function getDeliveryName($table_name,$id) {
         
        $shippingData = DB::table($table_name)->select('delivery_title')->where('shipping_id',$id)->first();
        if($shippingData) {
        return $shippingData->delivery_title;
        } else {
            return "";
        }
      }
      public static function getImageName($table_name,$id) {
         
        $shippingData = DB::table($table_name)->select('item_image')->where('shipping_id',$id)->first();
        if($shippingData) {
        return $shippingData->item_image;
        } else {
            return "";
        }
      }
      // check if shipping id is belongs to logged in user
      public static function isShippingIdBelongsToLoggedInUser($shipping_id, $user_id){
        return DB::table('shipping_details')->where('id', $shipping_id)->where('user_id', $user_id)->count();
      }
}
