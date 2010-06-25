<?php

class Model extends RawwObject{
  
  protected $name;  

  /**
  * ...
  *
  */ 
  public function __construct(){
    
    $this->name = str_replace('Model','',get_class($this));
  
  }  
}

?>