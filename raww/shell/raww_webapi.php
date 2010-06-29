<?php
  
class RawwWebapi{
  
  /**
  * ...
  *
  * @return ?
  */
  public static function pareseRequest($arrRequest){

    switch(strtolower($arrRequest['action'])){
      
      case 'create':
        self::create($arrRequest['params'][0],array_slice($arrRequest['params'],1));
        break;
        
      case 'run':
        
        $params    = array_slice($arrRequest['params'],1);
        
        $taskFile  = RAWW_APP_TASKS.Inflector::underscore($params[0]);
        $className = $params[0].'Task';
        
        if(App::import($taskFile)){
          $task = new $className(( count($params)>1 ? array_slice($params,1) : array() ));
          $task->run();
        }
        break;
        
      default:
        if(Config::read('App.Debug.level') > 0){
          require_once(RAWW_CORE.'templates'.DS.'webapi.tpl');
        }
    }
    
    die();
  }
  
  /**
  * ...
  *
  * @return ?
  */
  public static function create($resource,$params=array()){
    
    //build named params
    foreach($params as $p){
      $p = explode(':',$p);      
      if(count($p)==2) $params[trim($p[0])] = trim($p[1]);
    }

    switch(strtolower($resource)){
      
      case 'controller':
        
        $parts = explode('.',$params[0]);
         
        switch(count($parts)){
          case 1:
            $module = 'app';
            $controllerFile  = Inflector::underscore($parts[0]).'.php';
            $className = $params[0];
            break;
          case 2:
            $module = Inflector::underscore($parts[0]);
            $controllerFile  = Inflector::underscore($parts[1]).'.php';
            $className = $parts[1];
            break;
        }
        
        $fileContent = str_replace('{controllerName}',$className,file_get_contents(RAWW_CORE.'templates'.DS.'api'.DS.'controller.tpl'));
        
        file_put_contents(RAWW_APP_MODULES.$module.DS.'controllers'.DS.$controllerFile,$fileContent);
         
        break;
      
      case 'model':
      
        $parts = explode('.',$params[0]);
         
        switch(count($parts)){
          case 1:
            $module = 'app';
            $modelFile  = Inflector::underscore($parts[0]).'.php';
            $className = $params[0];
            break;
          case 2:
            $module = Inflector::underscore($parts[0]);
            $modelFile  = Inflector::underscore($parts[1]).'.php';
            $className = $parts[1];
            break;
        }
        
        $fileContent = str_replace('{modelName}',$className,file_get_contents(RAWW_CORE.'templates'.DS.'api'.DS.'model.tpl'));
        
        file_put_contents(RAWW_APP_MODULES.$module.DS.'models'.DS.$modelFile,$fileContent);
        
        
        break;
        
      case 'view':
        file_put_contents(RAWW_APP_MODULES.Inflector::underscore($params[0]).DS.'views'.DS.Inflector::underscore($params[1]).DS.Inflector::underscore($params[1]).'.tpl','');
        break;
        
    }
    
    return array();
    
  }
  
}
?>