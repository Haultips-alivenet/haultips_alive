<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class GeoLocation extends Model
{
    public static function getState(){
    	return DB::table('geo_locations')->where('location_type', 'STATE')->get();
    }

    public static function getCityByStateId($state_id){
    	return DB::table('geo_locations')->where('parent_id', $state_id)->get();
    }

}
