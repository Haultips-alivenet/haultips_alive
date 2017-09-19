<?php

namespace App\Helpers;

class Helper
{
    public static function setDefaultImage($path = '', $file = '', $type = 'n'){
        $filename = public_path($path . $file);
        $type_arr = array('n' => 'not-available.jpeg', 'u' => 'user_icon.png');
        if (file_exists($filename) && !empty($file))
            return url($path . $file);     
        else
            return asset('public/user/img/' . $type_arr[$type]);
    }

}
