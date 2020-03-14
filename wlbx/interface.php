<?php
require_once("include/global.php");
require_once("include/AES.php");
if(isset($_POST["require"]))
{
    if($_POST["require"] == 'actcookie')
    {
        require_once("action/actcookie.php");
    }
    else if($_POST["require"] == 'postrouterreport')
    {
        require_once("action/postrouterreport.php");
    }
    else if($_POST["require"] == 'datapost')
    {
        require_once("action/datapost.php");
    }
    else if($_POST["require"] == 'pagemanager')
    {
        require_once("action/pagemanager.php");
    }
    else if($_POST["require"] == 'permission')
    {
        require_once("action/permission.php");
    }
    else if($_POST["require"] == 'scriptexecuter')
    {
        require_once("action/scriptexecuter.php");
    }
    else if($_POST["require"] == 'scriptmanager')
    {
        require_once("action/scriptmanager.php");
    }
    else if($_POST["require"] == 'search')
    {
        require_once("action/search.php");
    }
    else if($_POST["require"] == 'timeline')
    {
        require_once("action/timeline.php");
    }
    else if($_POST["require"] == 'wechatbind')
    {
        require_once("action/wechatbind.php");
    }
    else if($_POST["require"] == 'feedback')
    {
        require_once("action/feedback.php");
    }
    else if($_POST["require"] == 'qiangpiao')
    {
        require_once("action/qiangpiao.php");
    }
    else if($_POST["require"] == 'datamanager' && isset($_SESSION["mgr_name"]))
    {
        require_once("action/datamgr.php");
    }
    else if($_POST["require"] == 'usermanager' && isset($_SESSION["mgr_name"]))
    {
        require_once("action/usermgr.php");
    }
    else if($_POST["require"] == 'pageeditor' && isset($_SESSION["mgr_name"]))
    {
        require_once("action/pageedit.php");
    }
    else if($_POST["require"] == 'wechatnew' && isset($_SESSION["mgr_name"]))
    {
        require_once("action/wechatnew.php");
    }
    else if($_POST["require"] == 'queryrouterreport' && isset($_SESSION["mgr_name"]))
    {
        require_once("action/queryrouterreport.php");
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
?>