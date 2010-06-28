<?php

class Controller extends RawwObject{

  public $name       = null;
  public $module     = null;
  public $params     = array();
  
  public $viewClass  = 'View';
  public $_viewVars  = array();
  public $action     = array();
  
  public $isRendered= false;
  public $autoRender = true;
  
  public $view;
  public $layout     = 'default';

  
  /**
  * Callback functions
  *
  */
  public function before_filter(){
    return true;
  }

  public function after_filter(){
    return true;
  }
  
  public function before_render(){
    return true;
  }
  
  public function after_render(){
    return true;
  }
  
  public function index(){
  
  }
  
  public function __construct(){
    
    $this->name = str_replace('Controller','',get_class($this));

    $routerSegments = Router::getParsedUri();
    
    $this->action            = $routerSegments['action'];
    $this->module            = $routerSegments['module'];
    $this->params['action']  = $routerSegments['params'];
    $this->params['get']     = $_GET;
    $this->params['post']    = $_POST;
    $this->params['request'] = $_REQUEST;
    
    $this->view = $this->action;
    
    if(Req::is('ajax')) $this->layout = 'ajax';
    
  }
  
  /**
  * ...
  *
  * @return ?
  */
  public function render($view=null,$layout=null){
    
    if(!$this->autoRender || $this->isRendered) return;
    
    $this->before_render();
      $View = new $this->viewClass($this);
      echo $View->render();
    $this->after_render();
    
    $this->isRendered = true;
    
    return true;
  }

  /**
  * ...
  *
  * @return ?
  */  
  public function set($name,$value){
    $this->_viewVars[$name] = $value;
  }
  
  /**
  * ...
  *
  * @return ?
  */  
  public function is_set($name){
    return isset($this->_viewVars[$name]);
  }
  
  /**
  * ...
  *
  * @return ?
  */  
  function redirect($url) {
     Req::redirect($url);
  }
  
  
  /**
  * ...
  *
  * @return ?
  */  
  function flash($message, $url, $pause = 1){
    $this->layout     = 'flash';
     
    $this->set('message', $message); 
    $this->set('url', $url); 
    $this->set('pause', $pause);
    
    $this->render();
  }
  
}

?>