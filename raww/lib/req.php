<?php

class Req {
 
  public static $mimeTypes = array(
    'asc'   => 'text/plain',
    'au'    => 'audio/basic',
    'avi'   => 'video/x-msvideo',
    'bin'   => 'application/octet-stream',
    'class' => 'application/octet-stream',
    'css'   => 'text/css',
    'csv'	  => 'application/vnd.ms-excel',
    'doc'   => 'application/msword',
    'dll'   => 'application/octet-stream',
    'dvi'   => 'application/x-dvi',
    'exe'   => 'application/octet-stream',
    'htm'   => 'text/html',
    'html'  => 'text/html',
    'json'  => 'application/json',
    'js'    => 'application/x-javascript',
    'txt'   => 'text/plain',
    'bmp'   => 'image/bmp',
    'rss'		=> 'application/rss+xml',
	'atom'  => 'application/atom+xml',
    'gif'   => 'image/gif',
    'jpeg'  => 'image/jpeg',
    'jpg'   => 'image/jpeg',
    'jpe'   => 'image/jpeg',
    'png'   => 'image/png',
    'ico'   => 'image/vnd.microsoft.icon',
    'mpeg'  => 'video/mpeg',
    'mpg'   => 'video/mpeg',
    'mpe'   => 'video/mpeg',
    'qt'    => 'video/quicktime',
    'mov'   => 'video/quicktime',
    'wmv'   => 'video/x-ms-wmv',
    'mp2'   => 'audio/mpeg',
    'mp3'   => 'audio/mpeg',
    'rm'    => 'audio/x-pn-realaudio',
    'ram'   => 'audio/x-pn-realaudio',
    'rpm'   => 'audio/x-pn-realaudio-plugin',
    'ra'    => 'audio/x-realaudio',
    'wav'   => 'audio/x-wav',
    'zip'   => 'application/zip',
    'pdf'   => 'application/pdf',
    'xls'   => 'application/vnd.ms-excel',
    'ppt'   => 'application/vnd.ms-powerpoint',
    'wbxml' => 'application/vnd.wap.wbxml',
    'wmlc'  => 'application/vnd.wap.wmlc',
    'wmlsc' => 'application/vnd.wap.wmlscriptc',
    'spl'   => 'application/x-futuresplash',
    'gtar'  => 'application/x-gtar',
    'gzip'  => 'application/x-gzip',
    'swf'   => 'application/x-shockwave-flash',
    'tar'   => 'application/x-tar',
    'xhtml' => 'application/xhtml+xml',
    'snd'   => 'audio/basic',
    'midi'  => 'audio/midi',
    'mid'   => 'audio/midi',
    'm3u'   => 'audio/x-mpegurl',
    'tiff'  => 'image/tiff',
    'tif'   => 'image/tiff',
    'rtf'   => 'text/rtf',
    'wml'   => 'text/vnd.wap.wml',
    'wmls'  => 'text/vnd.wap.wmlscript',
    'xsl'   => 'text/xml',
    'xml'   => 'text/xml'
  );
  
  public static $mobileDevices = array(
      "midp","240x320","blackberry","netfront","nokia","panasonic","portalmmm","sharp","sie-","sonyericsson",
      "symbian","windows ce","benq","mda","mot-","opera mini","philips","pocket pc","sagem","samsung",
      "sda","sgh-","vodafone","xda","iphone","android"
  );
 
  /**
  * ...
  *
  */ 
  public static function is($type){
    
    switch(strtolower($type)){
      case 'ajax':
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
        break;
      
      case 'mobile':
        return preg_match('/(' . implode('|',self::$mobileDevices). ')/i',strtolower($_SERVER['HTTP_USER_AGENT']));
        break;
      
      case 'post':
        return (strtolower($_SERVER['REQUEST_METHOD']) == 'post');
        break;
      
      case 'get':
        return (strtolower($_SERVER['REQUEST_METHOD']) == 'get');
        break;
        
      case 'put':
        return (strtolower($_SERVER['REQUEST_METHOD']) == 'put');
        break;
        
      case 'delete':
        return (strtolower($_SERVER['REQUEST_METHOD']) == 'delete');
        break;
        
      case 'ssl':
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
        break;
    }
    
    
  }
  
  /**
  * ...
  *
  */ 
  public static function getClientIp(){
  
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
      // Use the forwarded IP address, typically set when the
      // client is using a proxy server.
      return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])){
      // Use the forwarded IP address, typically set when the
      // client is using a proxy server.
      return $_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (isset($_SERVER['REMOTE_ADDR'])){
      // The remote IP address
      return $_SERVER['REMOTE_ADDR'];
    }
        
  }

  /**
  * ...
  *
  */ 
  function getMimeType($val) {
     
     $extension = strtolower(array_pop(explode('.',$val)));

     return (isset(self::$mimeTypes[$extension]) ? self::$mimeTypes[$extension]:'text/html');
  }
  
  /**
  * ...
  *
  */ 
  function redirect($url) {
      
      if (strpos($name,'://') === false) {
          if(substr($url,0,1)!='/'){
            $url = '/'.$url;
          }
          $url = Router::getBaseUrl().$url;
      }
      
      header('Location: '.$url);
      App::shutdown();
  }

}