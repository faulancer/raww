<?php

class MongoDbDataSource extends DataSource{
    
    protected $conn;
    public $db;
    
    /**
    * ...
    *
    */ 
    public function __construct($config){
    
      extract($config);
      
      try {
        
        $this->conn = new Mongo($dns,$options);
        $this->db   = $this->conn->{$db};
        
      }catch( MongoException $Exception ) {
         Debug::trigger_error('MongoDB Connect failed: '.$Exception->getMessage(),E_USER_ERROR);
      }
    }
    
    /**
    * ...
    *
    */ 
    public function getTables() {
      
      $tables = array();
      
      foreach($this->db->listCollections() as $col){
        $tables[] = $col->getName();
      }
      
      return $tables;
    }
    
    /**
    * ...
    *
    */ 
    public function getColumns($table) {
      return array('_id'=>'string', 'created'=>'integer');
    }
    
    /**
    * ...
    *
    * @return ?
    */
    public function field($options){    
      return false;
    }
    
    /**
    * ...
    *
    * @return ?
    */
    public function create($table,$data){    
      
      if($this->db->{$table}->insert($data)){
        return $data['_id'];
      }else{
        return false;
      }
    }
    
    /**
    * ...
    *
    * @return ?
    */
    public function delete($table,$conditions){    
      if($this->db->{$table}->remove($conditions)){
        return true;
      }else{
        return false;
      }
    }
    
    /**
    * ...
    *
    * @return ?
    */
    public function update($table,$data,$conditions=array()){    
        if($this->db->{$table}->update($conditions, array('$set' => $data))){
            return true;
        }else{
            return false;
        }
    }
    
    /**
    * ...
    *
    * @return ?
    */
    public function truncate($table){    
      return $this->db->{$table}->drop();
    }
    
    /**
    * ...
    *
    * @return ?
    */
    public function execute($data){    
      
    }
    
    /**
    * ...
    *
    * @return ?
    */
    public function find($options){  
                
        $options = Utils::am(array(
          'conditions' => array(), //array of conditions
          'table'      => '',
          'joins'      => array(),
          'fields'     => array(), //array of field names
          'order'      => array(), //string or array defining order
          'group'      => array(), //fields to GROUP BY
          'limit'      => null, //int
          'page'       => null, //int
          'offset'     => null
        ),$options);
        
        extract($options);
        
        $cursor = $this->db->{$table}->find($conditions, $fields);
        return iterator_to_array($cursor);
    }
  
  
}