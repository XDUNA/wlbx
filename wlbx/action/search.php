<?php
if($_POST["search"] == NULL)
{
	echo_404($_SERVER['PHP_SELF']);
}
else if($_POST["submit"] == "true")
{
    if(!isset($_SESSION["wechatid"]))
    {
        $_SESSION["query"] = "";
	
        $search = $_POST["search"];
        if(preg_match('/^[A-Z0-9]{4}$/i', $search))
        {
            $sql = "select id from queue where repairid = '$search' order by id desc limit 1";
            $mysqli->real_query($sql);
            $result = $mysqli->store_result();
            if($result->num_rows > 0)
            {
                $_SESSION["query"] = "success";
                $_SESSION["repairid"] = $search;
                $array = array('error' => 0,'content' => base64_encode('ID query success!'));
                echo json_encode($array);
            }
            else
            {
                $array = array('error' => 1,'content' => base64_encode('ID donot exist!'));
                echo json_encode($array);
            }
        }
        else
        {
            $array = array('error' => 2,'content' => base64_encode('ID cannot match!'));
            echo json_encode($array);
        }
    }
    else
    {
        $_SESSION["query"] = "";
	
        $search = $_SESSION["wechatid"];
        $sql = "select id,repairid from queue where wechatid = '$search' order by id desc limit 1";
        $mysqli->real_query($sql);
        $result = $mysqli->store_result();
        if($result->num_rows > 0)
        {
            $_SESSION["query"] = "success";
            $row = $result->fetch_array();
            $_SESSION["repairid"] = $row["repairid"];
            $array = array('error' => 0,'content' => base64_encode('ID query success!'));
            echo json_encode($array);
        }
        else
        {
            $array = array('error' => 1,'content' => base64_encode('ID donot exist!'));
            echo json_encode($array);
        }
    }
}
else
{
	echo_404($_SERVER['PHP_SELF']);
}
?>