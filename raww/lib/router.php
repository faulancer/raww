<?php



class Router{
  
  protected static $uri;
  protected static $base;
  protected static $params;
  protected static $parsedUri;
  
  protected static $_routes = array();
  
  protected static $parsedUriTpl = array(
      'module'     => 'app',
      'controller' => 'app',
      'action'     => 'index',
      'params'     => array()
  );
  
  /**
  * ...
  *
  */ 
  public static function connect($path,$options=array()){
    
    self::$_routes[] = array(
      'path'    => $path,
      'options' => array_merge(self::$parsedUriTpl,$options)
    );
    
  }
  
  /**
  * ...
  *
  */ 
  public static function getBaseUrl(){
    
    if(!is_null(self::$base)) return self::$base;
    
    $uri  = explode('/',$_SERVER['REQUEST_URI']);
    $self = explode('/',$_SERVER['PHP_SELF']);
    
    $tmp = array();
    
    for($i=0,$max=count($uri);$i<$max;$i++){
      if($uri[$i]!=$self[$i]){
        break;
      }
      $tmp[] = $uri[$i];
    }
    
    self::$base = implode('/',$tmp);
    
    return self::$base;
  }
  
  /**
  * ...
  *
  */ 
  public static function getUri(){
    
    if(!is_null(self::$uri)) return self::$uri;

    $uri  = explode('/',$_SERVER['REQUEST_URI']);
    $self = explode('/',$_SERVER['PHP_SELF']);
    
    $tmp = array();
    
    for($i=0,$max=count($uri);$i<$max;$i++){
      if(isset($self[$i]) && $uri[$i]===$self[$i]){
        continue;
      }
      $tmp[] = $uri[$i];
    }
    
    self::$uri = implode('/',$tmp);
    
    return self::$uri;
  }
  
  /**
  * ...
  *
  */ 
  public static function getParsedUri(){
    
    if(!is_null(self::$parsedUri)) return self::$parsedUri;

    $parsedUri = self::$parsedUriTpl;
    
    $clean_url = (self::getBaseUrl()!='') ? str_replace(self::getBaseUrl().'/','',$_SERVER['REQUEST_URI']):$_SERVER['REQUEST_URI'];
    $clean_url = preg_replace("/\?(.*)/", '', $clean_url);
    $clean_url = trim(str_replace('//','/',$clean_url));
    
    if(strlen($clean_url) > 1 && substr($clean_url,-1)=='/'){
      $clean_url = substr($clean_url,0,strlen($clean_url)-1);
    }
    
    if($clean_url=='') $clean_url = '/';

    $matchedRoute = self::matchRoutes($clean_url);
    
    if(is_array($matchedRoute)){
      self::$parsedUri = $matchedRoute;
      return $matchedRoute;
    }
    
    if($clean_url=='/') return $parsedUri;
    
    $parts = explode('/',$clean_url);
    
    if(substr($clean_url,0,1)=='/'){
      $parts = array_slice($parts,1);
    }
    
    //check if module
    //-----------------------------------------------------
    if(is_dir(RAWW_APP_MODULES.Inflector::underscore($parts[0]))){
      $parsedUri['module'] = $parts[0];
      $parts = array_slice($parts,1);
      
      
      switch(count($parts)){
        case 0:
          $parts[0] = $parsedUri['module'];
          break;
        case 1:
        case 2:
        
          $controllerFile = RAWW_APP_MODULES.$parsedUri['module'].DS.'controllers'.DS.Inflector::underscore($parts[0]).'.php';

          if(!file_exists($controllerFile)){
            array_unshift($parts, $parsedUri['module']);
          }
          break;
      }
    }
    //-----------------------------------------------------
    
    switch(count($parts)){
      case 1:
        if($parts[0]!=='') $parsedUri['controller'] = $parts[0];
        break;
      case 2:
        $parsedUri['controller'] = $parts[0];
        $parsedUri['action']     = $parts[1];
        break;
      default:
        $parsedUri['controller'] = $parts[0];
        $parsedUri['action']     = $parts[1];
        $parsedUri['params']     = array_slice($parts,2);
    }
    
    self::$parsedUri = $parsedUri;

    return self::$parsedUri;
  }
  
  /**
  * ...
  *
  */ 
  public static function matchRoutes($url){
    
    if(substr($url,0,1)!='/') $url = '/'.$url;
    
    foreach(self::$_routes as $r){
      
      if($r['path']==$url){
        return $r['options']; 
      }
      
      if(substr($r['path'],-1)=='*'){
        if(substr($url,0,strlen($r['path'])-2)==substr($r['path'],0,strlen($r['path'])-2)){
          
          $options = $r['options'];
          $tmp     = substr($url,strlen($r['path'])-2);
          
          //assign params
          if(!count($options['params']) && $tmp!='/' && $tmp!=''){
            $options['params'] = explode('/',substr($tmp,1));
          }

          return $options;
        }
      }
      
      if(substr($r['path'],0,1)=='#' && substr($r['path'],-1)=='#'){
        
        if(preg_match($r['path'],$url,$matches)){
          //assign params
          if(!count($r['options']['params'])){
            $r['options']['params'][0] = $matches;
          }else{
            for($i=0,$max=count($r['options']['params']);$i<$max;$i++){
              foreach($matches as $index => $m){
                $r['options']['params'][$i] = str_replace('::'.$index,$m,$r['options']['params'][$i]);
              }
            }
          }
          
          return $r['options'];
        }
      }
      
    }
    
    return false;
  }
}