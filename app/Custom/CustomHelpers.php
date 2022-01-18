<?php 

namespace App\Custom;
use App\Models\User;
use DB;
use Auth;
use \Carbon\Carbon;

class CustomHelpers{

	public static function quickAlphaRandom($length = 4)
    {
        $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }
    
    public static function quickRandom($length = 4)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }

    public static function calculate_total_time($arr){
          
        $total = 0; 
          
        // Loop the data items 
        foreach( $arr as $element){
            // Explode by seperator : 
            $temp = explode(":", $element); 
              
            // Convert the hours into seconds 
            // and add to total 
            $total+= (int) $temp[0] * 3600; 
              
            // Convert the minutes to seconds 
            // and add to total 
            $total+= (int) $temp[1] * 60; 
              
            // Add the seconds to total 
            $total+= (int) $temp[2];
        } 
        // Format the seconds back into HH:MM:SS 
        $formatted = sprintf('%02d:%02d:%02d',  
                        ($total / 3600), 
                        ($total / 60 % 60), 
                        $total % 60); 
          
        return $formatted;  
          
    }

    public static function menuTree(array &$elements, $parentId = 0) {

        $branch = array();    
        foreach ($elements as $element) {
            if ($element['pid'] == $parentId) {
                $children = self::menuTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[$element['id']] = $element;
            }
        }
        return $branch;
    }
}