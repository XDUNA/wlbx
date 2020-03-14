<?php
if($_POST["submit"] == 'SET')
{
	$_SESSION["testcookie"] = "manual";
}
else if($_POST["submit"] == 'TEST')
{
	if(isset($_SESSION["testcookie"]) && $_SESSION["testcookie"] == "manual")
	{
		$json = array("error" => 0);
		echo json_encode($json);
	}
	else
	{
		$json = array("error" => 1);
		echo json_encode($json);
	}
}
else
{
	echo_404($_SERVER['PHP_SELF']);
}
?>