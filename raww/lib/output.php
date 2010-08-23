<?php

class Output{
  
  public static $respondAs = 'html';
  
  /**
  * ...
  *
  */ 
  public static function start(){
    if(Config::read('App.GzipOutput', true) && !headers_sent() && !ob_start("ob_gzhandler")){
        ob_start();
    }
  }
  
  /**
  * ...
  *
  */ 
  public static function release(){
    
    if(self::$respondAs){
        header("Content-type: ".Req::getMimeType(self::$respondAs));
    }

  }
}