<?php
  
  //Debug
  //-----------------------------
  Config::write('App.Debug.level',1);
  
  //App defaults
  //-----------------------------
  Config::write('App.Charset','UTF-8');
  Config::write('App.GzipOutput',true);
  
  
  //Session configuration
  //-----------------------------
  Config::write('App.Session.autostart',true);
  Config::write('App.Session.name','RAWW');
  
  
?>