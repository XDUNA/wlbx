<?php
if($_POST["query"] == "query")
{
	if($_SESSION["query"] == "success")
	{
		$repairid = $_SESSION["repairid"];
		$sql = "select status,updatetime,remark,exptime from inflist where repairid = '$repairid' order by id desc";
		$mysqli->real_query($sql);
		$result = $mysqli->store_result();
		$rarray = array("error" => 0,"datasize" => $result->num_rows,"ID" => $repairid);
		while($row = $result->fetch_array())
		{
			array_push($rarray, 
			           array('status' => $row["status"], 
							 'updatetime' => base64_encode($row["updatetime"]),
							 'remark' => $row["remark"],
							 'exptime' => $row["exptime"]
							));
		}
		echo json_encode($rarray);
	}
	else
	{
		echo_404($_SERVER['PHP_SELF']);
	}
}
else if($_POST["query"] == "reschedule")
{
	$repairid = $_SESSION["repairid"];
	if($_SESSION["query"] == "success")
	{
		$sql = "select status from inflist where repairid = '$repairid' order by id desc limit 1";
		$mysqli->real_query($sql);
		$result = $mysqli->store_result();
		$row = $result->fetch_array();
		if($row["status"] == 20 || $row["status"] == 31 || $row["status"] == 50){
            send_msg( $repairid, "维修延缓", "您刚刚通过报修平台提出了维修延缓请求！\n如果这不是您本人操作，请及时联系我们", 2);
			$sql = "insert into inflist (repairid,status,updatetime) value ('$repairid','30',now())";
			$mysqli->real_query($sql);
            $sql = "update queue set nowstage = '30' where repairid = '$repairid'";
			$mysqli->real_query($sql);
			$array = array('error' => 0,'content' => base64_encode('Reschedule success!'));
			echo json_encode($array);
		}
		else
		{
			$array = array('error' => 1,'content' => base64_encode('Reschedule fail!'));
			echo json_encode($array);
		}
	}
	else
	{
		echo_404($_SERVER['PHP_SELF']);
	}
}
else if($_POST["query"] == "cancel")
{
	$repairid = $_SESSION["repairid"];
	if($_SESSION["query"] == "success")
	{
		$sql = "select status from inflist where repairid = '$repairid' order by id desc limit 1";
		$mysqli->real_query($sql);
		$result = $mysqli->store_result();
		$row = $result->fetch_array();
		if($row["status"] == 0 || $row["status"] == 10){
            send_msg( $repairid, "撤回报修", "您刚刚通过报修平台撤回了报修请求！\n如果这不是您本人操作，请及时联系我们", 3);
			$sql = "insert into inflist (repairid,status,updatetime) value ('$repairid','12',now())";
			$mysqli->real_query($sql);
            $sql = "insert into inflist (repairid,status,updatetime) value ('$repairid','100',now())";
			$mysqli->real_query($sql);
            $processer_tmp = AES_encode("用户撤回");
            $sql = "update queue set nowstage = '100', qstatus = '0', processer='$processer_tmp' where repairid = '$repairid'";
			$mysqli->real_query($sql);
			$array = array('error' => 0,'content' => base64_encode('Cancel success!'));
			echo json_encode($array);
		}
		else
		{
			$array = array('error' => 1,'content' => base64_encode('Cancel fail!'));
			echo json_encode($array);
		}
	}
	else
	{
		echo_404($_SERVER['PHP_SELF']);
	}
}
else
{
	echo_404($_SERVER['PHP_SELF']);
}
					   
/*
status:
0:submitsuccess
10:infconfirm
11:inferror
12:usercancel
20:scheduled
30:rescheduling
31:rescheduled
40:repairsuccess
50:repairdely
55:repairfail
100:closed
*/
/*
status:
0:submitsuccess
1:infconfirm
2:inferror
3:scheduled
4:rescheduling
5:rescheduled
6:repairsuccess
7:repairdely
8:repairfail
100:closed
*/
?>