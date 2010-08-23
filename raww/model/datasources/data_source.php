<?php

class DataSource{
    
    public $log = array();
    
    
    /**
    * ...
    *
    */ 
    public function getTables() {
      
      return array();
    }
    
    /**
    * ...
    *
    */ 
    public function getColumns($table) {
      return array();
    }
    
    /**
    * ...
    *
    * @return ?
    */
    public function find($options){    
    
      return array();
    }
    
    /**
    * ...
    *
    * @return ?
    */
    public function field($options){    
      return null;
    }
    
    /**
    * ...
    *
    * @return ?
    */
    public function create($table,$data){    
      
      return false;
    }
    
    /**
    * ...
    *
    * @return ?
    */
    public function delete($table,$conditions){    
      
      return false;
    }
    
    /**
    * ...
    *
    * @return ?
    */
    public function update($table,$data,$conditions=array()){    
       
        return false;
    }
    
    /**
    * ...
    *
    * @return ?
    */
    public function truncate($table){    
      return false;
    }
    
    /**
    * ...
    *
    * @return ?
    */
    public function execute($data){    
      return false;
    }
}