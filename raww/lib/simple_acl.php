<?php

class SimpleAcl {

  /**
  * ...
  *
  */
  private $resources = array();
  private $roles     = array();
  private $rights    = array();
  
  /**
  * ...
  *
  */ 
  public function addResource($resource, $actions = array()){
    $this->resources[$resource] = $actions;
  }
  
  /**
  * ...
  *
  */ 
  public function addRole($role, $isSuperAdmin = false){
    $this->roles[$role] = $isSuperAdmin;
  }
  
  /**
  * ...
  *
  */ 
  public function allow($role, $resource, $actions = array()){
    
    if(isset($this->resources[$resource]) && isset($this->roles[$role])){
        
        $actions = (array)$actions;
        
        if(!count($actions)){
            $actions = $this->resources[$resource];
        }
        
        foreach($actions as &$action){
            if(in_array($action, $this->resources[$resource])){
                $this->rights[$role][$resource][$action] = true;
            }
        }
    }
  }
  
  /**
  * ...
  *
  */ 
  public function deny($role, $resource, $actions = array()){
    
    if(isset($this->resources[$resource]) && isset($this->roles[$role])){
        
        $actions = (array)$actions;
        
        if(!count($actions)){
            $actions = $this->resources[$resource];
        }
        
        foreach($actions as &$action){
            if(isset($this->rights[$role][$resource][$action])){
                unset($this->rights[$role][$resource][$action]);
            }
        }
    }
  }

  /**
  * ...
  *
  */ 
  public function isAllowed($role, $resource, $action){
    
    if(!isset($this->resources[$resource]) || !isset($this->roles[$role])){
        return false;
    }

    //isSuperAdmin or action is allowed
    if($this->roles[$role] || isset($this->rights[$role][$resource][$action])) {
        return true;
    }
    
    return false;
  }
  
}