<?php

  
  class SQLITEAdapter extends DbAdapter{
  
    public function limit($limit,$offset){
      if ($limit) {
        $rt = '';
        if (!strpos(strtolower($limit), 'limit') || strpos(strtolower($limit), 'limit') === 0) {
          $rt = ' LIMIT';
        }
        $rt .= ' ' . $limit;
        if ($offset) {
          $rt .= ' OFFSET ' . $offset;
        }
        return $rt;
      }
      return null;
    }
    
    public function getTables() {
      $results = $this->pdoDatasource->query('SELECT tbl_name FROM sqlite_master WHERE type="table"');
      $tables = array();
      
      foreach($results as $result) $tables[] = $result[0];
      
      sort($tables);
      
      return $tables;
    }
    
    public function getColumns($table) {
        $results = $this->pdoDatasource->query('PRAGMA table_info("' . $table . '");');
      
        $columns = array();
      
        foreach($results as $result) {
          $columns[] = $result['name'];
        }
      
        sort($columns);
      
        return $columns;
    }
  
  }
  
?>