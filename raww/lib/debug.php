<?php

class Debug{

  /**
  * ...
  *
  */ 
  public static function show($var = false) {
		
    if (Config::read('App.Debug.level')==0){
      return;
    }
				  
    $_from  = debug_backtrace();
    $output = array();
    
    $output[] = '<div class="app-debug">';
      $output[] = '<strong title="'.$_from[0]['file'].'">' . str_replace(RAWW_APP,'',$_from[0]['file']) . '</strong>';
      $output[] = ' (line: <strong>' . $_from[0]['line'] . '</strong>)';
      $output[] = '<pre>';
      $output[] = print_r($var, true);
      $output[] = "</pre>";
    $output[] = '<div>';
    
    echo implode("\n",$output);
	}
  
  /**
  * ...
  *
  */ 
  public static function log($type, $message='') {
		
    $type = strtolower($type);
    
    if ($type!='error' && Config::read('App.Debug.level')==0){
      return;
    }
		
    $output = date('Y/m/d H:m:s').' : '.$message."\n";
    
    file_put_contents(RAWW_APP_LOGS.$type.'.log',$output,FILE_APPEND);
	}
  
  public static function trigger_error($msg,$errorType=E_USER_NOTICE){
    
    echo '<div class="app-msg-e-user-'.$errorType.'">';
      echo '<pre>';
      trigger_error($msg,$errorType);
      echo '</pre>';
    echo '</div>';
  }
}