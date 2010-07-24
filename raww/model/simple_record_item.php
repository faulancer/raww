<?php

class SimpleRecordItem extends RawwObject{
  
  protected $model;
  protected $id;
  protected $data;
  
  /**
  * ...
  *
  */ 
  public function __construct($model,$data=array()){
    $this->model = $model;
    $this->data  = $data;
    
    if(isset($data[$this->model->primary_key]) && is_numeric($data[$this->model->primary_key])){
        $this->id = $data[$this->model->primary_key];
    }
  }

  /**
  * ...
  *
  */ 
  public function save($data=false){
    
    if(is_array($data)){
       $this->data = Utils::am($this->data, $data);
    }
    
    if($this->id){
      $this->model->update($this->data,$this->id);  
    }else{
      $this->id = $this->model->create($this->data);
    }
    
  }
  
  /**
  * ...
  *
  */
  public function __set($name, $value){
    $this->data[$name] = $value;
    
    if($name==$this->model->primary_key){
        $this->model->primary_key = $value;
    }
  }

  /**
  * ...
  *
  */
  public function __get($name){
    return isset($this->data[$name]) ? $this->data[$name] : null;
  }
  
}
