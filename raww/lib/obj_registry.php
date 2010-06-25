<?php

class ObjRegistry{
  
  protected static $_classes = array();
  
  /**
  * ...
  *
  */ 
  public static function get($class){
    
    if(isset(self::$_classes[$class])){
      return self::$_classes[$class];
    }
    
    if(!class_exists($class)){
      trigger_error("CLASS<$class> not found!");
    }
    
    self::$_classes[$class] = new $class();
    
    return self::$_classes[$class];
    
  }
  
  /**
  * ...
  *
  */ 
  public static function set($className,$object){
    
    self::$_classes[$className] = $object;     
  }
 
}

?>