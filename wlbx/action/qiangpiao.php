<?php

if($_POST["qiangpiao"] == NULL)
{
	echo_404($_SERVER['PHP_SELF']);
}
else if($_POST["qiangpiao"] == "submit")
{
	$name = AES_encode($_POST["name"]);
	$name_hash = hash("SHA256",$_POST["name"]);
	$stuid = AES_encode($_POST["stuid"]);
	$stuid_hash = hash("SHA256",$_POST["stuid"]);
	$tel = AES_encode($_POST["tel"]);
	$tel_hash = hash("SHA256",$_POST["tel"]);
    $key = hash("SHA256", $_POST["key"]);
    $chang = $_POST["chang"];
    $ci = $_POST["ci"];
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
	$sql = "INSERT INTO qiangpiao (
                `name`,
                `name_hash`,
                `stuid`,
                `stuid_hash`,
                `tel`,
                `tel_hash`,
                `wechat`,
                `qp_chang`,
                `qp_ci`,
                `qp_time`
            ) SELECT
                '$name',
                '$name_hash',
                '$stuid',
                '$stuid_hash',
                '$tel',
                '$tel_hash',
                '$wechatid',
                '$chang',
                '$ci',
                NOW()
            FROM
                DUAL
            WHERE
                NOT EXISTS (
                    SELECT
                        cnt
                    FROM(
                        SELECT
                            count(id) AS `cnt`,
                            wechat
                        FROM
                            qiangpiao
                        WHERE
                            wechat = '$wechatid'
                        AND qp_chang = '$chang'
                        GROUP BY wechat
                    ) AS tab
                    WHERE
                        tab.cnt >= 10
                )
            AND
                NOT EXISTS (
                    SELECT
                        id
                    FROM
                        qiangpiao
                    WHERE
                        stuid_hash = '$stuid_hash'
                    AND qp_chang = '$chang'
                )
            AND EXISTS (
                SELECT
                    id
                FROM
                    qiangpiao_idx
                WHERE
                    chang = '$chang'
                AND ci = '$ci'
                AND qp_limit > qp_count
                AND UNIX_TIMESTAMP(NOW()) >= UNIX_TIMESTAMP(begin_time)
            );";
    //echo $sql;
	$mysqli->real_query($sql);
    if($mysqli->affected_rows == 1)
    {
        $sql = "UPDATE qiangpiao_idx
                SET `qp_count` = `qp_count` + 1
                WHERE
                    `chang` = '$chang'
                AND `ci` = '$ci'";
        $mysqli->real_query($sql);
        $array = array('error' => 0,'content' => base64_encode('Success!'));
        echo json_encode($array);
    }else{
        $array = array('error' => 1,'content' => base64_encode('Already Exist!'));
        echo json_encode($array);
    }
}
else if($_POST["qiangpiao"] == "query")
{
    $chang = $_POST["chang"];
    $sql = "SELECT
                `name`,
                ci,
                UNIX_TIMESTAMP(`begin_time`) AS begin_time,
                UNIX_TIMESTAMP(`end_time`) AS end_time,
                qp_limit,
                qp_count,
                UNIX_TIMESTAMP(NOW()) AS nowtime
            FROM
                qiangpiao_idx
            WHERE
                chang = '$chang'
            AND UNIX_TIMESTAMP(end_time) > UNIX_TIMESTAMP(NOW())
            ORDER BY
                ci ASC
            LIMIT 2";
    $mysqli->real_query($sql);
    $result = $mysqli->store_result();
    $data = array("error" => 0,"contentlen" => $result->num_rows);
    while($row = $result->fetch_array())
    {

        array_push($data,
            array("name" => $row["name"],
                  "ci" => $row["ci"],
                  "begin_time" => $row["begin_time"],
                  "end_time" => $row["end_time"],
                  "tkt_limit" => $row["qp_limit"],
                  "tkt_count" => $row["qp_count"],
                  "now_time" => $row["nowtime"]
                 )
        );
    }
    echo json_encode($data);
}
else if($_POST["qiangpiao"] == "refreash")
{
    $_SESSION = array();
    $data = array("error" => 0);
    echo json_encode($data);
}
else
{
	echo_404($_SERVER['PHP_SELF']);
}
?>