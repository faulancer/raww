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
      die(ob_get_clean());
      
    }else{
      self::show404();      
    }
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
  
  
  public static function show404(){

      if(file_exists(RAWW_APP_MODULES.'app'.DS.'views'.DS.'_layouts'.DS.'404.tpl')){
        ob_start();
            include(RAWW_APP_MODULES.'app'.DS.'views'.DS.'_layouts'.DS.'404.tpl');
        echo ob_get_clean();
      }else{
        header("HTTP/1.0 404 Not Found");
      }
      
      die();
  }
  
}