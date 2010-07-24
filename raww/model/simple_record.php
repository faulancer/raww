<?php

class SimpleRecord extends Model{
  
  protected $table       = null;
  protected $columns     = null;
  
  protected $belongs_to = array();
  protected $has_one    = array();
  protected $__compiledRelations = false;
  
  public $primary_key = 'id';
  public $connection  = 'default';
  
  /**
  * ...
  *
  */ 
  public function __construct($dynClassName=false){
       
      if($dynClassName===false){
        parent::__construct();
      }else{
        $this->name = str_replace('Model','',$dynClassName);
      }
      
      if(is_null($this->table)){
        $this->table = Inflector::underscore(Inflector::toPlural(trim($this->name)));
      }

      $this->__compiledRelations = $this->_buildRelations();
      $this->columns();
  }

  /**
  * ...
  *
  */ 
  public function all($options=array()){
   
     $options = Utils::am(array(
          'conditions' => array(), //array of conditions
          'having'     => array(), //array of conditions
          'table'      => $this->table.' AS '.$this->name,
          'joins'      => array(),
          'fields'     => array('*'), //array of field names
          'order'      => array(), //string or array defining order
          'group'      => array(), //fields to GROUP BY
          'limit'      => null, //int
          'offset'     => null, //int
          'contains'   => '*',
          'cache'      => false
        ),$options);
     
     
     if($options['cache']!==false){
      $cached = Cache::read($options['cache']);
      if($cached) return $cached;
     }
     
     
     if(count($this->__compiledRelations) && $options['contains']){        
        foreach($this->__compiledRelations as $r){
          $options['joins'][]=$r;
        }
     }

     $result = $this->D($this->connection)->find($options);
     
     if($options['cache']!==false){
      Cache::write($options['cache'],$result);
     }
     
     return $result;
  }

  /**
  * ...
  *
  */ 
  public function read($options=array()){
     
     if(is_numeric($options)){
       $id = $options;
       $options = array(
        'conditions' => array($this->name.'.'.$this->primary_key.'='.$id)
       );
     }
     
     $options['limit'] = 1;
     $result =  $this->all($options);
     
     return count($result) ? $result[0]:false;
  }
  
  /**
  * ...
  *
  */ 
  public function field($name,$conditions=array()){
     
     if(is_numeric($conditions)){
       $id = $conditions;
       $conditions = array(
        'conditions' => array($this->name.'.'.$this->primary_key.'='.$id)
       );
     }
    
    $result = $this->read(array('conditions'=>$conditions));
    
    return ($result===false) ? false : $result[$this->name][$name];
    
  }
  
  /**
  * ...
  *
  */ 
  public function lst($options, $format='name'){
    
    $result = $this->all($options);
    
    $list = array();
    
    if(is_string($format)){
        $format = create_function('$row','return str_replace(array_keys($row),array_values($row),'.$format.');');
    }
    
    
    if(count($result)){
      foreach($result as &$r){        
        $list[$r[$this->name][$this->primary_key]] = $format($r[$this->name]); 
      }
    }
    
    return $list;
  }
  
  
  /**
  * ...
  *
  */ 
  public function count($conditions=array()){
    $result = $this->read(array(
      'fields'     => 'COUNT(*) AS CNT',
      'conditions' => $conditions
    ));
    return $result[0]['CNT'];
  }
  
  /**
  * ...
  *
  */ 
  public function max($field,$conditions=array()){
    $result = $this->read(array(
      'fields'     => 'MAX('.$field.') AS MAXVAL',
      'conditions' => $conditions
    ));
    return $result[0]['MAXVAL'];
  }
  
  /**
  * ...
  *
  */ 
  public function min($field,$conditions=array()){
    $result = $this->read(array(
      'fields'     => 'MIN('.$field.') AS MINVAL',
      'conditions' => $conditions
    ));
    return $result[0]['MINVAL'];
  }
  
  /**
  * ...
  *
  */ 
  public function avg($field,$conditions=array()){
    $result = $this->read(array(
      'fields'     => 'AVG('.$field.') AS AVGVAL',
      'conditions' => $conditions
    ));
    return $result[0]['AVGVAL'];
  }
  
  /**
  * ...
  *
  */ 
  public function sum($field,$conditions=array()){
    $result = $this->read(array(
      'fields'     => 'SUM('.$field.') AS SUMVAL',
      'conditions' => $conditions
    ));
    return $result[0]['SUMVAL'];
  }
  
  /**
  * ...
  *
  */ 
  public function columns(){
    
    if(is_null($this->columns)){
      $this->columns = $this->D($this->connection)->getColumns($this->table);
    }
    
    return $this->columns;
  }
  
  /**
  * ...
  *
  */ 
  public function create($data){
    
    if(isset($this->columns['created']) && !isset($data['created'])){
      $data['created'] = time();
    }
    
    return $this->D($this->connection)->create($this->table,$data);
  }
  
  /**
  * ...
  *
  */ 
  public function update($data,$conditions=array()){
   
   if(is_numeric($conditions)){
     $id = $conditions;
     $conditions = array(
      $this->table.'.'.$this->primary_key.'='.$conditions
     );
   }
     
    return $this->D($this->connection)->update($this->table,$data,$conditions);
  }
  
  /**
  * ...
  *
  */ 
  public function delete($conditions){
     
     if(is_numeric($conditions)){
       $id = $conditions;
       $conditions = array(
        $this->table.'.'.$this->primary_key.'='.$conditions
       );
     }
    
    return $this->D($this->connection)->delete($this->table,$conditions);
  }

  /**
  * ...
  *
  */ 
  public function record($data=array()){
     
     if(is_numeric($data)){
        $data = $this->read($data);
        $data = $data[$this->name];
     }
     
     $record = new SimpleRecordItem($this, $data);
    
    return $record;
  }

  
  /**
  * ...
  *
  */ 
  public function __call($name,$arguments){
    
    if(preg_match('/^(find_by_|find_all_by_)/',$name)){
      
      $type  = (substr($name,0,12)=='find_all_by_') ? 'all':'first';
      $query = ($type=='first') ? substr($name,-(strlen($name)-8)) : substr($name,-(strlen($name)-12));
      
      $parts = preg_split('/(_and_|_or_)/i',$query,-1,PREG_SPLIT_DELIM_CAPTURE);
      $conditions = array();
      
      for ($i=0,$a=0,$m=count($parts); $i<$m; $i++){
        if($parts[$i]=='_and_' || $parts[$i]=='_or_') continue;
        
        $prefix = '';
        
        if($i>0){
          $prefix = ($parts[$i-1]=='_and_') ? 'AND ' : 'OR ';
        }
        
        $conditions[] = array($prefix.$parts[$i].'=:value',array('value'=>$arguments[$a]));
        $a++;
      }
      
      $options = array();
      
      $options['fields']     = '*';
      $options['conditions'] = $conditions;
      
      return ($type=='first') ? $this->read($options) : $this->all($options);
    }
    
    trigger_error('method '.$name.' not exists!',E_USER_ERROR);
  }
  
  /**
  * ...
  *
  */ 
  protected function _buildRelations(){
    
    $relations  = array();
    
    $belongs_to = $this->belongs_to;
    $has_one    = $this->has_one;
    
    if(is_string($belongs_to)){
      $t = explode(",",$belongs_to);
      $belongs_to = array();
      foreach($t as $n) $belongs_to[$n] = array();
    }
    if(is_string($has_one)){
      $t = explode(",",$has_one);
      $has_one = array();
      foreach($t as $n) $has_one[$n] = array();
    }
    
    //BelongsTo
    foreach($belongs_to as $relationTo=>$settings){
      
      $relationTo = trim($relationTo);
      
      $Join = array(
        'type'        => 'left',
        'table'       => Inflector::underscore(Inflector::toPlural(trim($relationTo))),
        'alias'       => $relationTo,
        'primary_key' => 'id',
        'foreign_key' => Inflector::underscore($relationTo).'_id',
        'fields'      => '*',
        'conditions'  => array()
      );
      
      if(isset($belongs_to[$relationTo])){
        $Join = Utils::am($Join,$belongs_to[$relationTo]);
      }
      
      array_unshift($Join['conditions'], $Join['alias'].'.'.$Join['primary_key'].'='.$this->name.'.'.$Join['foreign_key']);
      
      $relations[$relationTo] = $Join;
    }
    
    //HasOne
    foreach($has_one as $relationTo=>$settings){
      
      $Join = array(
        'type'        => 'left',
        'table'       => Inflector::underscore(Inflector::toPlural(trim($relationTo))),
        'alias'       => $relationTo,
        'primary_key' => 'id',
        'foreign_key' => Inflector::underscore($this->name).'_id',
        'fields'      => '*',
        'conditions'  => array()
      );
      
      if(isset($has_one[$relationTo])){
        $Join = Utils::am($Join,$belongs_to[$relationTo]);
      }
      
      array_unshift($Join['conditions'],  $Join['alias'].'.'.$Join['foreign_key'].'='.$this->name.'.'.$Join['primary_key']);
      
      $relations[$relationTo] = $Join;
    }
    
    return $relations;
    
  }
  
}

?>