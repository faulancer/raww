<?php

class String{
  /**
  * ...
  *
  */ 
  public static function insert($str, $data){
     return str_replace(array_keys($data),array_values($data));
  }
  
  
  public static function uuid() {   

     // The field names refer to RFC 4122 section 4.1.2

    return sprintf('%04x%04x-%04x-%03x4-%04x-%04x%04x%04x',
        mt_rand(0, 65535), mt_rand(0, 65535), // 32 bits for "time_low"
        mt_rand(0, 65535), // 16 bits for "time_mid"
        mt_rand(0, 4095),  // 12 bits before the 0100 of (version) 4 for "time_hi_and_version"
        bindec(substr_replace(sprintf('%016b', mt_rand(0, 65535)), '01', 6, 2)),
            // 8 bits, the last two of which (positions 6 and 7) are 01, for "clk_seq_hi_res"
            // (hence, the 2nd hex digit after the 3rd hyphen can only be 1, 5, 9 or d)
            // 8 bits for "clk_seq_low"
        mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535) // 48 bits for "node" 
    );
  }
  
  // UTF8 Helper
  
  public static function len($str){
		return mb_strlen($str);
  }
  
  public static function pos($str, $search, $offset = false){
    if (strlen($str) && strlen($search)){
      return ($offset === false) ? mb_strpos($str, $search) : mb_strpos($str, $search, $offset);
		}else{
      return false;
    }
  }
  
  public static function substr($str, $offset, $length = false){
    return ($length===false) ? mb_substr($str, $offset) : mb_substr($str, $offset, $length);
  }
  
  public static function lower($str){
		return mb_strtolower($str);
  }
  
  public static function upper($str){
		return mb_strtoupper($str);
  }

  public static function capitalize($str){
      return mb_convert_case($str, MB_CASE_TITLE, 'UTF-8');
  }
  
  public static function time2str($ts){
        
    if(!ctype_digit($ts)) $ts = strtotime($ts);

    $diff = time() - $ts;
        
    if($diff == 0) return 'now';
    
    if($diff > 0) {
        $day_diff = floor($diff / 86400);
        if($day_diff == 0){
            if($diff < 60) return 'just now';
            if($diff < 120) return '1 minute ago';
            if($diff < 3600) return floor($diff / 60) . ' minutes ago';
            if($diff < 7200) return '1 hour ago';
            if($diff < 86400) return floor($diff / 3600) . ' hours ago';
        }
        if($day_diff == 1) return 'Yesterday';
        if($day_diff < 7) return $day_diff . ' days ago';
        if($day_diff < 31) return ceil($day_diff / 7) . ' weeks ago';
        if($day_diff < 60) return 'last month';
        return date('F Y', $ts);
    } else {
        $diff = abs($diff);
        $day_diff = floor($diff / 86400);
        if($day_diff == 0){
            if($diff < 120) return 'in a minute';
            if($diff < 3600) return 'in ' . floor($diff / 60) . ' minutes';
            if($diff < 7200) return 'in an hour';
            if($diff < 86400) return 'in ' . floor($diff / 3600) . ' hours';
        }
        if($day_diff == 1) return 'Tomorrow';
        if($day_diff < 4) return date('l', $ts);
        if($day_diff < 7 + (7 - date('w'))) return 'next week';
        if(ceil($day_diff / 7) < 4) return 'in ' . ceil($day_diff / 7) . ' weeks';
        if(date('n', $ts) == date('n') + 1) return 'next month';
        return date('F Y', $ts);
    }
  }
  
  public static function slugify($str){
    $str = preg_replace('/[^a-zA-Z0-9 -]/', '', $str);
    $str = strtolower(str_replace(' ', '-', trim($str)));
    $str = preg_replace('/-+/', '-', $str);
    return $str;
  }

}