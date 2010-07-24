<?php


App::import(RAWW_CORE.'model'.DS.'datasources'.DS.'pdo'.DS.'adapters'.DS.'db_adapter.php');
App::import(RAWW_CORE.'model'.DS.'datasources'.DS.'pdo'.DS.'adapters'.DS.'mysql.php');
App::import(RAWW_CORE.'model'.DS.'datasources'.DS.'pdo'.DS.'adapters'.DS.'sqlite.php');


class PdoDataSource extends DataSource{
    
    protected $pdo;
    public $DbAdapter;
    
    /**
    * ...
    *
    */ 
    public function __construct($config){
      extract($config);
      
      try {
        $this->pdo = new PDO($dns,$user,$password,$options);
      }catch( PDOException $Exception ) {
         Debug::trigger_error('PDO Connect failed: '.$Exception->getMessage(),E_USER_ERROR);
      }
      
      $adapterClass     = strtoupper($this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME)).'Adapter';
      
      $this->DbAdapter    = new $adapterClass($this);
    }
    
    /**
    * ...
    *
    */ 
    public function getTables() {
      
      return $this->DbAdapter->getTables();
    }
    
    /**
    * ...
    *
    */ 
    public function getColumns($table) {
      return $this->DbAdapter->getColumns($table);
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
      
      $fields = array();
      $values = array();
      
      foreach($data as $col=>$value){
        $fields[] = $col;
        $values[] = $this->pdo->quote($value);
      }
      $fields = implode(',', $fields);
      $values = implode(',', $values);
      
      $sql = "INSERT INTO {$table} ({$fields}) VALUES ({$values})";
      
      $this->log['queries'][] = $sql;
      
      $res = $this->pdo->exec($sql);
      
      if($res){
        return $this->pdo->lastInsertId();
      }else{
        trigger_error('SQL Error: '.implode(', ',$this->pdo->errorInfo()).":\n".$sql);
        return false;
      }
    }
    
    /**
    * ...
    *
    * @return ?
    */
    public function delete($table,$conditions){    
      
      
      $conditions = $this->buildConditions($conditions);
      
      if(strlen(trim($conditions))>0) $conditions = "WHERE ".$conditions;
      
      $sql = "DELETE FROM {$table} {$conditions}";
      
      $this->log['queries'][] = $sql;
      
      $res = $this->pdo->exec($sql);
      
      if($res || $res===0){
        return true;
      }else{
        trigger_error('SQL Error: '.implode(', ',$this->pdo->errorInfo()).":\n".$sql);
        return false;
      }
    }
    
    /**
    * ...
    *
    * @return ?
    */
    public function update($table,$data,$conditions=array()){    

      $conditions = $this->buildConditions($conditions);
      
      if(strlen(trim($conditions))>0) $conditions = "WHERE ".$conditions;
      
      $fields = array();
      
      foreach($data as $col=>$value){
        $fields[] = $col.'='.$this->pdo->quote($value);
      }
      
      $fields = implode(',', $fields);
      
      $sql = "UPDATE ".$table." SET {$fields} {$conditions}";
      
      $this->log['queries'][] = $sql;
      
      if($this->pdo->exec($sql)){
      
      }else{
        $errorInfo = $this->pdo->errorInfo();
        if($errorInfo[0]!='00000'){
            trigger_error('SQL Error: '.implode(', ',$errorInfo).":\n".$sql);
            return false;
        }
      }
      
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
      return $this->pdo->exec($data);
    }
    
    /**
    * ...
    *
    * @return ?
    */
    public function find($options){  
                
        $options = Utils::am(array(
          'conditions' => array(), //array of conditions
          'having'     => array(), //array of conditions
          'table'      => array(),
          'joins'      => array(),
          'fields'     => array('*'), //array of field names
          'order'      => array(), //string or array defining order
          'group'      => array(), //fields to GROUP BY
          'limit'      => null, //int
          'page'       => null, //int
          'offset'     => null
        ),$options);
        
        extract($options);

        if(is_array($fields)) $fields = implode(', ', $fields);
        if(is_array($table))  $table  = implode(', ', $table);
        if(is_array($group))  $group  = implode(', ', $group);
        if(is_array($order))  $order  = implode(', ', $order);

        $conditions = $this->buildConditions($conditions);
        $having     = $this->buildConditions($having);
        $limit      = $this->DbAdapter->limit($limit,$offset);

        //build joins
        
        $_joins     = array();
        
        if(is_string($joins)){
          $_joins = array($joins);
        }else{
          if(count($joins)){
             foreach($joins as $j){
               if(is_string($j)){
                 $_joins = $j;
               }else{
                $_joins[] = strtoupper($j['type']).' JOIN '.$j['table'].' '.$j['alias'].' ON('.implode(' AND ', $j['conditions']).')';
               }
             }
          }
        }
        
        $joins = implode(' ', $_joins);
        
       if(strlen(trim($conditions))>0) $conditions = "WHERE ".$conditions;
       if(strlen(trim($group))>0) $group = "GROUP BY ".$group;
       if(strlen(trim($having))>0) $having = "HAVING ".$conditions;
       if(strlen(trim($fields))==0) $fields = "*";
       if(strlen(trim($order))>0) $order = "ORDER BY ".$order;
        
       $sql = "SELECT {$fields} FROM {$table} {$joins} {$conditions} {$group} {$having} {$order} {$limit}";
       
       $this->log['queries'][] = $sql;
       
       return $this->fetchAll($sql);

    }
    
    
    /**
    * ...
    *
    * @return ?
    */
    public function fetchAll($sql){
      
      $ret_result = array();
      
      if($stmt = $this->pdo->query($sql)){
      
        $meta = array();

        foreach(range(0, $stmt->columnCount() - 1) as $column_index){
          $meta[] = $stmt->getColumnMeta($column_index);
        }
        
        $rows = $stmt->fetchAll(PDO::FETCH_NUM);

        foreach($rows as &$r){  
          $rec = array();
          for($i=0,$max=count($r);$i<$max;$i++){            
            
            $tabeleName = (strlen($meta[$i]['table'])!=0) ? $meta[$i]['table']:0;
            
            $rec[$tabeleName][$meta[$i]['name']] = $r[$i]; 
          }
          $ret_result[] = $rec;
        }
      }else{
         trigger_error('SQL Error: '.implode(', ',$this->pdo->errorInfo()).":\n".$sql,E_USER_ERROR);
      }

      return $ret_result;
  }

  protected function buildConditions($conditions){
        
        if(is_string($conditions)) $conditions = array($conditions);
        
        $_conditions = array();
        
        if(count($conditions)){
          
          $_bindParams = array();

          foreach($conditions as $c){
            
            $sql = '';
            
            if(is_array($c)){
              
              $sql = $c[0];
              
              foreach($c[1] as $key=>$value){
                $sql = str_replace(':'.$key,$this->pdo->quote($value),$sql);
              }
            }else{
              $sql= $c;
            }

            if(count($_conditions) > 0  && strtoupper(substr($sql,0,4))!='AND ' && strtoupper(substr($sql,0,3))!='OR '){
              $sql = 'AND '.$sql;
            }
            
            $_conditions[] = $sql;
            
          }
          
        }
        
       $conditions = implode(' ', $_conditions);
       
       return $conditions;
  }
  
    /**
    * Initiates a transaction
    *
    * @return bool
    */
    public function beginTransaction() {
    return $this->pdo->beginTransaction();
    }
            
    /**
    * Commits a transaction
    *
    * @return bool
    */
    public function commit() {
    return $this->pdo->commit();
    }
 
    /**
    * Fetch the SQLSTATE associated with the last operation on the database handle
    *
    * @return string
    */
    public function errorCode() {
     return $this->pdo->errorCode();
    }
    
    /**
    * Fetch extended error information associated with the last operation on the database handle
    *
    * @return array
    */
    public function errorInfo() {
     return $this->pdo->errorInfo();
    }
    
    /**
    * Execute an SQL statement and return the number of affected rows
    *
    * @param string $statement
    */
    public function exec($statement) {
     return $this->pdo->exec($statement);
    }
    
    /**
    * Retrieve a database connection attribute
    *
    * @param int $attribute
    * @return mixed
    */
    public function getAttribute($attribute) {
     return $this->pdo->getAttribute($attribute);
    }
 
    /**
    * Return an array of available PDO drivers
    *
    * @return array
    */
    public function getAvailableDrivers(){
     return $this->pdo->getAvailableDrivers();
    }
    
    /**
    * Returns the ID of the last inserted row or sequence value
    *
    * @param string $name Name of the sequence object from which the ID should be returned.
    * @return string
    */
    public function lastInsertId($name=null) {
      return $this->pdo->lastInsertId($name);
    }
        
    /**
    * Prepares a statement for execution and returns a statement object
    *
    * @param string $statement A valid SQL statement for the target database server
    * @param array $driver_options Array of one or more key=>value pairs to set attribute values for the PDOStatement obj
    returned
    * @return PDOStatement
    */
    public function prepare ($statement, $driver_options=false) {
     if(!$driver_options) $driver_options=array();
     return $this->pdo->prepare($statement, $driver_options);
    }
    
    /**
    * Executes an SQL statement, returning a result set as a PDOStatement object
    *
    * @param string $statement
    * @return PDOStatement
    */
    public function query($statement) {
     return $this->pdo->query($statement);
    }
    
    /**
    * Execute query and return all rows in assoc array
    *
    * @param string $statement
    * @return array
    */
    public function queryFetchAllAssoc($statement) {
     return $this->pdo->query($statement)->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
    * Execute query and return one row in assoc array
    *
    * @param string $statement
    * @return array
    */
    public function queryFetchRowAssoc($statement) {
     return $this->pdo->query($statement)->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
    * Execute query and select one column only
    *
    * @param string $statement
    * @return mixed
    */
    public function queryFetchColAssoc($statement) {
     return $this->pdo->query($statement)->fetchColumn();
    }
    
    /**
    * Quotes a string for use in a query
    *
    * @param string $input
    * @param int $parameter_type
    * @return string
    */
    public function quote ($input, $parameter_type=0) {
     return $this->pdo->quote($input, $parameter_type);
    }
    
    /**
    * Rolls back a transaction
    *
    * @return bool
    */
    public function rollBack() {
     return $this->pdo->rollBack();
    }
    
    /**
    * Set an attribute
    *
    * @param int $attribute
    * @param mixed $value
    * @return bool
    */
    public function setAttribute($attribute, $value ) {
     return $this->pdo->setAttribute($attribute, $value);
    }
  
}
?>