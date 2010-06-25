<?php

class Output{
  
  public static $respondAs = false;
  
  /**
  * ...
  *
  */ 
  public static function start(){
    ob_start();
  }
  
  /**
  * ...
  *
  */ 
  public static function release(){
    
    if(self::$respondAs){
    
    }
    
    echo ob_get_clean();
  }
}

?>