<h1>
  <span style="color:#ccc;">Oooops!</span><br />
  Missing Route for the Startpage
</h1>

<div class="code">
  <pre>
    Router::connect('<strong>/</strong>',array(
      'controller' => 'pages',
      'action'     => 'display',
      'params'     => array('welcome')
    ));
  </pre>
</div>

in <i><?php echo RAWW_APP_CONFIG.'routes.php';?></i>