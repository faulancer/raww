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
  
  public static function strlen($str){
		return mb_strlen($str);
	}
  
  public static function strpos($str, $search, $offset = false){
    if (strlen($str) && strlen($search)){
      return ($offset === false) ? mb_strpos($str, $search) : mb_strpos($str, $search, $offset);
		}else{
      return false;
    }
	}
  
  public static function substr($str, $offset, $length = false){
    return ($length===false) ? mb_substr($str, $offset) : mb_substr($str, $offset, $length);
	}
  
  public static function strtolower($str){
		return mb_strtolower($str);
	}
  
  public static function strtoupper($str){
		return mb_strtoupper($str);
	}

}
?>