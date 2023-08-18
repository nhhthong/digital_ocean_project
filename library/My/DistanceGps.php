<?php

class My_DistanceGps
{

    public static function getDistance($lat1, $lon1, $lat2, $lon2, $unit){
       
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);
    
        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

    public static function getDistanceOffice($latitude1, $longitude1, $latitude2, $longitude2){
       
        //Converting to radians
        $longi1 = deg2rad($longitude1); 
        $longi2 = deg2rad($longitude2); 
        $lati1 = deg2rad($latitude1); 
        $lati2 = deg2rad($latitude2); 
   
        //Haversine Formula 
        $difflong = $longi2 - $longi1; 
        $difflat = $lati2 - $lati1; 
   
        $val = pow(sin($difflat/2),2)+cos($lati1)*cos($lati2)*pow(sin($difflong/2),2); 
   
        $res2 = 6378.8 * (2 * asin(sqrt($val))); //for kilomet

        return $res2;
    }
    
    

}
