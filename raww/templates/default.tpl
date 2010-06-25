<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo isset($pageTitle) ? $pageTitle:''; ?></title>
    <style type="text/css">
      * {
        padding: 0px;
        margin:0px;
        -moz-box-sizing: border-box;
        -ms-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
      }
      
      body{
        font: normal 18px Helvetica, Arial, sans-serif;
        font-style: normal;
        font-weight: normal;
        text-transform: normal;
        letter-spacing: normal;
        line-height: 1.4em;
      }
      
      h1{
        font-size: 44px;
        font-weight: bold;
        letter-spacing: -2px;
        line-height: 1.2em;
      }
      
      .wrapper{
        width: 850px;
        margin: 0px auto;
      }
      
      #header{
        height: 80px;
      }
      
     #logo{
        font-size: 20px;
        font-weight: bold;
        letter-spacing: -1px;
        line-height: 1.5em;
        background-color: #7F0000;
        color: #fff;
        display: inline-block;
        padding: 5px 15px 5px 15px;
      }
      #logo span{

        font-family: Georgia, serif;
        font-style: italic;
        font-weight: normal;
        text-transform: normal;
        letter-spacing: normal;
        line-height: 1.2em;
      }
      #content{
        padding-bottom: 25px;
      }
      #footer{
        font-size: 15px;
        line-height: 1.45em;
        
      }
      #footer .wrapper{
        background-color: #f7f7f7;
        color: #999;
        padding: 15px;
      }
      
      .code{
        margin: 15px 0px;
        padding: 20px;
        background-color: #FFFFCC;
      }
    </style>
	</head>
	<body>
    <div id="header">
      <div class="wrapper">
          <span id="logo">Raww <span>framework</span></span>
      </div>
    </div>
    <div id="content">
      <div class="wrapper">
        <?php 
          if(isset($message)) {
            
            ob_start();
              include(RAWW_CORE.'templates'.DS.'messages'.DS.$message.'.tpl');
            $msg = ob_get_clean();
            
            echo $msg;
            
          }else{
          
          }
        ?>
      </div>
    </div>
    <div id="footer">
      <div class="wrapper">
        <strong>&copy; raww</strong> - webframework &mdash; because we &hearts; it simple, <i> sponsered by</i> d-xp.com
      </div>
    </div>
  </body>
</html>