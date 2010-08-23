<?php
  
  class HtmlHelper{
    
  /**
  * ...
  *
  * @return ?
  */
    public static function specialchars($str, $double_encode = true) {
      // Force the string to be a string
      $str = (string) $str;

      // Do encode existing HTML entities (default)
      if ($double_encode === true) {
        $str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
      } else {
        // Do not encode existing HTML entities
        // From PHP 5.2.3 this functionality is built-in, otherwise use a regex
        if (version_compare(PHP_VERSION, '5.2.3', '>=')) {
          $str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8', false);
        } else {
          $str = preg_replace('/&(?!(?:#\d++|[a-z]++);)/ui', '&amp;', $str);
          $str = str_replace(array('<', '>', '\'', '"'), array('&lt;', '&gt;', '&#39;', '&quot;'), $str);
        }
      }

      return $str;
    }
    
  /**
  * ...
  *
  * @return ?
  */
    public function assets($type,$assetConfigName,$timestamp=false) {
      
      $output = '';
      
      if(!is_array($assetConfigName)) $assetConfigName = array($assetConfigName);
      
      $url = Router::getBaseUrl().'/assets/'.$type.'/'.implode("/",$assetConfigName).'.'.$type.(($timestamp) ? '?'.$timestamp:'');
      
      switch($type){
        case 'js':
          $output .= '<script type="text/javascript" src="'.$url.'"></script>';
          break;
        case 'css':
          $output .= '<link rel="stylesheet" href="'.$url.'" type="text/css" media="all" />';
          break;
      }
      
      return $output."\n";
    }
    
  /**
  * ...
  *
  * @return ?
  */
    public function css($links,$folder='css') {
      $output = '';
      
      if(!is_array($links)) $links = array($links);

      foreach ($links as $name) {
        $url = $name;
        
        if (strpos($name,'://') === false) {
          $name = (strtolower(substr($name, -4)) !== '.css') ? $name.'.css' : $name;
          $url = Router::getBaseUrl().'/'.$folder.'/'.$name;
        }
        $output .= '<link rel="stylesheet" href="'.$url.'" type="text/css" media="all" />';
      }

      return $output."\n";
    }

  /**
  * ...
  *
  * @return ?
  */
    public function js($scripts,$folder='js') {
      $output = '';
      
      if(!is_array($scripts)) $scripts = array($scripts);

      foreach ($scripts as $name) {
        $url = $name;
        
        if (strpos($name,'://') === false) {
          $name = (strtolower(substr($name, -3)) !== '.js') ? $name.'.js' : $name;
          $url = Router::getBaseUrl().'/'.$folder.'/'.$name;
        }
        $output .= '<script type="text/javascript" src="'.$url.'"></script>';
      }

      return $output."\n";
    }
    
  /**
  * ...
  *
  * @return ?
  */
    public function img($file,$attributes=array()){
      
      if (strpos($file,'://') === false) {
        $file = Router::getBaseUrl().'/img/'.$file;
      }
      
      $output = '<img src="'.$file.'" '.$this->attributes($attributes).' />';
      
      return $output;
    }
    
    public function video($file,$options=array()){
      
      $options = Utils::am(array(
        
        
      
      ),$options);

      
      $output = '...';
      
      return $output;
    }
    
  /**
  * ...
  *
  * @return ?
  */
    public function url($url){
      
      if(strpos($url,'://') === false) {
          if(substr($url,0,1)!='/'){
            $url = '/'.$url;
          }
          $url = Router::getBaseUrl().$url;
      }
            
      return $url;
    }
    
  /**
  * ...
  *
  * @return ?
  */
    public function link($title,$url,$attributes=array()){
      
      if(strpos($url,'://') === false) {
          if(substr($url,0,1)!='/'){
            $url = '/'.$url;
          }
          $url = Router::getBaseUrl().$url;
      }
      
      $output = '<a href="'.$url.'" '.$this->attributes($attributes).'>'.$title.'</a>';
      
      return $output;
    }
    
  /**
  * ...
  *
  * @return ?
  */
    public function attributes($attributes){
      if (empty($attributes))
        return '';

      if (is_string($attributes))
        return ' '.$attributes;

      $compiled = '';
      foreach ($attributes as $key => $val) {
        $compiled .= ' '.$key.'="'.$val.'"';
      }

      return $compiled;
    }
    
  }