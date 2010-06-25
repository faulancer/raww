<?php

class PagesController extends Controller{

  public function display($view){
    $parts = explode('/',$view);
    
    for($i=0,$max=count($parts);$i<$max;$i++){
      $parts[$i] = Inflector::underscore($parts[$i]);
    }
    
    $this->view = RAWW_APP_MODULES.'app'.DS.'views'.DS.'pages'.DS.implode(DS,$parts).'.tpl';
  }
  
}

?>