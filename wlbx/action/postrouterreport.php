<?php
//require_once("../include/global.php");
//$_SESSION["mgr_name"] = "U3lzdGVt";
if($_POST["routerreport"] == NULL)
{
	echo_404($_SERVER['PHP_SELF']);
}
else
{
    if($_POST["routerreport"] == "postreport")
    {
        $sql = "";
        $cond = 0;
        for($i = 0; $i < $_POST["length"]; $i++)
        {
            $sql = "INSERT INTO routerscan (
                        `name`,
                        loss,
                        rtt,
                        `condition`,
                        `time`
                    )
                    VALUES
                    ('" . $_POST["name_".$i] . "',
                    '" . $_POST["loss_".$i] . "',
                    '" . $_POST["rtt_".$i] . "',
                    '" . $_POST["cond_".$i] . "',
                    NOW())";
            $mysqli->real_query($sql);
            if($_POST["cond_".$i] == 1)
            {
                $cond++;
            }
        }
       // send_msg("KCCE", "?页面:报告", $cond . "/". $_POST["length"] . "运行正常", 4);
	//send_msg("RD7G", "?页面:报告", $cond . "/". $_POST["length"] . "运行正常", 4);
	//send_msg("7YJ7", "?页面:报告", $cond . "/". $_POST["length"] . "运行正常", 4);

    }
    else
    {
        echo_404($_SERVER['PHP_SELF']);
    }
}
?>