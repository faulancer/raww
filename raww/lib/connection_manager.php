<?php

Config::load('connections');

class ConnectionManager{
   
  protected static $sources = array();
   
  /**
  * ...
  *
  */ 
  private static function _createConnection($connection){
    
    $config = Config::read('App.connections.'.$connection);
    
    if(!isset($config['datasource'])){
      $config['datasource'] ="Pdo";
    }
    
    $dataSource = $config['datasource'].'Datasource';
    
    $parts = explode(':',$dataSource);
  
    switch(count($parts)){
      case 1:
        $module = 'app';
        $dataSourceFile  = Inflector::underscore($parts[0]).'.php';
        $className = $config['datasource'].'DataSource';
        break;
      case 2:
        $module = Inflector::underscore($parts[0]);
        $dataSourceFile  = Inflector::underscore($parts[1]).'.php';
        $className = $parts[1].'DataSource';
        break;
    }
    
    if(!App::classExists($className)){
      if(!App::import(RAWW_APP.'modules'.DS.$module.DS.'_datasource'.DS.$dataSourceFile)){

      }
    }
    

    self::$sources[$connection] = new $className($config);  
       
  }  
  
  /**
  * ...
  *
  */ 
  public static function getDatasource($connection){
    
    if(!isset(self::$sources[$connection])){
      self::_createConnection($connection);
    }

    return self::$sources[$connection];
    
  }  
  
}
