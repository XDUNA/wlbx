<?php
//require_once("../include/global.php");
//$_SESSION["mgr_name"] = "U3lzdGVt";
if($_POST["usermgr"] == NULL && isset($_SESSION["mgr_name"]))
{
	echo_404($_SERVER['PHP_SELF']);
}
else
{
    if($_POST["usermgr"] == "query_profile")
    {
        $mgrid = $_SESSION["mgr_id"];
        $sql = "SELECT
                    realname,
                    stuid,
                    tel,
                    remark
                FROM
                    usr
                WHERE
                    id = '$mgrid'";
        $mysqli->real_query($sql);
        $result = $mysqli->store_result();
        $data = array("error" => 0,"contentlen" => $result->num_rows);
        while($row = $result->fetch_array())
        {
            array_push($data,
                array("name" => base64_encode(AES_decode($row["realname"])),
                      "id" => base64_encode(AES_decode($row["stuid"])),
                      "tel" => base64_encode(AES_decode($row["tel"])),
                      "remark" => $row["remark"]
                     )
            );
        }
        echo json_encode($data);
    }
    else if($_POST["usermgr"] == "query_profile_new")
    {
        if(isset($_SESSION["wechatid_new"]))
        {
            $mgr_wechat = $_SESSION["wechatid_new"];
            $sql = "SELECT
                        realname,
                        stuid,
                        tel,
                        groups
                    FROM
                        usr
                    WHERE
                        wechatid = '$mgr_wechat'
                    AND
                        enable = '1'";
            $mysqli->real_query($sql);
            $result = $mysqli->store_result();
            $data = array("error" => 0,"contentlen" => $result->num_rows);
            while($row = $result->fetch_array())
            {
                $groups = 0;
                if($row["groups"] == base64_encode("mgr"))
                {
                    $groups = 1;
                }
                array_push($data,
                    array("name" => base64_encode(AES_decode($row["realname"])),
                          "id" => base64_encode(AES_decode($row["stuid"])),
                          "tel" => base64_encode(AES_decode($row["tel"])),
                          "group" => $groups
                         )
                );
            }
            echo json_encode($data);
        }
        else
        {
            $data = array("error" => 1);
            echo json_encode($data);
        }
    }
    else if($_POST["usermgr"] == "logout")
    {
        $_SESSION = array();
        $data = array("error" => 0);
        echo json_encode($data);
    }
    else if($_POST["usermgr"] == "query_mgr")
    {
        $mgr = base64_encode("mgr");
        $sql = "SELECT
                    realname,
                    stuid,
                    tel,
                    remark
                FROM
                    usr
                WHERE
                    groups = '$mgr'";
        $mysqli->real_query($sql);
        $result = $mysqli->store_result();
        $data = array("error" => 0,"contentlen" => $result->num_rows);
        while($row = $result->fetch_array())
        {
            array_push($data,
                array("name" => base64_encode(AES_decode($row["realname"])),
                      "id" => base64_encode(AES_decode($row["stuid"])),
                      "tel" => base64_encode(AES_decode($row["tel"])),
                      "remark" => $row["remark"]
                     )
            );
        }
        echo json_encode($data);
    }
    else if($_POST["usermgr"] == "new_admin")
    {
        if(isset($_SESSION["wechatid_new"]))
        {
            $mgr_wechat = $_SESSION["wechatid_new"];
            $name = AES_encode(base64_decode($_POST["name"]));
            $name_hash = hash("SHA256",base64_decode($_POST["name"]));
            $stuid = AES_encode(base64_decode($_POST["stuid"]));
            $stuid_hash = hash("SHA256",base64_decode($_POST["stuid"]));
            $tel = AES_encode(base64_decode($_POST["tel"]));
            $tel_hash = hash("SHA256",base64_decode($_POST["tel"]));
            $remark = base64_encode("由" . AES_decode($_SESSION["mgr_name"]) . "添加");
            $group = base64_encode("mgr");
            $sql = "INSERT INTO usr (
                    realname,
                    namehash,
                    stuid,
                    stuidhash,
                    tel,
                    telhash,
                    wechatid,
                    groups,
                    enable,
                    remark
                ) SELECT
                    '$name',
                    '$name_hash',
                    '$stuid',
                    '$stuid_hash',
                    '$tel',
                    '$tel_hash',
                    '$mgr_wechat',
                    '$group',
                    '1',
                    '$remark'
                FROM
                    DUAL
                WHERE
                    NOT EXISTS (
                        SELECT
                            id
                        FROM
                            usr
                        WHERE
                            wechatid = '$mgr_wechat'
                        AND enable = '1'
                    );";
            $mysqli->real_query($sql);
            if($mysqli->affected_rows == 0)
            {
                $remark = base64_encode("由" . AES_decode($_SESSION["mgr_name"]) . "提升权限");
                $sql = "UPDATE usr SET 
                            groups = '$group',
                            remark = '$remark'
                        WHERE
                            wechatid = '$mgr_wechat'
                            AND enable = '1'
                        ";
                $mysqli->real_query($sql);
                if($mysqli->affected_rows == 1)
                {
                    $data = array("error" => 0);
                    echo json_encode($data);
                }
                else
                {
                    $data = array("error" => 1);
                    echo json_encode($data);
                }
            }
            else
            {
                $data = array("error" => 0);
                echo json_encode($data);
            }
        }
    }
}
?>