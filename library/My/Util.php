<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Util
 *
 * @author Hero
 */
class My_Util {

    //put your code here
    public static function changeKey($arr, $key) {
        if (is_array($arr)) {
            $arr_temp = [];
            foreach ($arr as $one) {
                $arr_temp[$one[$key]] = $one;
            }
            return $arr_temp;
        }
        return $arr;
    }

    public static function validateDate($date, $format = 'Y-m-d H:i:s') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public static function escape_string($value) {
        if (empty($value) || $value == null || ($value == 0 && is_numeric($value))) {
            return $value;
        }
        $search = array("\x00", "\n", "\r", "\\", "'", "\"", "\x1a","delete", "drop", "and", "or", "where" , "table","--");
        $replace = array("\\x00", "\\n", "\\r", "\\\\", "\'", "\\\"", "\\\x1a","\\delete", "\drop", "\and", "\or", "\where" , "\table","\--");
        return str_replace($search, $replace, $value);
    }

    public static function formatLinkS3($url_file) {
        $url_file = TRIM($url_file,'/');
        $full_url = HOST_S3 . $url_file;
//        $new_url = preg_replace('~/+~', '/', $full_url);
        return $full_url;
    }


    public static function forceUseApp()
    {
        $userStorage = Zend_Auth::getInstance()->getStorage()->read();
        $title = $userStorage->title;

        $force = in_array($title, [SALES_TITLE, PGPB_TITLE, SENIOR_PROMOTER_TITLE]);

        return $force;
    }

    public static function exceedDayAllow($from_date, $to_date, $days_allow = 92)
    {
        $from_date = date('y-m-d', strtotime(str_replace('/', '-', $from_date))) ;
        $to_date = date('y-m-d', strtotime(str_replace('/', '-', $to_date))) ;

        $days_diff = My_Date::date_diff($from_date,$to_date );

        return $days_diff > $days_allow;
    }


}
