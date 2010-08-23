<?php

  
class DbAdapter{
    
    protected $pdoDatasource;
    
    public function __construct($pdoDatasource){
      $this->pdoDatasource = $pdoDatasource;
    }
    
    public function limit($limit,$offset){
      return null;
    }
    
    public function getTables() {
      
      return array();
    }
    
    public function getColumns($table) {
      return array();
    }
  
  
    public function escape($value){
      return addcslashes($value, "\000\n\r\\'\"\032");
    }
    
    public function enquote($name){
      return $name;
    }
}