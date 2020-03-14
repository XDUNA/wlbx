<?php
if($_POST["action"] == 'query')
{
	if(!isset($_SESSION["wechatid"]))
	{
        if(isset($_SESSION["key"]))
        {
            //$key = hash("SHA256", $_POST["key"]);
            $key = hash("SHA256", $_SESSION["key"]);
            $sql = "SELECT wechatid FROM wechatbind WHERE `key` = '$key'";
            $mysqli->real_query($sql);
            $result = $mysqli->store_result();
            if($result->num_rows == 0)
            {
                echo json_encode(array("error" => 1));
            }
            else
            {
                $row = $result->fetch_array();
                if(empty($row["wechatid"]))
                {
                    echo json_encode(array("error" => 0, "bind" => 0));
                }
                else
                {
                    $_SESSION["wechatid"] = $row["wechatid"];
                    echo json_encode(array("error" => 0, "bind" => 1));
                }
            }
        }
        else if(isset($_POST["key"]))
        {
            $key = hash("SHA256", $_POST["key"]);
            //$key = hash("SHA256", $_SESSION["key"]);
            $sql = "SELECT wechatid FROM wechatbind WHERE `key` = '$key'";
            $mysqli->real_query($sql);
            $result = $mysqli->store_result();
            if($result->num_rows == 0)
            {
                echo json_encode(array("error" => 1));
            }
            else
            {
                $row = $result->fetch_array();
                if(empty($row["wechatid"]))
                {
                    echo json_encode(array("error" => 0, "bind" => 0));
                }
                else
                {
                    $_SESSION["wechatid"] = $row["wechatid"];
                    echo json_encode(array("error" => 0, "bind" => 1));
                }
            }
        }
        else
        {
            echo json_encode(array("error" => 1));
        }
	}
    else
    {
        echo json_encode(array("error" => 0, "bind" => 1));
    }
}
else if($_POST["action"] == 'wechatbind')
{
	if(!isset($_SESSION["wechatid"]))
	{
		if(!isset($_SESSION["key"]))
		{
			$key = hash("SHA256", (string)mt_rand(0,1000000000));
			$keyhash = hash("SHA256", $key);
			$sql = "INSERT INTO wechatbind (`key`) SELECT
                        '$keyhash'
                    FROM
                        DUAL
                    WHERE
                        NOT EXISTS (
                            SELECT
                                `key`
                            FROM
                                wechatbind
                            WHERE
                                `key` = '$keyhash'
                        );";
			$mysqli->real_query($sql);
			if($mysqli->affected_rows == 1)
			{
                $_SESSION["key"] = $key;
				echo json_encode(array("error" => 0, "bind" => 0, "url" => "http://".$Domain."/wechatbindinterface.php?key=".$key."&submit=yes", "key" => $key));
			}
			else
			{
				echo json_encode(array("error" => 0, "bind" => 1, "url" => "http://".$Domain."/wechatbindinterface.php?key=".$key."&submit=yes", "key" => $key));
			}
		}
		else
		{
			$key = $_SESSION["key"];
			//$keyhash = hash("SHA256", $key);
			echo json_encode(array("error" => 0, "bind" => 0, "url" => "http://".$Domain."/wechatbindinterface.php?key=".$key."&submit=yes", "key" => $key));
		}
	}
	else
	{
        $key = $_SESSION["key"];
		echo json_encode(array("error" => 0, "bind" => 1,  "url" => "http://".$Domain."/wechatbindinterface.php?key=".$key."&submit=yes", "key" => $key));
	}
}

?>