<?php

namespace App\Helpers;

class Helper
{
    public static function setDefaultImage($path = '', $file = '', $type = 'n'){
        $filename = public_path($path . $file);
        // n=>Normal no image, u=>User icon image
        $type_arr = array('n' => 'not-available.jpg', 'u' => 'customer_img.png');
        if (file_exists($filename) && !empty($file))
            return url($path . $file);     
        else
            return asset('public/user/img/' . $type_arr[$type]);
    }

}
