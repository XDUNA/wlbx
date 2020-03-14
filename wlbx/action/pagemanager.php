<?php
if($_POST["pagename"] == NULL)
{
	echo_404($_SERVER['PHP_SELF']);
}
else
{   
	//$pagename = base64_encode("search");
	$pagename = base64_encode($_POST["pagename"]);
	$sql = "select pagecontent,pagelocation from pagemgr where pagename = '$pagename' and enable = '1' order by id desc limit 1";
	if($mysqli->real_query($sql))
	{
		if($result = $mysqli->store_result())
		{
			if($row = $result->fetch_array())
			{
				if(base64_decode($row["pagecontent"]) == 'In the file')
				{
					$filename = base64_decode($row["pagelocation"]);
					//echo $filename;
					$handle = fopen($filename, "r");
					$array = array('error' => 0,'content' => base64_encode(fread($handle, filesize ($filename))));
					fclose($handle);
					echo json_encode($array);
				}
				else
				{
					$array = array('error' => 0,'content' => $row["pagecontent"]);
					echo json_encode($array);
				}
			}
			else
			{
				$array = array('error' => 1,'content' => base64_encode('loadpage(error_404);'));
				echo json_encode($array);
			}
		}
		else
		{
			$array = array('error' => 1,'content' => base64_encode('loadpage(error_500);'));
			echo json_encode($array);
		}
	}
	else
	{
		$array = array('error' => 1,'content' => base64_encode('loadpage(error_500);'));
		echo json_encode($array);
	}
}
?>