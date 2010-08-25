<?php

class Set{
  /**
  * ...
  *
  */ 
  public static function extract($path, $array){
    
    if(!count($array)) return $array;
    
    $result = array();

    $path = explode(".",$path);
    $dimension = count($path);
    
    switch($dimension){
      case 0:
        return $array;
        break;
      
      case 1;
        foreach($array as $a){
          if(isset($a[$path[0]])){
            $result[] = $a[$path[0]];
          }
        }
        break;
      
      case 2;
        foreach($array as $a){
          if(isset($a[$path[0]][$path[1]])){
            $result[] = $a[$path[0]][$path[1]];
          }
        }
        break;
      
      case 3;
        foreach($array as $a){
          if(isset($a[$path[0]][$path[1]][$path[2]])){
            $result[] = $a[$path[0]][$path[1]][$path[2]];
          }
        }
        break;
      
      case 4;
        foreach($array as $a){
          if(isset($a[$path[0]][$path[1]][$path[2]][$path[3]])){
            $result[] = $a[$path[0]][$path[1]][$path[2]][$path[3]];
          }
        }
        break;
      case 5;
        foreach($array as $a){
          if(isset($a[$path[0]][$path[1]][$path[2]][$path[3]][$path[4]])){
            $result[] = $a[$path[0]][$path[1]][$path[2]][$path[3]][$path[4]];
          }
        }
        break;
    }
    
    return $result;
    
  }
  
  public static function countDim($array = null, $all = false, $count = 0) {
		if ($all) {
			$depth = array($count);
			if (is_array($array) && reset($array) !== false) {
				foreach ($array as $value) {
					$depth[] = Set::countDim($value, true, $count + 1);
				}
			}
			$return = max($depth);
		} else {
			if (is_array(reset($array))) {
				$return = Set::countDim(reset($array)) + 1;
			} else {
				$return = 1;
			}
		}
		return $return;
  }

  public static function get($array, $key, $default=false){
      return isset($array[$key]) ? $array[$key] : $default;
  }
}