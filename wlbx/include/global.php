<?php
	session_start();
	error_reporting(0);
	$http_ref=isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
	//set_time_limit(3);
	date_default_timezone_set('PRC');
	require_once("config.php");
	require_once("mysql.php");
	require_once("wechat.php");
	function echo_404($url)
	{
Header("HTTP/1.1 404 Not Found");?><!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>The requested URL <?php echo $url;?> was not found on this server.</p>
</body></html><?php }
?>