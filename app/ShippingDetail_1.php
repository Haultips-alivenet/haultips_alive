<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
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
      
      public static function getDeliveryName($table_name,$id) {
         
        $shippingData = DB::table($table_name)->select('delivery_title')->where('shipping_id',$id)->first();
        if($shippingData) {
        return $shippingData->delivery_title;
        } else {
            return "";
        }
      }

}
