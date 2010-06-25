<?php

class RawwObject{
  /**
  * ...
  *
  */ 
  public function M($modelName){
    return App::getModel($modelName);
  }
  /**
  * ...
  *
  */ 
  public function H($helperName){
    return App::getHelper($helperName);
  }
  /**
  * ...
  *
  */ 
  public function D($connection="default"){
    return ConnectionManager::getDatasource($connection);
  }
  /**
  * ...
  *
  */ 
  public function log($type, $message=''){
    Debug::log($type, $message);
  }
  
}

?>