<?php

App::addIncludePath(RAWW_APPS_VENDOR);
App::import(RAWW_APPS_VENDOR.'Zend'.DS.'Loader.php'); 

class Zend{
  /**
  * ...
  *
  */ 
  public static function loadClass($class, $dirs = null){
        Zend_Loader::loadClass($class, $dirs);
  }
  /**
  * ...
  *
  */ 
  public static function loadFile($filename, $dirs = null, $once = false){
        Zend_Loader::loadFile($filename, $dirs, $once);
  } 
  /**
  * ...
  *
  */ 
  public static function isReadable($filename) {
        Zend_Loader::isReadable($filename);
  }
}
?>