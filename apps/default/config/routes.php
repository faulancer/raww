<?php

  Router::connect('/',array(
    'controller' => 'pages',
    'action'     => 'display',
    'params'     => array('welcome')
  ));
  
  Router::connect('#/(.*)\.html#',array(
    'controller' => 'pages',
    'action'     => 'display',
    'params'     => array('::1')
  ));
?>