<?php

class Auth {

  /**
  * ...
  *
  */
  protected static $config = array(
    'key'     => 'Auth',
    'enabled' => true,
    'login'   => array(
      'module'     => 'app',
      'controller' => 'users',
      'action'     => 'login'
    )
  );
  
  /**
  * ...
  *
  */ 
  public static function login($userData){
    Session::write(self::$config['key'],$userData);
  }
  
  /**
  * ...
  *
  */ 
  public static function logout(){
    Session::delete(self::$config['key']);
  }
  
  /**
  * ...
  *
  */ 
  public static function isVerified(){
    return (Session::read(self::$config['key'])) ? true: false;
  }
  
  /**
  * ...
  *
  */ 
  public static function get($path=''){
    return (empty($path)) ? Session::read(self::$config['key']) : Session::read(self::$config['key'].'.'.$path);
  }

  /**
  * ...
  *
  */ 
  public static function set($config){
    self::$config = Utils::am(self::$config,$config);
  }
  
  
  /**
  * ...
  *
  */ 
  public static function enable(){
    self::$config['enabled'] = true;
  }
  
  /**
  * ...
  *
  */ 
  public static function disable(){
    self::$config['enabled'] = false;
  }
  
  /**
  * ...
  *
  */ 
  public static function check($callback=false){
    if(!$callback){
      if(!self::isVerified() && self::$config['enabled']){
        Req::redirect('/'.implode('/',array_values(self::$config['login'])));
      }
    }else{
      if(Utils::is_callback($callback)){
          call_user_func_array($callback, array());
      }
    }
  }
  
}

?>