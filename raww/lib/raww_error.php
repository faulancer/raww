<?php

class RawwError{
  
  protected static $_errors = array();
  
  /**
  * ...
  *
  */ 
  public static function showInfoPage($message,$params=array()){
    
    if (Config::read('App.Debug.level') > 0){
    
      extract($params);
      
      ob_start();
        include(RAWW_CORE.'templates'.DS.'default.tpl');
      echo ob_get_clean();
      
    }else{
      header("HTTP/1.0 404 Not Found");
    }
    
    die();
    
  }
  
  /**
  * ...
  *
  */ 
  public static function getMessage($message,$params=array()){
    
    $ret = "";
    
    if (Config::read('App.Debug.level') > 0){
    
      extract($params);
    
      ob_start();
        include(RAWW_CORE.'templates'.DS.'messages'.DS.$message.'.tpl');
      echo ob_get_clean();
    }
    
    
    return $ret;
    
  }
  
}

?>