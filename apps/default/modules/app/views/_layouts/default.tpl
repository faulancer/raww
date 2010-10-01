<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo isset($pageTitle) ? $pageTitle:''; ?></title>
    
        <script type="text/javascript">
          (function(){  
            WEBROOT = '<?php echo Router::getBaseUrl().'/';?>';
            BASE    = '<?php echo Router::getBaseUrl();?>';
          })();
        </script>
    
        <?php
            echo $this->H('Html')->css('style');
            
            // load assets defined in config/assets.php
            // echo $this->H('Html')->assets('css','main');
            // echo $this->H('Html')->assets('js','main');
        ?>
    
	</head>
	<body>
    <div id="header">
      <div class="wrapper">
        <span id="logo">Raww <span>framework</span></span>
      </div>
    </div>
    <div id="content">
      <div class="wrapper">
        <?php echo $content_for_layout; ?>
      </div>
    </div>
    <div id="footer">
      <div class="wrapper">
        <strong>&copy; raww</strong> - webframework &mdash; because we &hearts; it simple, <i> sponsered by</i> d-xp.com
      </div>
    </div>
  </body>
</html>