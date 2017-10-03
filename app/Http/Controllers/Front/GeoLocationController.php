<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\FrontController;
use App\GeoLocation;

class GeoLocationController extends FrontController
{

    public function getCityByStateId($state_id){
      return GeoLocation::getCityByStateId($state_id);
    }

}


