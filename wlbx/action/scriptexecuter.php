<?php
if($_POST["scriptname"] == NULL || $_POST["scriptfamily"] == NULL)
{
	echo_404($_SERVER['PHP_SELF']);
}else
{
	$scriptname = base64_encode($_POST["scriptname"]);
	$scriptfamily = base64_encode($_POST["scriptfamily"]);
	$sql = "select jscontent,jslocation from jsexe where pagename = '$scriptname' and jstype='$scriptfamily' and enable = '1' order by id desc limit 1";
	if($mysqli->real_query($sql))
	{
		if($result = $mysqli->store_result())
		{
			if($row = $result->fetch_array())
			{
				if(base64_decode($row["jscontent"]) == 'In the file')
				{
					$filename = base64_decode($row["jslocation"]);
					$handle = fopen($filename, "r");
					$array = array('error' => 0,'content' => base64_encode(fread($handle, filesize($filename))));
					fclose($handle);
					ob_clean();
					echo json_encode($array);
				}
				else
				{
					$array = array('error' => 0,'content' => $row["jscontent"]);
					ob_clean();
					echo json_encode($array);
				}
			}
			else
			{
				//
				$array = array('error' => 1,'content' => 'NULL');
				echo json_encode($array);
			}
		}
		else
		{
			//
			$array = array('error' => 1,'content' => 'NULL');
			echo json_encode($array);
		}
	}
	else
	{
		//
		$array = array('error' => 1,'content' => 'NULL');
		echo json_encode($array);
	}
}
?>