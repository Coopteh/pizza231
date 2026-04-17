<?php
namespace App\Services;

class ValidateBase {

    public static function sanitationString(&$value):void {
        $value = htmlspecialchars(strip_tags(trim($value)));
    }
 
    public static function sanitationArray(&$arr):void {
        foreach($arr as $key => $value) {
            self::sanitationString($arr[$key]);
        }
    }
}