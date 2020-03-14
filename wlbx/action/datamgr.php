<?php
/*
status:
0:submitsuccess
10:infconfirm
11:inferror
20:scheduled
30:rescheduling
31:rescheduled
40:repairsuccess
50:repairdely
55:repairfail
100:closed
*/
//require_once("../include/global.php");
//$_SESSION["mgr_name"] = "U3lzdGVt";
if($_POST["datamgr"] == NULL && isset($_SESSION["mgr_name"]))
{
	echo_404($_SERVER['PHP_SELF']);
}
else
{
    if($_POST["datamgr"] == "query_num")
    {
        $username = $_SESSION["mgr_name"];
        $sql = "SELECT
                    COUNT(`id`) AS count
                FROM
                    queue
                WHERE
                    nowstage = '0'
                AND qstatus = '1'
                UNION ALL
                    (
                        SELECT
                            COUNT(`id`) AS count
                        FROM
                            queue
                        WHERE
                            nowstage = '10'
                        AND qstatus = '1'
                    )
                UNION ALL
                    (
                        SELECT
                            COUNT(`id`) AS count
                        FROM
                            queue
                        WHERE
                            nowstage = '20'
                        AND qstatus = '1'
                        AND processer = '$username'
                    )
                UNION ALL
                    (
                        SELECT
                            COUNT(`id`) AS count
                        FROM
                            queue
                        WHERE
                            nowstage = '30'
                        AND qstatus = '1'
                        AND processer = '$username'
                    )
                UNION ALL
                    (
                        SELECT
                            COUNT(`id`) AS count
                        FROM
                            queue
                        WHERE
                            nowstage = '31'
                        AND qstatus = '1'
                        AND processer = '$username'
                    )
                UNION ALL
                    (
                        SELECT
                            COUNT(`id`) AS count
                        FROM
                            queue
                        WHERE
                            nowstage = '50'
                        AND qstatus = '1'
                        AND processer = '$username'
                    )
                UNION ALL
                    (
                        SELECT
                            COUNT(`id`) AS count
                        FROM
                            queue
                        WHERE
                            nowstage = '55'
                        AND qstatus = '1'
                        AND processer = '$username'
                    )";
        $mysqli->real_query($sql);
        $result = $mysqli->store_result();

        $confirm = (int)$result->fetch_row()[0];
        $schedule1 = (int)$result->fetch_row()[0];
        $repair1 = (int)$result->fetch_row()[0];
        $schedule2 = (int)$result->fetch_row()[0];
        $repair2 = (int)$result->fetch_row()[0];
        $delay = (int)$result->fetch_row()[0];
        $fail = (int)$result->fetch_row()[0];

        $schedule = $schedule1 + $schedule2;
        $repair = $repair1 + $repair2;
        echo json_encode(array("error" => 0, "confirm" => $confirm, "schedule1" => $schedule1, "schedule2" => $schedule2, "repair" => $repair, "delay" => $delay, "fail" => $fail));
    }
    else if($_POST["datamgr"] == "query_confirm")
    {
        $sql = "SELECT
                    usrid,
                    tab.repairid,
					
                    freetime,
                    buildnum,
                    floor,
                    section,
                    roomnum,
                    roomside,
                    description,
                    realname,
                    stuid,
					tel,
                    updatetime
                FROM
                    usr
                JOIN (
                    SELECT
                        id,
                        usrid,
                        repairid,
                        freetime,
                        buildnum,
                        floor,
                        section,
                        roomnum,
                        roomside,
                        description
                    FROM
                        queue
                    WHERE
                        nowstage = '0'
                ) AS tab
                JOIN (
                    SELECT
                        repairid,
                        max(`updatetime`) AS updatetime
                    FROM
                        inflist
                    GROUP BY
                        repairid
                ) AS inf
                WHERE
                    usr.id = tab.usrid
                AND tab.repairid = inf.repairid
                ORDER BY
                    tab.id";
        $mysqli->real_query($sql);
        $result = $mysqli->store_result();
        $data = array("error" => 0,"contentlen" => $result->num_rows);
        while($row = $result->fetch_array())
        {

            array_push($data,
                array("repairid" => $row["repairid"],
                      "buildnum" => $row["buildnum"],
                      "floor" => $row["floor"],
                      "section" => $row["section"],
                      "roomnum" => $row["roomnum"],
                      "roomside" => $row["roomside"],
                      "remark" => $row["description"],
                      "name" => base64_encode(AES_decode($row["realname"])),
					//marc 2018.9.23  
					  
					  "tel" => base64_encode(AES_decode($row["tel"])),
					  
                      "id" => base64_encode(AES_decode($row["stuid"])),
                      "time" => base64_encode($row["updatetime"]))
            );
        }
        echo json_encode($data);
    }
    else if($_POST["datamgr"] == "accept")
    {
        $username = $_SESSION["mgr_name"];
        $repairid = $_POST["repairid"];
        send_msg( $repairid, "审核通过", "经核实，您的报修信息正常。\n正常情况下,我们的维修人员会尽快安排在三个工作日内处理到您的位置处理报修请求。", 2);
        $sql = "INSERT INTO inflist (
                    repairid,
                    updatetime,
                    `status`,
                    processer
                ) SELECT
                    '$repairid',
                    NOW(),
                    '10',
                    '$username'
                FROM
                    DUAL
                WHERE
                    NOT EXISTS (
                        SELECT
                            id
                        FROM
                            inflist
                        WHERE
                            repairid = '$repairid'
                        AND `status` > '0'
                    );";
        $mysqli->real_query($sql);
        if($mysqli->affected_rows == 1)
        {
            $sql = "UPDATE queue
                    SET nowstage = '10'
                    WHERE
                        repairid = '$repairid'
                    AND qstatus = '1';";
            $mysqli->real_query($sql);
            echo json_encode(array("error" => 0));
        }
        else
        {
            echo json_encode(array("error" => 1));
        }
    }
    else if($_POST["datamgr"] == "deny")
    {
        $username = $_SESSION["mgr_name"];
        $repairid = $_POST["repairid"];
        $remark = base64_encode($_POST["reason"]);
        send_msg( $repairid, "审核未通过", "您的信息审核未通过，原因是：".$_POST["reason"]."\n请您重新填写报修信息后再次报修。", 3);
        $sql = "INSERT INTO inflist (
                    repairid,
                    updatetime,
                    `status`,
                    remark,
                    processer
                ) SELECT
                    '$repairid',
                    NOW(),
                    '11',
                    '$remark',
                    '$username'
                FROM
                    DUAL
                WHERE
                    NOT EXISTS (
                        SELECT
                            id
                        FROM
                            inflist
                        WHERE
                            repairid = '$repairid'
                        AND `status` > '0'
                    );";
        $mysqli->real_query($sql);
        if($mysqli->affected_rows == 1)
        {
            $sql = "INSERT INTO inflist (
                        repairid,
                        updatetime,
                        `status`,
                        processer
                    ) SELECT
                        '$repairid',
                        NOW(),
                        '100',
                        '$username'
                    FROM
                        DUAL
                    WHERE
                        NOT EXISTS (
                            SELECT
                                id
                            FROM
                                inflist
                            WHERE
                                repairid = '$repairid'
                            AND `status` = '100'
                        );";
            $mysqli->real_query($sql);
            $sql = "UPDATE queue
                    SET qstatus = '0',
                     nowstage = '100',
                     processer = '$username'
                    WHERE
                        repairid = '$repairid'
                    AND qstatus = '1'";
            $mysqli->real_query($sql);
            echo json_encode(array("error" => 0));
        }
        else
        {
            echo json_encode(array("error" => 1));
        }
    }
    else if($_POST["datamgr"] == "query_schedule")
    {
        $sql = "SELECT
                    usrid,
                    tab.repairid,
                    freetime,
                    buildnum,
                    floor,
                    section,
                    roomnum,
                    roomside,
                    description,
                    realname,
                    stuid,
                    tel,
                    updatetime
                FROM
                    usr
                JOIN (
                    SELECT
                        id,
                        usrid,
                        repairid,
                        freetime,
                        buildnum,
                        floor,
                        section,
                        roomnum,
                        roomside,
                        description
                    FROM
                        queue
                    WHERE
                        nowstage = '10'
                ) AS tab
                JOIN (
                    SELECT
                        repairid,
                        max(`updatetime`) AS updatetime
                    FROM
                        inflist
                    GROUP BY
                        repairid
                ) AS inf
                WHERE
                    usr.id = tab.usrid
                AND tab.repairid = inf.repairid
                ORDER BY
                    tab.id";
        $mysqli->real_query($sql);
        $result = $mysqli->store_result();
        $data = array("error" => 0,"contentlen" => $result->num_rows);
        while($row = $result->fetch_array())
        {

            array_push($data,
                array("repairid" => $row["repairid"],
                      "buildnum" => $row["buildnum"],
                      "floor" => $row["floor"],
                      "section" => $row["section"],
                      "roomnum" => $row["roomnum"],
                      "roomside" => $row["roomside"],
                      "remark" => $row["description"],
                      "name" => base64_encode(AES_decode($row["realname"])),
                      "id" => base64_encode(AES_decode($row["stuid"])),
                      "time" => base64_encode($row["updatetime"]),
                      "tel" => base64_encode(AES_decode($row["tel"])),
                      "free" => $row["freetime"]
                     )
            );
        }
        echo json_encode($data);
    }
    else if($_POST["datamgr"] == "schedule")
    {
        $username = $_SESSION["mgr_name"];
        $repairid = $_POST["id"];
        $exptime = base64_encode($_POST["date"]);
        send_msg( $repairid, "已预约", "维修人员已预约在".$_POST["date"]."对您的报修进行现场处理。\n如果与您的安排冲突，请点击此链接申请延迟处理。", 2);
        $sql = "INSERT INTO inflist (
                    repairid,
                    updatetime,
                    `status`,
                    exptime,
                    processer
                ) SELECT
                    '$repairid',
                    NOW(),
                    '20',
                    '$exptime',
                    '$username'
                FROM
                    DUAL
                WHERE
                    NOT EXISTS (
                        SELECT
                            id
                        FROM
                            inflist
                        WHERE
                            repairid = '$repairid'
                        AND `status` > '19'
                    );";
        $mysqli->real_query($sql);
        if($mysqli->affected_rows == 1)
        {
            $sql = "UPDATE queue
                    SET processer = '$username',
                     nowstage = '20'
                    WHERE
                        repairid = '$repairid'
                    AND qstatus = '1'";
            $mysqli->real_query($sql);
            echo json_encode(array("error" => 0));
        }
        else
        {
            echo json_encode(array("error" => 1));
        }
    }
    else if($_POST["datamgr"] == "query_myduty")
    {
        $username = $_SESSION["mgr_name"];
        $sql = "SELECT
                    usrid,
                    tab.repairid,
                    freetime,
                    buildnum,
                    floor,
                    section,
                    roomnum,
                    roomside,
                    description,
                    realname,
                    stuid,
                    tel,
                    updatetime,
                    exptime
                FROM
                    usr
                JOIN (
                    SELECT
                        id,
                        usrid,
                        repairid,
                        freetime,
                        buildnum,
                        floor,
                        section,
                        roomnum,
                        roomside,
                        description
                    FROM
                        queue
                    WHERE
                        (nowstage = '20'
                        OR nowstage = '31')
                    AND processer = '$username'
                ) AS tab
                JOIN (
                    SELECT
                        inflist.repairid AS repairid,
                        inflist.updatetime AS updatetime,
                        inflist.exptime AS exptime
                    FROM
                        inflist
                    JOIN (
                        SELECT
                            repairid,
                            max(`updatetime`) AS updatetime
                        FROM
                            inflist
                        GROUP BY
                            repairid
                    ) AS tmp
                    WHERE
                        inflist.updatetime = tmp.updatetime
                    AND inflist.repairid = tmp.repairid
                    AND inflist.exptime IS NOT NULL
                ) AS inf
                WHERE
                    usr.id = tab.usrid
                AND tab.repairid = inf.repairid
                ORDER BY
                    tab.id";
        $mysqli->real_query($sql);
        $result = $mysqli->store_result();
        $data = array("error" => 0,"contentlen" => $result->num_rows);
        while($row = $result->fetch_array())
        {

            array_push($data,
                array("repairid" => $row["repairid"],
                      "buildnum" => $row["buildnum"],
                      "floor" => $row["floor"],
                      "section" => $row["section"],
                      "roomnum" => $row["roomnum"],
                      "roomside" => $row["roomside"],
                      "remark" => $row["description"],
                      "name" => base64_encode(AES_decode($row["realname"])),
                      "id" => base64_encode(AES_decode($row["stuid"])),
                      "time" => base64_encode($row["updatetime"]),
                      "tel" => base64_encode(AES_decode($row["tel"])),
                      "exptime" => $row["exptime"]
                     )
            );
        }
        echo json_encode($data);
    }
    else if($_POST["datamgr"] == "myduty_repair_success")
    {
        $username = $_SESSION["mgr_name"];
        $repairid = $_POST["repairid"];
        send_msg( $repairid, "维修成功", "您的网络已经重回通畅，感谢您的使用~", 3);
        $sql = "INSERT INTO inflist (
                    repairid,
                    updatetime,
                    `status`,
                    processer
                ) SELECT
                    '$repairid',
                    NOW(),
                    '40',
                    '$username'
                FROM
                    DUAL
                WHERE
                    NOT EXISTS (
                        SELECT
                            id
                        FROM
                            inflist
                        WHERE
                            repairid = '$repairid'
                        AND `status` = '40'
                    );";
        $mysqli->real_query($sql);
        if($mysqli->affected_rows == 1)
        {
            $sql = "INSERT INTO inflist (
                        repairid,
                        updatetime,
                        `status`,
                        processer
                    ) SELECT
                        '$repairid',
                        NOW(),
                        '100',
                        '$username'
                    FROM
                        DUAL
                    WHERE
                        NOT EXISTS (
                            SELECT
                                id
                            FROM
                                inflist
                            WHERE
                                repairid = '$repairid'
                            AND `status` = '100'
                        );";
            $mysqli->real_query($sql);
			$time = time();
            $sql = "UPDATE queue
                    SET qstatus = '0',
                     nowstage = '100',finishtime=$time
                    WHERE
                        repairid = '$repairid' 
                    AND qstatus = '1'";
            $mysqli->real_query($sql);
            echo json_encode(array("error" => 0));
        }
        else
        {
            echo json_encode(array("error" => 1));
        }
    }
    else if($_POST["datamgr"] == "myduty_repair_delay")
    {
        $username = $_SESSION["mgr_name"];
        $repairid = $_POST["repairid"];
        $remark = base64_encode($_POST["reason"]);
        send_msg( $repairid, "维修延缓", "由于：".$_POST["reason"]."您的维修预约被延缓，具体时间会另行通知，请您谅解。", 2);
        $sql = "INSERT INTO inflist (
                    repairid,
                    updatetime,
                    `status`,
                    remark,
                    processer
                ) SELECT
                    '$repairid',
                    NOW(),
                    '50',
                    '$remark',
                    '$username'
                FROM
                    DUAL
                WHERE
                    NOT EXISTS (
                        SELECT
                            id
                        FROM
                            inflist
                        WHERE
                            repairid = '$repairid'
                        AND `status` = '50'
                        AND updatetime = NOW()
                    );";
        $mysqli->real_query($sql);
        if($mysqli->affected_rows == 1)
        {
            $sql = "UPDATE queue
                    SET nowstage = '50'
                    WHERE
                        repairid = '$repairid' 
                    AND qstatus = '1'";
            $mysqli->real_query($sql);
            echo json_encode(array("error" => 0));
        }
        else
        {
            echo json_encode(array("error" => 1));
        }
    }
    else if($_POST["datamgr"] == "myduty_repair_fail")
    {
        $username = $_SESSION["mgr_name"];
        $repairid = $_POST["repairid"];
        $remark = base64_encode($_POST["reason"]);
        send_msg( $repairid, "维修失败", "由于：".$_POST["reason"]."我们暂时无法维修好您的网络，请您谅解。", 3);
        $sql = "INSERT INTO inflist (
                    repairid,
                    updatetime,
                    `status`,
                    remark,
                    processer
                ) SELECT
                    '$repairid',
                    NOW(),
                    '55',
                    '$remark',
                    '$username'
                FROM
                    DUAL
                WHERE
                    NOT EXISTS (
                        SELECT
                            id
                        FROM
                            inflist
                        WHERE
                            repairid = '$repairid'
                        AND `status` = '55'
                    );";
        $mysqli->real_query($sql);
        if($mysqli->affected_rows == 1)
        {
            $sql = "INSERT INTO inflist (
                        repairid,
                        updatetime,
                        `status`,
                        processer
                    ) SELECT
                        '$repairid',
                        NOW(),
                        '100',
                        '$username'
                    FROM
                        DUAL
                    WHERE
                        NOT EXISTS (
                            SELECT
                                id
                            FROM
                                inflist
                            WHERE
                                repairid = '$repairid'
                            AND `status` = '100'
                        );";
            $mysqli->real_query($sql);
			$time = time();
            $sql = "UPDATE queue
                    SET qstatus = '0',
                     nowstage = '100',finishtime=$time
                    WHERE
                        repairid = '$repairid' 
                    AND qstatus = '1'";
            $mysqli->real_query($sql);
            echo json_encode(array("error" => 0));
        }
        else
        {
            echo json_encode(array("error" => 1));
        }
    }
    else if($_POST["datamgr"] == "query_delay")
    {
        $username = $_SESSION["mgr_name"];
        $sql = "SELECT
                    usrid,
                    tab.repairid,
                    freetime,
                    buildnum,
                    floor,
                    section,
                    roomnum,
                    roomside,
                    description,
                    realname,
                    stuid,
                    tel,
                    updatetime
                FROM
                    usr
                JOIN (
                    SELECT
                        id,
                        usrid,
                        repairid,
                        freetime,
                        buildnum,
                        floor,
                        section,
                        roomnum,
                        roomside,
                        description
                    FROM
                        queue
                    WHERE
                        (nowstage = '30'
                    OR nowstage = '50')
                    AND processer = '$username'
                ) AS tab
                JOIN (
                    SELECT
                        repairid,
                        max(`updatetime`) AS updatetime
                    FROM
                        inflist
                    GROUP BY
                        repairid
                ) AS inf
                WHERE
                    usr.id = tab.usrid
                AND tab.repairid = inf.repairid
                ORDER BY
                    tab.id";
        $mysqli->real_query($sql);
        $result = $mysqli->store_result();
        $data = array("error" => 0,"contentlen" => $result->num_rows);
        while($row = $result->fetch_array())
        {

            array_push($data,
                array("repairid" => $row["repairid"],
                      "buildnum" => $row["buildnum"],
                      "floor" => $row["floor"],
                      "section" => $row["section"],
                      "roomnum" => $row["roomnum"],
                      "roomside" => $row["roomside"],
                      "remark" => $row["description"],
                      "name" => base64_encode(AES_decode($row["realname"])),
                      "id" => base64_encode(AES_decode($row["stuid"])),
                      "time" => base64_encode($row["updatetime"]),
                      "free" => $row["freetime"],
                      "tel" => base64_encode(AES_decode($row["tel"]))
                     )
            );
        }
        echo json_encode($data);
    }
    else if($_POST["datamgr"] == "reschedule")
    {
        $username = $_SESSION["mgr_name"];
        $repairid = $_POST["id"];
        $exptime = base64_encode($_POST["date"]);
        send_msg( $repairid, "重预约成功", "维修人员已经重新将预约时间调整为：".$_POST["date"]."\n如果与您的安排冲突，请点击此链接申请延迟处理。", 2);
        $sql = "INSERT INTO inflist (
                    repairid,
                    updatetime,
                    `status`,
                    exptime,
                    processer
                ) VALUES (
                    '$repairid',
                    NOW(),
                    '31',
                    '$exptime',
                    '$username'
                )";
        $mysqli->real_query($sql);
        if($mysqli->affected_rows == 1)
        {
            $sql = "UPDATE queue
                    SET processer = '$username',
                     nowstage = '31'
                    WHERE
                        repairid = '$repairid'
                    AND qstatus = '1'";
            $mysqli->real_query($sql);
            echo json_encode(array("error" => 0));
        }
        else
        {
            echo json_encode(array("error" => 1));
        }
    }else if($_POST["datamgr"] == "query_report")
    {
        $start_num = $_POST["start"];
        $capa_num = $_POST["capa"];
        $sql = "SELECT
                    usrid,
                    tab.repairid,
                    freetime,
                    buildnum,
                    floor,
                    section,
                    roomnum,
                    roomside,
                    description,
                    realname,
                    stuid,
                    tel,
                    nowstage,
                    qstatus,
                    processer
                FROM
                    usr
                JOIN (
                    SELECT
                        id,
                        usrid,
                        repairid,
                        freetime,
                        buildnum,
                        floor,
                        section,
                        roomnum,
                        roomside,
                        description,
                        nowstage,
                        qstatus,
                        processer
                    FROM
                        queue
                ) AS tab
                WHERE
                    usr.id = tab.usrid
                ORDER BY
                    tab.id DESC
                LIMIT $start_num, $capa_num";
        $mysqli->real_query($sql);
        $result = $mysqli->store_result();
        $data = array("error" => 0,"contentlen" => $result->num_rows);
        while($row = $result->fetch_array())
        {
            array_push($data,
                array("repairid" => $row["repairid"],
                      "buildnum" => $row["buildnum"],
                      "floor" => $row["floor"],
                      "section" => $row["section"],
                      "roomnum" => $row["roomnum"],
                      "roomside" => $row["roomside"],
                      "remark" => $row["description"],
                      "name" => base64_encode(AES_decode($row["realname"])),
                      "id" => base64_encode(AES_decode($row["stuid"])),
                      "tel" => base64_encode(AES_decode($row["tel"])),
                      "nowstage" => base64_encode($row["nowstage"]),
                      "status" => base64_encode($row["qstatus"]),
                      "processer" => ($row["processer"] == null ? base64_encode("system") : base64_encode(AES_decode($row["processer"])))
                     )
            );
        }
        echo json_encode($data);
    }else if($_POST["datamgr"] == "query_item")
    {
        $query_type = $_POST["type"];
        $query_content = base64_decode($_POST["content"]);
        $sql = "SELECT
	               NULL;";
        if($query_type == "search_repairid")
        {
            $sql = "SELECT
                    usrid,
                    tab.repairid,
                    freetime,
                    buildnum,
                    floor,
                    section,
                    roomnum,
                    roomside,
                    description,
                    realname,
                    stuid,
                    tel,
                    nowstage,
                    qstatus,
                    processer
                FROM
                    usr
                JOIN (
                    SELECT
                        id,
                        usrid,
                        repairid,
                        freetime,
                        buildnum,
                        floor,
                        section,
                        roomnum,
                        roomside,
                        description,
                        nowstage,
                        qstatus,
                        processer
                    FROM
                        queue
                    WHERE
                        repairid = '$query_content'
                ) AS tab
                WHERE
                    usr.id = tab.usrid
                ORDER BY
                    tab.id DESC";
        }else if($query_type == "search_stuid")
        {
            $stu_hash = hash("SHA256", $query_content);
            $sql = "SELECT
                    usrid,
                    tab.repairid,
                    freetime,
                    buildnum,
                    floor,
                    section,
                    roomnum,
                    roomside,
                    description,
                    realname,
                    stuid,
                    tel,
                    nowstage,
                    qstatus,
                    processer
                FROM
                    usr
                JOIN (
                    SELECT
                        id,
                        usrid,
                        repairid,
                        freetime,
                        buildnum,
                        floor,
                        section,
                        roomnum,
                        roomside,
                        description,
                        nowstage,
                        qstatus,
                        processer
                    FROM
                        queue
                ) AS tab
                WHERE
                    usr.id = tab.usrid
                AND
                    usr.stuidhash = '$stu_hash'
                ORDER BY
                    tab.id DESC";
        }else if($query_type == "search_name"){
            $name_hash = hash("SHA256", $query_content);
            $sql = "SELECT
                    usrid,
                    tab.repairid,
                    freetime,
                    buildnum,
                    floor,
                    section,
                    roomnum,
                    roomside,
                    description,
                    realname,
                    stuid,
                    tel,
                    nowstage,
                    qstatus,
                    processer
                FROM
                    usr
                JOIN (
                    SELECT
                        id,
                        usrid,
                        repairid,
                        freetime,
                        buildnum,
                        floor,
                        section,
                        roomnum,
                        roomside,
                        description,
                        nowstage,
                        qstatus,
                        processer
                    FROM
                        queue
                ) AS tab
                WHERE
                    usr.id = tab.usrid
                AND
                    usr.namehash = '$name_hash'
                ORDER BY
                    tab.id DESC";
        }else if($query_type == "search_tel"){
            $tel_hash = hash("SHA256", $query_content);
            $sql = "SELECT
                    usrid,
                    tab.repairid,
                    freetime,
                    buildnum,
                    floor,
                    section,
                    roomnum,
                    roomside,
                    description,
                    realname,
                    stuid,
                    tel,
                    nowstage,
                    qstatus,
                    processer
                FROM
                    usr
                JOIN (
                    SELECT
                        id,
                        usrid,
                        repairid,
                        freetime,
                        buildnum,
                        floor,
                        section,
                        roomnum,
                        roomside,
                        description,
                        nowstage,
                        qstatus,
                        processer
                    FROM
                        queue
                ) AS tab
                WHERE
                    usr.id = tab.usrid
                AND
                    usr.telhash = '$tel_hash'
                ORDER BY
                    tab.id DESC";
        }
        $mysqli->real_query($sql);
        $result = $mysqli->store_result();
        $data = array("error" => 0,"contentlen" => $result->num_rows);
        while($row = $result->fetch_array())
        {
            array_push($data,
                array("repairid" => $row["repairid"],
                      "buildnum" => $row["buildnum"],
                      "floor" => $row["floor"],
                      "section" => $row["section"],
                      "roomnum" => $row["roomnum"],
                      "roomside" => $row["roomside"],
                      "remark" => $row["description"],
                      "name" => base64_encode(AES_decode($row["realname"])),
                      "id" => base64_encode(AES_decode($row["stuid"])),
                      "tel" => base64_encode(AES_decode($row["tel"])),
                      "nowstage" => base64_encode($row["nowstage"]),
                      "status" => base64_encode($row["qstatus"]),
                      "processer" => ($row["processer"] == null ? base64_encode("system") : base64_encode(AES_decode($row["processer"])))
                     )
            );
        }
        echo json_encode($data);
    }else if($_POST["datamgr"] == "query_summary")
    {
        $data = array("error" => 0,"contentlen" => 24);
        $sql = "SELECT
                    YEARWEEK(
                        date_format(updatetime, '%Y-%m-%d'),1
                    ) AS time,
                    WEEKOFYEAR(
                        date_format(updatetime, '%Y-%m-%d')
                    ) AS weeknum,
                    count(*) AS count
                FROM
                    `inflist`
                WHERE
                    `status` = '100'
                GROUP BY
                    time
                ORDER BY
                    time DESC
                LIMIT 10;";
        $mysqli->real_query($sql);
        $result = $mysqli->store_result();
        while($row = $result->fetch_array())
        {
            array_push($data,
                array("count" => $row["count"],
                      "weeknum" => $row["weeknum"]
                     )
            );
        }
        $sql = "SELECT
                    YEARWEEK(
                        date_format(updatetime, '%Y-%m-%d'),1
                    ) AS time,
                    WEEKOFYEAR(
                        date_format(updatetime, '%Y-%m-%d')
                    ) AS weeknum,
                    count(*) AS count
                FROM
                    `inflist`
                WHERE
                    `status` = '0'
                GROUP BY
                    time
                ORDER BY
                    time DESC
                LIMIT 10;";
        $mysqli->real_query($sql);
        $result = $mysqli->store_result();
        while($row = $result->fetch_array())
        {
            array_push($data,
                array("count" => $row["count"],
                      "weeknum" => $row["weeknum"]
                     )
            );
        }
        $sql = "SELECT
                    buildnum,
                    count(*) AS count
                FROM
                    (
                        SELECT
                            id,
                            buildnum
                        FROM
                            queue
                        ORDER BY
                            id DESC
                        LIMIT 100
                    ) AS tab
                GROUP BY
                    buildnum
                ORDER BY
                    count DESC
                LIMIT 4";
        $mysqli->real_query($sql);
        $result = $mysqli->store_result();
        while($row = $result->fetch_array())
        {
            array_push($data,
                array("buildnum" => $row["buildnum"],
                      "count" => $row["count"]
                     )
            );
        }
        $sql = "SELECT
                    count(*) AS count
                FROM
                    queue";
        $mysqli->real_query($sql);
        $result = $mysqli->store_result();
        while($row = $result->fetch_array())
        {
            $data["tot_count"] = $row["count"];
        }
        echo json_encode($data);
    }
    else if($_POST["datamgr"] == "query_qiangpiao")
    {
        $sql = "SELECT
                    id,
                    `name`,
                    stuid,
                    tel,
                    taket_name,
                    qp_chang,
                    qp_ci,
                    qp_time
                FROM
                    qiangpiao
                JOIN (
                    SELECT
                        `name` AS taket_name,
                        chang,
                        ci
                    FROM
                        qiangpiao_idx
                ) AS tab
                WHERE
                    qiangpiao.qp_chang = tab.chang
                AND qiangpiao.qp_ci = tab.ci
                ORDER BY
                    id";
        $mysqli->real_query($sql);
        $result = $mysqli->store_result();
        $data = array("error" => 0,"contentlen" => $result->num_rows);
        while($row = $result->fetch_array())
        {

            array_push($data,
                array("id" => $row["id"],
                      "name" => base64_encode(AES_decode($row["name"])),
                      "stuid" => base64_encode(AES_decode($row["stuid"])),
                      "tel" => base64_encode(AES_decode($row["tel"])),
                      "chang" => base64_encode($row["qp_chang"]),
                      "ci" => base64_encode($row["qp_ci"]),
                      "tkt_name" => $row["taket_name"]
                     ));
        }
        echo json_encode($data);
    }else if($_POST["datamgr"] == "query_export")
    {
        $start_time = $_POST["start_time"];
        $stop_time = $_POST["stop_time"];
        $sql = "SELECT
                    usrid,
                    tab.repairid,
                    buildnum,
                    floor,
                    section,
                    roomnum,
                    roomside,
                    description,
                    realname,
                    stuid,
                    tel,
                    nowstage,
                    qstatus,
                    processer,
                    timestart,
                    timestop
                FROM
                    usr
                JOIN (
                    SELECT
                        id,
                        usrid,
                        repairid,
                        freetime,
                        buildnum,
                        floor,
                        section,
                        roomnum,
                        roomside,
                        description,
                        nowstage,
                        qstatus,
                        processer
                    FROM
                        queue
                ) AS tab
                JOIN (
                    SELECT
                        repairid,
                        updatetime AS timestart
                    FROM
                        inflist
                    WHERE
                        `status` = 0
                    AND unix_timestamp(updatetime) BETWEEN unix_timestamp('$start_time')
                    AND unix_timestamp('$stop_time')
                ) AS tab_start
                JOIN (
                    SELECT
                        repairid,
                        updatetime AS timestop
                    FROM
                        inflist
                    WHERE
                        `status` = 100
                ) AS tab_stop
                WHERE
                    usr.id = tab.usrid
                AND tab.repairid = tab_start.repairid
                AND tab.repairid = tab_stop.repairid
                ORDER BY
                    tab.id DESC";
        $mysqli->real_query($sql);
        $result = $mysqli->store_result();
        $data = array("error" => 0,"contentlen" => $result->num_rows);
        while($row = $result->fetch_array())
        {
            array_push($data,
                array("repairid" => $row["repairid"],
                      "buildnum" => $row["buildnum"],
                      "floor" => $row["floor"],
                      "section" => $row["section"],
                      "roomnum" => $row["roomnum"],
                      "roomside" => $row["roomside"],
                      "remark" => $row["description"],
                      "name" => base64_encode(AES_decode($row["realname"])),
                      "id" => base64_encode(AES_decode($row["stuid"])),
                      "tel" => base64_encode(AES_decode($row["tel"])),
                      "nowstage" => base64_encode($row["nowstage"]),
                      "status" => base64_encode($row["qstatus"]),
                      "processer" => ($row["processer"] == null ? base64_encode("system") : base64_encode(AES_decode($row["processer"]))),
                      "timestart" => base64_encode($row["timestart"]),
                      "timestop" => base64_encode($row["timestop"])
                     )
            );
        }
        echo json_encode($data);
    }
}

/*
status:
0:submitsuccess
10:infconfirm
11:inferror
20:scheduled
30:rescheduling
31:rescheduled
40:repairsuccess
50:repairdely
55:repairfail
100:closed
*/
?>