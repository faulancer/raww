<?php

Config::load('assets');

class Assets {

  /**
  * ...
  *
  */ 
  public static function output($type,$groups){
    
    $type   = strtolower($type);
    $output = '';
    
    $groups = str_replace(array(".css",".js"),"",$groups);
    
    //Debug::show(Req::getMimeType($type));
    
    
    $cache_file = RAWW_APP_TMP.'assets/'.implode('_',$groups).'.'.$type;
      
    if(file_exists($cache_file) && Config::read('App.Debug.level')==0){
        $output = file_get_contents($cache_file);
    }else{
        
        $output = array();
        
        foreach($groups as $g){
          
          $files = Config::read('App.Assets.'.$g.'.'.$type);
          
          if(!$files) continue;

          foreach($files as $f){
            
            if(!Utils::isFile($f)){
              $f = RAWW_WEBROOT.$type.DS.$f.'.'.$type;
            }
            
            if(file_exists($f)){
              
              $content = file_get_contents($f);
              
              if($type=="css"){

                $csspath = explode('/','css/'.str_replace(RAWW_WEBROOT.'css'.DS,'',$f));
                $csspath[count($csspath)-1] = "";
                $csspath = implode("/",$csspath);
                
                $req = explode('/assets/',$_SERVER['REQUEST_URI']);
                
                $csspath = $req[0].'/'.$csspath;
                
                preg_match_all('/url\((.*)\)/',$content,$matches);
                
                foreach($matches[1] as $imgpath){
                  if(!preg_match("/^http/",trim($imgpath))){
                    $content = str_replace('url('.$imgpath.')','url('.$csspath.str_replace('"','',$imgpath).')',$content);
                  }
                }
                
              }
              
              $output[] = $content;
            }
          }
        
        }
        
        $output = implode("\n",$output);
        
        if(Config::read('App.Debug.level')==0) {
          file_put_contents($cache_file,$output);
        }
    }
     
    if(!ob_start("ob_gzhandler")) ob_start();
    
    header("Content-type: ".Req::getMimeType($type));
    header('Expires: '.gmdate('D, d M Y H:i:s', time() + 259200).' GMT'); // 3 days  
    
    echo $output;

    //ob_end_flush();
    App::shutdown();
  }
}

?>