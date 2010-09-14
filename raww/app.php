<?php

require_once(RAWW_CORE.'lib'.DS.'path.php');
require_once(RAWW_CORE.'lib'.DS.'utils.php');

class App{

  /**
  * ...
  *
  */ 
  public static $_autoloadClasses = array(
      // MAIN
      'RawwObject'       => 'lib:raww_object.php',
      'RawwTask'         => 'lib:raww_task.php',
      'RawwEvent'        => 'lib:raww_event.php',
      'ObjRegistry'      => 'lib:obj_registry.php',
      'Router'           => 'lib:router.php',
      'Req'              => 'lib:req.php',
      'Dispatcher'       => 'lib:dispatcher.php',
      'Output'           => 'lib:output.php',
      'Inflector'        => 'lib:inflector.php',
      'Config'           => 'lib:config.php',
      'Cache'            => 'lib:cache.php',
      'Session'          => 'lib:session.php',
      'I18n'             => 'lib:i18n.php',
      'Utils'            => 'lib:utils.php',
      'Security'         => 'lib:security.php',
      'Set'              => 'lib:set.php',
      'String'           => 'lib:string.php',
      'Debug'            => 'lib:debug.php',

      'Model'            => 'lib:model/model.php',
      'SimpleRecord'     => 'lib:model/simple_record.php',
      'SimpleRecordItem' => 'lib:model/simple_record_item.php',
      'Controller'       => 'lib:controller/controller.php',
      'View'             => 'lib:view/view.php',

      'Bench'            => 'lib:bench.php',
      'Validate'         => 'lib:validate.php',
      'Auth'             => 'lib:auth.php',
      'SimpleAcl'        => 'lib:simple_acl.php',
      'Assets'           => 'lib:assets.php',
      'RawwError'        => 'lib:raww_error.php',
      'Zend'             => 'lib:zend.php',

      'ConnectionManager'=> 'lib:connection_manager.php',
      'DataSource'       => 'lib:model/datasources/data_source.php',
      'PdoDataSource'    => 'lib:model/datasources/pdo/pdo.php',

      'HtmlHelper'       => 'lib:view/helpers/html.php',
      'FormHelper'       => 'lib:view/helpers/form.php',
      'TextHelper'       => 'lib:view/helpers/text.php'
  );
  
  
  /**
  * ...
  *
  * @return ?
  */ 
  public static function import($file,$folder = null){
    if(!Utils::isFile($file)){
      if(strpos($file,':')){
          $file = Path::find($file);
      } else {
          $file = $folder.$file;
      }
    }
    
    if(file_exists($file)){
      require_once($file);
    }else{
      return false;
    }
    return true;
  }

  /**
  * ...
  *
  * @return ?
  */
  public static function addClass($className,$path){
      self::$_autoloadClasses[$className] = $path;
  }
  
  /**
  * ...
  *
  * @return ?
  */
  public static function classExists($className){
    return (isset(self::$_autoloadClasses[$className]) || class_exists($className));
  }
  
  /**
  * ...
  *
  * @return ?
  */
  public static function registerAutoloader($callback){
		spl_autoload_unregister(array('App','autoload'));
		spl_autoload_register($callback);
		spl_autoload_register(array('App','autoload'));
	}
  
  /**
  * ...
  *
  * @return ?
  */
  public static function autoload($className){
		
    $classPath = null;
    
    switch($className){
      case 'AppController':
        $classPath = RAWW_APP_MODULES.'app_controller.php';
        break;
      case 'AppModel':
        $classPath = RAWW_APP_MODULES.'app_model.php';
        break;
      default:
        
        if(isset(self::$_autoloadClasses[$className])){
          self::import(self::$_autoloadClasses[$className],RAWW_CORE);
          return;
        }else{
          
          //check for module controller/module
          if(preg_match('/(Model|Controller)$/',$className)){
            
            $module = Inflector::underscore(str_replace(array('Controller','Model'),'',$className));
            
            if(strpos($className,'Controller')){
              $path_controller = RAWW_APP_MODULES.$module.DS.$module.'_controller.php';
              if(file_exists($path_controller)) require_once($path_controller);
            }else{
              $path_model = RAWW_APP_MODULES.$module.DS.$module.'_model.php';
              if(file_exists($path_model)) require_once($path_model);        
            }
            
          }
        }
    }
    
    if($classPath){
      include_once $classPath;
    }else{
      //todo
    }
    
	}
  
  /**
  * ...
  *
  * @return ?
  */
  public static function addIncludePath($path){
    
    if(is_array($path)){
      foreach($path as $p){
        set_include_path(get_include_path() . PATH_SEPARATOR . $p);
      }
    }else{
      set_include_path(get_include_path() . PATH_SEPARATOR . $path);
    }
  }
  
  /**
  * ...
  *
  * @return ?
  */
  public static function getModel($modelName){
    
    $parts = explode(':',$modelName);
    
    switch(count($parts)){
      case 1:
        $module = 'app';
        $modelFile  = Inflector::underscore($parts[0]).'.php';
        $className = $modelName.'Model';
        break;
      case 2:
        
        $parts[0] = (strlen($parts[0])) ? $parts[0]:$parts[1];
        
        $module = Inflector::underscore($parts[0]);
        $modelFile  = Inflector::underscore($parts[1]).'.php';
        $className = $parts[1].'Model';
        break;
    }    

    if(!App::classExists($className)){
      if(!self::import(RAWW_APP_MODULES.$module.DS.'models'.DS.$modelFile)){
        ObjRegistry::set($className, new SimpleRecord($className));
      }
    }
    
    return ObjRegistry::get($className);
  }
  
  /**
  * ...
  *
  * @return ?
  */
  public static function getHelper($helperName){
      
      $parts = explode(':',$helperName);
    
      switch(count($parts)){
        case 1:
          $module = 'app';
          $helperFile  = Inflector::underscore($parts[0]).'.php';
          $className = $helperName.'Helper';
          break;
        case 2:
          $module = Inflector::underscore($parts[0]);
          $helperFile  = Inflector::underscore($parts[1]).'.php';
          $className = $parts[1].'Helper';
          break;
      }
      
      if(!App::classExists($className)){
        if(!self::import(RAWW_APP_MODULES.$module.DS.'views'.DS.'_helpers'.DS.$helperFile)){

        }
      }
      
    return ObjRegistry::get($className);
  }
  
  /**
  * ...
  *
  * @return ?
  */
  public static function getDatasource($connection="default"){
    return ConnectionManager::getDatasource($connection);
  }
  
  /**
  * ...
  *
  * @return ?
  */
  public static function shutdown($message=null){
    die($message);
  }
  
  
}

spl_autoload_register(array('App','autoload'));