<?php
//require_once("../include/global.php");
//$_SESSION["mgr_name"] = "U3lzdGVt";
if($_POST["pagemgr"] == NULL && isset($_SESSION["mgr_name"]))
{
	echo_404($_SERVER['PHP_SELF']);
}
else
{
    if($_POST["pagemgr"] == "query_page")
    {
        $sql = "SELECT
                    pagename,
                    `enable`,
                    remark
                FROM
                    pagemgr";
        $mysqli->real_query($sql);
        $result = $mysqli->store_result();
        $data = array("error" => 0,"contentlen" => $result->num_rows);
        while($row = $result->fetch_array())
        {
            array_push($data,
                array("name" => $row["pagename"],
                      "enable" => $row["enable"],
                      "type" => "page",
                      "remark" => $row["remark"]
                     )
            );
        }
        $sql = "SELECT
                    pagename,
                    `enable`,
                    remark
                FROM
                    jsmgr";
        $mysqli->real_query($sql);
        $result = $mysqli->store_result();
        $data["contentlen"] += $result->num_rows;
        while($row = $result->fetch_array())
        {
            array_push($data,
                array("name" => $row["pagename"],
                      "enable" => $row["enable"],
                      "type" => "jsmanager",
                      "remark" => $row["remark"]
                     )
            );
        }
        $sql = "SELECT
                    pagename,
                    `enable`,
                    remark
                FROM
                    jsexe";
        $mysqli->real_query($sql);
        $result = $mysqli->store_result();
        $data["contentlen"] += $result->num_rows;
        while($row = $result->fetch_array())
        {
            array_push($data,
                array("name" => $row["pagename"],
                      "enable" => $row["enable"],
                      "type" => "jsexecutor",
                      "remark" => $row["remark"]
                     )
            );
        }
        echo json_encode($data);
    }
    else if($_POST["pagemgr"] == "query_content")
    {
        if(base64_decode($_POST["type"]) == "jsexecutor")
        {
            $pagename = $_POST["name"];
            $sql = "SELECT
                        jscontent,
                        jslocation,
                        remark
                    FROM
                        jsexe
                    WHERE
                        pagename = '$pagename'";
            $mysqli->real_query($sql);
            $result = $mysqli->store_result();
            $data = array("error" => 0,"contentlen" => $result->num_rows);
            while($row = $result->fetch_array())
            {
                array_push($data,
                    array("content" => $row["jscontent"],
                          "path" => $row["jslocation"],
                          "remark" => $row["remark"]
                         )
                );
            }
            echo json_encode($data);
        }
        else if(base64_decode($_POST["type"]) == "jsmanager")
        {
            $pagename = $_POST["name"];
            $sql = "SELECT
                        jscontent,
                        jslocation,
                        remark
                    FROM
                        jsmgr
                    WHERE
                        pagename = '$pagename'";
            $mysqli->real_query($sql);
            $result = $mysqli->store_result();
            $data = array("error" => 0,"contentlen" => $result->num_rows);
            while($row = $result->fetch_array())
            {
                array_push($data,
                    array("content" => $row["jscontent"],
                          "path" => $row["jslocation"],
                          "remark" => $row["remark"]
                         )
                );
            }
            echo json_encode($data);
        }
        else if(base64_decode($_POST["type"]) == "page")
        {
            $pagename = $_POST["name"];
            $sql = "SELECT
                        pagecontent,
                        pagelocation,
                        remark
                    FROM
                        pagemgr
                    WHERE
                        pagename = '$pagename'";
            $mysqli->real_query($sql);
            $result = $mysqli->store_result();
            $data = array("error" => 0,"contentlen" => $result->num_rows);
            while($row = $result->fetch_array())
            {
                array_push($data,
                    array("content" => $row["pagecontent"],
                          "path" => $row["pagelocation"],
                          "remark" => $row["remark"]
                         )
                );
            }
            echo json_encode($data);
        }
    }
    else if($_POST["pagemgr"] == "add_page")
    {
        if(base64_decode($_POST["type"]) == "jsexecutor")
        {
            $pagename = $_POST["name"];
            $jstype = base64_encode("pagecontent");
            $jscontent = $_POST["content"];
            $jslocation = $_POST["path"];
            $remark = $_POST["remark"];
            $sql = "INSERT INTO jsexe (
                        pagename,
                        jstype,
                        jscontent,
                        jslocation,
                        ENABLE
                    )
                    VALUES
                        (
                            '$pagename',
                            '$jstype',
                            '$jscontent',
                            '$jslocation',
                            1
                        )";
            $mysqli->real_query($sql);
        }
        else if(base64_decode($_POST["type"]) == "jsmanager")
        {
            $pagename = $_POST["name"];
            $jstype = base64_encode("pagecontent");
            $jscontent = $_POST["content"];
            $jslocation = $_POST["path"];
            $remark = $_POST["remark"];
            $sql = "INSERT INTO jsmgr (
                        pagename,
                        jstype,
                        jscontent,
                        jslocation,
                        ENABLE
                    )
                    VALUES
                        (
                            '$pagename',
                            '$jstype',
                            '$jscontent',
                            '$jslocation',
                            1
                        )";
            $mysqli->real_query($sql);
        }
        else if(base64_decode($_POST["type"]) == "page")
        {
            $pagename = $_POST["name"];
            $pagecontent = $_POST["content"];
            $pagelocation = $_POST["path"];
            $remark = $_POST["remark"];
            $sql = "INSERT INTO pagemgr (
                        pagename,
                        pagecontent,
                        pagelocation,
                        ENABLE
                    )
                    VALUES
                        (
                            '$pagename',
                            '$pagecontent',
                            '$pagelocation',
                            1
                        )";
            $mysqli->real_query($sql);
        }
        $data = array("error" => 0);
        echo json_encode($data);
    }
    else if($_POST["pagemgr"] == "edit_page")
    {
        if(base64_decode($_POST["type"]) == "jsexecutor")
        {
            $pagename = $_POST["name"];
            $jscontent = $_POST["content"];
            $jslocation = $_POST["path"];
            $remark = $_POST["remark"];
            $sql = "UPDATE jsexe
                    SET jscontent = '$jscontent',
                     jslocation = '$jslocation',
                     remark = '$remark'
                    WHERE
                        pagename = '$pagename'";
            $mysqli->real_query($sql);
        }
        else if(base64_decode($_POST["type"]) == "jsmanager")
        {
            $pagename = $_POST["name"];
            $jscontent = $_POST["content"];
            $jslocation = $_POST["path"];
            $remark = $_POST["remark"];
            $sql = "UPDATE jsmgr
                    SET jscontent = '$jscontent',
                     jslocation = '$jslocation',
                     remark = '$remark'
                    WHERE
                        pagename = '$pagename'";
            $mysqli->real_query($sql);
        }
        else if(base64_decode($_POST["type"]) == "page")
        {
            $pagename = $_POST["name"];
            $pagecontent = $_POST["content"];
            $pagelocation = $_POST["path"];
            $remark = $_POST["remark"];
            $sql = "UPDATE pagemgr
                    SET pagecontent = '$pagecontent',
                     pagelocation = '$pagelocation',
                     remark = '$remark'
                    WHERE
                        pagename = '$pagename'";
            $mysqli->real_query($sql);
        }
        $data = array("error" => 0);
        echo json_encode($data);
    }
    else if($_POST["pagemgr"] == "enable_page")
    {
        $pagename = $_POST["name"];
        if(base64_decode($_POST["type"]) == "page")
        {
            $sql = "UPDATE pagemgr
                    SET `enable` = '1'
                    WHERE
                        pagename = '$pagename'";
            $mysqli->real_query($sql);
            $data = array("error" => 0);
            echo json_encode($data);
        }
        else if(base64_decode($_POST["type"]) == "jsmanager")
        {
            $sql = "UPDATE jsmgr
                    SET `enable` = '1'
                    WHERE
                        pagename = '$pagename'";
            $mysqli->real_query($sql);
            $data = array("error" => 0);
            echo json_encode($data);
        }
        else if(base64_decode($_POST["type"]) == "jsexecutor")
        {
            $sql = "UPDATE jsexe
                    SET `enable` = '1'
                    WHERE
                        pagename = '$pagename'";
            $mysqli->real_query($sql);
            $data = array("error" => 0);
            echo json_encode($data);
        }
    }
    else if($_POST["pagemgr"] == "disable_page")
    {
        $pagename = $_POST["name"];
        if(base64_decode($_POST["type"]) == "page")
        {
            $sql = "UPDATE pagemgr
                    SET `enable` = '0'
                    WHERE
                        pagename = '$pagename'";
            $mysqli->real_query($sql);
            $data = array("error" => 0);
            echo json_encode($data);
        }
        else if(base64_decode($_POST["type"]) == "jsmanager")
        {
            $sql = "UPDATE jsmgr
                    SET `enable` = '0'
                    WHERE
                        pagename = '$pagename'";
            $mysqli->real_query($sql);
            $data = array("error" => 0);
            echo json_encode($data);
        }
        else if(base64_decode($_POST["type"]) == "jsexecutor")
        {
            $sql = "UPDATE jsexe
                    SET `enable` = '0'
                    WHERE
                        pagename = '$pagename'";
            $mysqli->real_query($sql);
            $data = array("error" => 0);
            echo json_encode($data);
        }
    }
}
?>