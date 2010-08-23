<?php

class Dispatcher{
  
  /**
  * ...
  *
  */ 
  public static function delegate(){
    
    $parsedUri = Router::getParsedUri();
    
    if(is_null($parsedUri['controller'])){
      RawwError::showInfoPage('missing_startroute');
    }
    
    switch($parsedUri['controller']){
      
      // RAWW WEB SHELL
      //------------------------------------------------------------------------------------------
      case 'rawwapi':
        if(Config::read('App.Debug.level')>0){
          App::import(RAWW_CORE.'shell'.DS.'raww_webapi.php');
          RawwWebapi::pareseRequest($parsedUri);
        }
        break;
      
      // ASSETS HANDLER
      //------------------------------------------------------------------------------------------
      case 'assets':
        
        Assets::output($parsedUri['action'],$parsedUri['params']);
        break;
      
      // CONTROLLER HANDLER
      //------------------------------------------------------------------------------------------
      default:
      
        $controllerFile = RAWW_APP_MODULES.$parsedUri['module'].DS.'controllers'.DS.Inflector::underscore($parsedUri['controller']).'.php';
        $controllerName = Inflector::camelize($parsedUri['controller']).'Controller'; 
        
        if(!App::import($controllerFile)){
          RawwError::showInfoPage('missing_controller',array(
            'controllerName' => $controllerName,
            'controllerFile' => $controllerFile
          ));
        }

        $controller = new $controllerName();
        
        if(!method_exists($controller,$parsedUri['action'])){
          RawwError::showInfoPage('missing_action',array(
            'controllerName'   => $controllerName,
            'controllerAction' => $parsedUri['action'],
            'controllerFile'   => $controllerFile
          ));
        }
        
        Output::start();
        
        $controller->before_filter();
        
        switch(count($parsedUri['params'])){
          case 0:
            $controller->{$parsedUri['action']}();
            break;
          case 1:
            $controller->{$parsedUri['action']}($parsedUri['params'][0]);
            break;
          case 2:
            $controller->{$parsedUri['action']}($parsedUri['params'][0],$parsedUri['params'][1]);
            break;
          case 3:
            $controller->{$parsedUri['action']}($parsedUri['params'][0],$parsedUri['params'][1],$parsedUri['params'][2]);
            break;
          case 4:
            //$controller->{$parsedUri['action']}($parsedUri['params']0],$params[1],$parsedUri['params'][2],$parsedUri['params'][3]);
            break;
          default:
            call_user_func_array(array(&$controller, $parsedUri['action']), $parsedUri['params']);
        }
        
        
        if ($controller->autoRender && !$controller->isRendered) {
          $controller->render();
        }
        
        $controller->after_filter();
        
        Output::release();
      
        break;
    }
  }
  
 
}