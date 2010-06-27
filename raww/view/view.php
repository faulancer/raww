<?php
  
  class View extends RawwObject{
    
    public $name;
    public $module;
    public $action;
    public $vars;
    public $view;
    public $layout;
    public $controller;
    
    protected $_loadedHelpers = array();
    
    public function __construct(&$controller){
      
      $this->controller = $controller;
      $this->name       = $controller->name;
      $this->module     = $controller->module;
      $this->action     = $controller->action;
      $this->vars       = $controller->_viewVars;
      
    }
    
    /**
    * ...
    *
    * @return ?
    */
    public function set($name,$value){
      $this->vars[$name] = $value;
    }
    
    /**
    * ...
    *
    * @return ?
    */
    public function render(){
      
      if($this->controller->layout != 'json' && $this->controller->layout != 'element'){
        $this->_renderView();   
      }
      return $this->_renderLayout();
    }
    
    /**
    * ...
    *
    * @return ?
    */
    public function element($elementName,$vars=array()){
      extract($this->vars);
      extract($vars);
      
      if(Utils::isFile($elementName)){
        $___elementPath = $elementName;
      }else{
        
        $___module = Inflector::underscore($this->controller->module);
        
        if(strpos($elementName,':')){
          $___temp   = explode(':',$elementName);
          $___module = Inflector::underscore($___temp[0]);
        }
        
        $___elementModPath = RAWW_APP_MODULES.$___module.DS.'views'.DS.'_elements'.DS.$elementName.'.tpl';
        $___elementAppPath = RAWW_APP_MODULES.'app'.DS.'views'.DS.'_elements'.DS.$elementName.'.tpl';
      
        $___elementPath    = file_exists($___elementModPath) ? $___elementModPath:$___elementAppPath;
      }
      
      ob_start();
      include($___elementPath);
      $out = ob_get_clean();
      
      return $out;
      
    }
    
    /**
    * ...
    *
    * @return ?
    */
    public function _renderView(){
      extract($this->vars);
      
      if(Utils::isFile($this->controller->view)){
        $___viewPath = $this->controller->view; 
      }else{
        $___module      = Inflector::underscore($this->controller->module);
        $___viewModPath = RAWW_APP_MODULES.$___module.DS.'views'.DS.Inflector::underscore($this->controller->name).DS.Inflector::underscore($this->controller->view).'.tpl';
        $___viewAppPath = RAWW_APP_MODULES.'app'.DS.'views'.DS.Inflector::underscore($this->controller->name).DS.Inflector::underscore($this->controller->view).'.tpl';
        
        $___viewPath    = file_exists($___viewModPath) ? $___viewModPath:$___viewAppPath;
      }
      
      ob_start();
      include($___viewPath);
      $this->vars['content_for_layout'] = ob_get_clean();
      
      return $this->vars['content_for_layout'];
    }
    
    /**
    * ...
    *
    * @return ?
    */
    public function _renderLayout(){
      extract($this->vars);

      if(Utils::isFile($this->controller->layout)){
        $___layoutPath = $this->controller->layout; 
      }else{
        
        $___module = Inflector::underscore($this->controller->module);
        
        if(strpos($this->controller->layout,':')){
          $___temp   = explode(':',$this->controller->layout);
          $___module = Inflector::underscore($___temp[0]);
        }
        
        $___layoutModPath = RAWW_APP_MODULES.$___module.DS.'views'.DS.'_layouts'.DS.Inflector::underscore($this->controller->layout).'.tpl';
        $___layoutAppPath = RAWW_APP_MODULES.'app'.DS.'views'.DS.'_layouts'.DS.Inflector::underscore($this->controller->layout).'.tpl';
        
        $___layoutPath    = file_exists($___layoutModPath) ? $___layoutModPath:$___layoutAppPath;
      
      }
      
      ob_start();
      
      include($___layoutPath);
      
      $out = ob_get_clean();
      
      return $out;
    }
    
    /**
    * ...
    *
    * @return ?
    */
    public function __get($name) {
        return App::getHelper($name);
    }
    
  }

?>