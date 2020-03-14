<?php
function generate_repairid(){
	global $mysqli;
	for($i=0;$i<10;$i++)
	{
		$map = array('A','B','C','D','E','F','G','H','I','J',
					 'K','L','M','N','O','P','Q','R','S','T',
					 'U','V','W','X','Y','Z','1','2','3','4',
					 '5','6','7','8','9','0');
		$first = mt_rand(0,35);
		$str = '';
		$str .= $map[$first];
		$second = mt_rand(0,35);
		$str .= $map[$second];
		$third = mt_rand(0,35);
		$str .= $map[$third];
		$fourth = ($first * 10 * ($second + 22) + $third * 8) % 36;
		$str .= $map[$fourth];
		$sql = "select id from queue where repairid='$str'";
		$mysqli->real_query($sql);
		$result = $mysqli->store_result();
		if($result->num_rows == 0)
		{
			return $str;
		}
	}
	return false;
}

if($_POST["datapost"] == NULL)
{
	echo_404($_SERVER['PHP_SELF']);
}
else if($_POST["datapost"] == "submit")
{
	$buildnum = base64_encode($_POST["bulnum"]);
	$descript = base64_encode($_POST["detail"]);
	$floor = base64_encode($_POST["floor"]);
	$name = AES_encode($_POST["name"]);
	$name_hash = hash("SHA256",$_POST["name"]);
	$ftime = base64_encode(json_encode($_POST["time"]));
	$roomnum = base64_encode($_POST["roomnum"]);
	$roomside = base64_encode($_POST["roomside"]);
	$section = base64_encode($_POST["section"]);
	$stuid = AES_encode($_POST["stuid"]);
	$stuid_hash = hash("SHA256",$_POST["stuid"]);
	$tel = AES_encode($_POST["tel"]);
	$tel_hash = hash("SHA256",$_POST["tel"]);
	//$wechatid = base64_encode(mt_rand(100000,10000000));
    $key = hash("SHA256", $_POST["key"]);
    if(isset($_SESSION["wechatid"]))
    {
        $wechatid = $_SESSION["wechatid"];
    }
    else
    {
        $sql = "select wechatid from wechatbind where `key` = '$key'";
        $mysqli->real_query($sql);
        $result = $mysqli->store_result();
        $row = $result->fetch_array();
        $wechatid = $row["wechatid"];
    }
    
    if( empty($wechatid) )
    {
        $array = array('error' => 3,'content' => base64_encode('Wechat id disable!'));
        echo json_encode($array);
        die();
    }
	$sql = "select id from usr where stuidhash = '$stuid_hash' or wechatid = '$wechatid'";
	$mysqli->real_query($sql);
	$result = $mysqli->store_result();
	if($result->num_rows == 1){
		$row = $result->fetch_array();
		$userid = $row["id"];
		$sql = "select repairid from queue where usrid = '$userid' and qstatus = '1'";
		$mysqli->real_query($sql);
		$result = $mysqli->store_result();
		if($result->num_rows > 0)
		{
			$array = array('error' => 1,'content' => base64_encode('Already in queue!'));
			echo json_encode($array);
		}
		else if($result->num_rows == 0)
		{
			if(($repairid = generate_repairid()) != false)
			{
				$sql = "insert into queue (repairid,wechatid,usrid,freetime,buildnum,floor,section,roomnum,roomside,nowstage,description,qstatus) value ('$repairid','$wechatid','$userid','$ftime','$buildnum','$floor','$section','$roomnum','$roomside','0','$descript','1')";
				//echo $sql;
				$mysqli->real_query($sql);
				$processer = base64_encode('System');
				$sql = "insert into inflist (repairid,status,updatetime,processer) value ('$repairid','0',now(),'$processer')";
				$mysqli->real_query($sql);
				
				$_SESSION["query"] = "success";
				$_SESSION["repairid"] = $repairid;
				send_msg($repairid , "已提交", "你已通过报修平台提交报修信息！感谢你的使用！\n你可以随时通过点击本链接来检查最新进度！", 1);
				$array = array('error' => 0,'content' => base64_encode('Success!'),'script' => base64_encode('loadpage(search_result);'));
				echo json_encode($array);
			}
			else
			{
				$array = array('error' => 2,'content' => base64_encode('Repairid Fail!'));
				echo json_encode($array);
			}
		}
	}
	else if($result->num_rows == 0)
	{
		$group_default = base64_encode('user');
		$remark = base64_encode('Auto Creat User!');
		$sql = "insert into usr (realname,namehash,stuid,stuidhash,tel,telhash,wechatid,groups,enable,remark) value ('$name','$name_hash','$stuid','$stuid_hash','$tel','$tel_hash','$wechatid','$group_default','1','$remark')";
		$mysqli->real_query($sql);
		if(($repairid = generate_repairid()) != false)
		{
			$sql = "select id from usr where stuidhash = '$stuid_hash'";
			$mysqli->real_query($sql);
			$result = $mysqli->store_result();
			$row = $result->fetch_array();
			$userid = $row["id"];
			$sql = "insert into queue (repairid,wechatid,usrid,freetime,buildnum,floor,section,roomnum,roomside,nowstage,description,qstatus) value ('$repairid','$wechatid','$userid','$ftime','$buildnum','$floor','$section','$roomnum','$roomside','0','$descript','1')";
			//echo $sql;
			$mysqli->real_query($sql);
			$processer = base64_encode('System');
			$sql = "insert into inflist (repairid,status,updatetime,processer) value ('$repairid','0',now(),'$processer')";
			$mysqli->real_query($sql);
			
			$_SESSION["query"] = "success";
			$_SESSION["repairid"] = $repairid;
			send_msg($repairid , "已提交", "你已通过报修平台提交报修信息！感谢你的使用！\n你可以随时通过点击本链接来检查最新进度！", 1);
			$array = array('error' => 0,'content' => base64_encode('Success!'),'script' => base64_encode('loadpage(search_result);'));
			echo json_encode($array);
		}
		else
		{
			$array = array('error' => 2,'content' => base64_encode('Repairid Fail!'));
			echo json_encode($array);
		}
	}
    else
    {
        $array = array('error' => 1,'content' => base64_encode('Data Fake!'));
        echo json_encode($array);
    }
}
else
{
	echo_404($_SERVER['PHP_SELF']);
}
?>