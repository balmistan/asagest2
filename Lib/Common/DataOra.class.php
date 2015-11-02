<?php
date_default_timezone_set("Europe/Berlin");
ini_set('date.timezone', 'Europe/Berlin');

class DataOra{

    public static function isoDatetimeToIsoDate($datetime){
        $datetime = explode(" ",$datetime);
        return $datetime[0];
    }
    
    public static function isoDatetimeToDate($datetime){
        if($datetime=="") return "";
        $datetime = DataOra::isoDatetimeToIsoDate($datetime);
        $datetime = explode("-",$datetime);
        $datetime = $datetime[2]."/".$datetime[1]."/".$datetime[0];
        return $datetime;
    }
    
    public static function isoDatetimeToTime($datetime,$separator=null){
        $datetime = explode(" ",$datetime);
        $datetime=$datetime[1];
        if($separator!=null)
            $datetime = str_replace (":", $separator,$datetime);
        return $datetime;
    }
    
    public static function isoDatetimeToDatetime($datetime){
        $firstPart = DataOra::isoDatetimeToDate($datetime);
        $secondPart = DataOra::isoDatetimeToTime($datetime,":");
        return $firstPart." ".$secondPart;
    }

    public static function today(){
        return date("d/m/Y");
    }
    
    public static function todayIso(){
        return date("d-m-Y");
    }
    
    public static function now(){
        return date("d/m/Y H:i:s");
    }
    
    public static function nowIso(){
        return date("Y-m-d H:i:s");
    }
        
    public static function nowTime(){
        date("H:i:s");
    }
    
    public static function nowIsoTime(){
        date("H-i-s");
    }
    
    
    public static function dateToIsoDate($date){
        $date = explode("/",$date);
        if(count($date)<3) return "";
        $date = $date[2]."-".$date[1]."-".$date[0];
        return $date;
    }
    
    public static function dateTimeToIsoDateTime($date){
        $time = explode(" ",$date);
        $date = $time[0];
        $time = $time[1];
           
        $date = explode("/",$date);
        if(count($date)<3) return "";
        $date = $date[2]."-".$date[1]."-".$date[0]." ".$time;
        return $date;
    }
    
    public static function addDaysToIsoDate($date,$days){
         return date("Y-m-d",strtotime(date("Y-m-d", strtotime($date)) . " +$days day"));
    }
    
    public static function addDaysToIsoDateTime($datetime,$days){
         return date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s", strtotime($datetime)) . " +$days day"));
    }
    
    public static function addHoursToIsoDate($date,$hours){
         return date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s", strtotime($date)) . " +$hours hours"));
    }
    
        public static function addSecondsToIsoDate($date,$seconds){
         return date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s", strtotime($date)) . " +$seconds seconds"));
    }
    
}
?>
