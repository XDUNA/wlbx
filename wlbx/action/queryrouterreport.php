<?php
//require_once("../include/global.php");
//$_SESSION["mgr_name"] = "U3lzdGVt";
if($_POST["queryrouterreport"] == NULL)
{
	echo_404($_SERVER['PHP_SELF']);
}
else
{
    if($_POST["queryrouterreport"] == "querytoday")
    {
        $sql = "SELECT
                    `name`,
                    loss,
                    rtt,
                    `condition`
                FROM
                    routerscan
                WHERE
                    date_format(`time`, '%Y-%m-%d') = date_format(now(), '%Y-%m-%d');";
        $mysqli->real_query($sql);
        $result = $mysqli->store_result();
        $data = array("error" => 0,"contentlen" => $result->num_rows);
        while($row = $result->fetch_array())
        {
            array_push($data,
                array("host" => base64_encode($row["name"]),
                      "loss" => $row["loss"],
                      "rtt" => $row["rtt"],
                      "cond" => $row["condition"]
                     )
            );
        }
        echo json_encode($data);
    }
    else
    {
        echo_404($_SERVER['PHP_SELF']);
    }
}
?>