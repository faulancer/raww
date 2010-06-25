<?php
  
  Config::write('App.connections',array(
    'default' => array(
      'datasource'=> 'Pdo',
      'dns'       => 'mysql:host=localhost;dbname=test',
      'user'      => 'root',
      'password'  => '',
      'options'   => array()
    )
  ));
?>