<?php

  
class MYSQLAdapter extends DbAdapter{
    
    public function limit($limit,$offset){
      if ($limit) {
        $rt = '';
        if (!strpos(strtolower($limit), 'limit') || strpos(strtolower($limit), 'limit') === 0) {
          $rt = ' LIMIT';
        }

        if ($offset) {
          $rt .= ' ' . $offset . ',';
        }

        $rt .= ' ' . $limit;
        return $rt;
      }
      return null;
    }
    
    public function getTables() {
      $results = $this->pdoDatasource->query('SHOW TABLES');
      
      $tables = array();
      
      foreach($results as $result) $tables[] = $result[0];
      
      sort($tables);
      
      return $tables;
    }
    
    public function getColumns($table) {
      try{
        $results = $this->pdoDatasource->query('SHOW COLUMNS FROM ' . $table . ';');
      } catch(Exception $e) {
        return array();
      }
      
      $columns = array();

        $field   = 0;
        $type    = 1;
        $null    = 2;
        $key     = 3;
        $default = 4;
        $extra   = 5;

        $i = 1;
        $p = 1;

      foreach($results as $row) {
        
        $globalType = 'string';
        $coltype = null;
        $length  = null;
        $unsigned  = false;
        $precision = null;
        $scale = null;
        $primary = false;
        
            if (preg_match('/unsigned/', $row[$type])) {
                $unsigned = true;
            }
            if (preg_match('/^((?:var)?char)\((\d+)\)/', $row[$type], $matches)) {
                $coltype = $matches[1];
                $length = $matches[2];
                $globalType = 'string';
            } else if (preg_match('/^decimal\((\d+),(\d+)\)/', $row[$type], $matches)) {
                $coltype = 'decimal';
                $precision = $matches[1];
                $scale = $matches[2];
                
                $globalType = 'float';
                
            } else if (preg_match('/^float\((\d+),(\d+)\)/', $row[$type], $matches)) {
                $coltype = 'float';
                $precision = $matches[1];
                $scale = $matches[2];
                
                $globalType = 'float';
                
            } else if (preg_match('/^((?:big|medium|small|tiny)?int)\((\d+)\)/', $row[$type], $matches)) {
                $coltype = $matches[1];
                $globalType = 'integer';
                // The optional argument of a MySQL int type is not precision
                // or length; it is only a hint for display width.
            }
            
            if (strtoupper($row[$key]) == 'PRI') {
                $primary = true;
                $primaryPosition = $p;
                if ($row[$extra] == 'auto_increment') {
                    $identity = true;
                } else {
                    $identity = false;
                }
                ++$p;
            }
        
        $columns[$row[0]] = array(
          'name' => $row[0],
          'TYPE' => $globalType,
          'primary' => $primary,
          'type' => $coltype,
          'length' => $length,
          'precision' => $precision,
          'scale' => $scale,
        );
        
      }
      
      
      
      //sort($columns);
      
      return $columns;
    }
}