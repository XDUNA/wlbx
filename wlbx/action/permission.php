<?php 
if($_POST["action"] == NULL && !isset($_SESSION["wechatid"]))
{
	echo_404($_SERVER['PHP_SELF']);
}
else if($_POST["action"] == "query")
{
    if(!isset($_SESSION["mgr_name"]))
    {
        $wechatid = $_SESSION["wechatid"];
        $group = base64_encode("mgr");
        $sql = "SELECT id,realname FROM usr WHERE wechatid = '$wechatid' AND groups = '$group' AND enable = '1'";
        $mysqli->real_query($sql);
        $result = $mysqli->store_result();
        if($result->num_rows == 1)
        {
            $row = $result->fetch_array();
            $_SESSION["mgr_name"] = $row["realname"];
            $_SESSION["mgr_id"] = $row["id"];
            echo json_encode(array("error" => 0, "permission" => 1));
        }
        else
        {
            echo json_encode(array("error" => 0, "permission" => 0));
        }
    }
    else
    {
        echo json_encode(array("error" => 0, "permission" => 1));
    }
}
?>