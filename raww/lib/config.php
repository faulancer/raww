<?php

class Config{
  
  protected static $_collection;
  
  /**
  * ...
  *
  */ 
  public static function load($configFile){
    
    if(!Utils::isFile($configFile)){
      $configFile = RAWW_APP_CONFIG.$configFile.'.php';
    }
    
    include_once($configFile);

    if(isset($config)){
      if(is_array($config)){
        foreach($config as $key => $c){
          self::$_collection[$key] = $c;
        }
      }
    }
    
  }
  
  /**
  * ...
  *
  */  
  public static function read($key){
    
    $keys = explode('.',$key);
    
    switch(count($keys)){
      
      case 1:
        if(isset(self::$_collection[$keys[0]])){
          return self::$_collection[$keys[0]];
        }
        break;
      
      case 2:
        if(isset(self::$_collection[$keys[0]][$keys[1]])){  
          return self::$_collection[$keys[0]][$keys[1]];
        }
        break;
      
      case 3:
        if(isset(self::$_collection[$keys[0]][$keys[1]][$keys[2]])){
          return self::$_collection[$keys[0]][$keys[1]][$keys[2]];
        }
        break;
        
      case 4:
        if(isset(self::$_collection[$keys[0]][$keys[1]][$keys[2]][$keys[3]])){
          return self::$_collection[$keys[0]][$keys[1]][$keys[2]][$keys[3]];
        }
        break;
        
      case 5:
        if(isset(self::$_collection[$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]])){
          return self::$_collection[$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]];
        }
        break;
    }
    
    return null;
  }
  
  /**
  * ...
  *
  */ 
  public static function write($key,$value){
    
    $keys = explode('.',$key);
    
    switch(count($keys)){
      
      case 1:
        self::$_collection[$keys[0]] = $value;
        break;
      
      case 2:
        self::$_collection[$keys[0]][$keys[1]] = $value;
        break;
      
      case 3:
        self::$_collection[$keys[0]][$keys[1]][$keys[2]] = $value;
        break;
        
      case 4:
        self::$_collection[$keys[0]][$keys[1]][$keys[2]][$keys[3]] = $value;
        break;
        
      case 5:
        self::$_collection[$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]] = $value;
        break;
        
      default:
        return false;
    }
    
    return true;
    
  }
  
  /**
  * ...
  *
  */ 
  public static function delete($key){
    $keys = explode('.',$key);
       
    switch(count($keys)){
      
      case 1:
        if(isset(self::$_collection[$keys[0]])){
          unset(self::$_collection[$keys[0]]);
        }
        break;
      
      case 2:
        if(isset(self::$_collection[$keys[0]][$keys[1]])){  
          unset(self::$_collection[$keys[0]][$keys[1]]);
        }
        break;
      
      case 3:
        if(isset(self::$_collection[$keys[0]][$keys[1]][$keys[2]])){
          unset(self::$_collection[$keys[0]][$keys[1]][$keys[2]]);
        }
        break;
        
      case 4:
        if(isset(self::$_collection[$keys[0]][$keys[1]][$keys[2]][$keys[3]])){
          unset(self::$_collection[$keys[0]][$keys[1]][$keys[2]][$keys[3]]);
        }
        break;
        
      case 5:
        if(isset(self::$_collection[$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]])){
           unset(self::$_collection[$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]]);
        }
        break;
        
      default:
        return false;
    }
    
    return true;
  }
}

?>