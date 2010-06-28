<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>...</title>

<?php if (Config::read('App.Debug.level')==0) { ?>
<meta http-equiv="Refresh" content="<?php echo $pause; ?>;url=<?php echo $this->Html->url($url); ?>"/>
<?php } ?>
<style type="text/css">
<!--
body{font: normal 14px Arial, Helvetica, sans-serif;background-color:#fff;}
p { text-align:center; font:bold 1.5em sans-serif }
a { color:#444; text-decoration:none }
a:HOVER { text-decoration: underline; color:#44E }
-->
</style>
</head>
<body>
<p><a href="<?php echo $this->Html->url($url); ?>"><?php echo $message; ?></a></p>
</body>
</html>