<?php
function http( $url, $method = '', $postfields = null, $headers = array(), $debug = false ) {
        //echo "http\n";
        $ci = curl_init();
        /* Curl settings */
        curl_setopt( $ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
        curl_setopt( $ci, CURLOPT_CONNECTTIMEOUT, 30 );
        curl_setopt( $ci, CURLOPT_TIMEOUT, 30 );
        curl_setopt( $ci, CURLOPT_RETURNTRANSFER, true );

        switch ( $method ) {
            case 'POST':
                curl_setopt( $ci, CURLOPT_POST, true );
                if ( !empty( $postfields ) ) {
                    curl_setopt( $ci, CURLOPT_POSTFIELDS, $postfields );
                    //echo "yes!";
                    //$this->postdata = $postfields;
                }
                break;
        }
        curl_setopt( $ci, CURLOPT_URL, $url );
        curl_setopt( $ci, CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ci, CURLINFO_HEADER_OUT, true );

        $response = curl_exec( $ci );
        $http_code = curl_getinfo( $ci, CURLINFO_HTTP_CODE );

        if ( $debug ) {
            echo "=====post data======\r\n";
            var_dump( $postfields );

            echo '=====info=====' . "\r\n";
            print_r( curl_getinfo( $ci ) );

            echo '=====$response=====' . "\r\n";
            print_r( $response );
        }
        curl_close( $ci );
        return array( $http_code, $response );
    }

function request_post($url = '', $param = '') {
      $postUrl = $url;
      $curlPost = $param;
      // 初始化curl
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $postUrl);
      curl_setopt($curl, CURLOPT_HEADER, 0);
      // 要求结果为字符串且输出到屏幕上
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      // post提交方式
      curl_setopt($curl, CURLOPT_POST, 1);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
      // 运行curl
      $data = curl_exec($curl);
      curl_close($curl);
      return $data;
}
function work_get_userinfo($xgh){
    global $work_api_host;
    $url = "https://$work_api_host/uc/api/user/xgh-by-info";
    $post_data['access_token']  = work_get_access_token();
    $post_data['xgh']  = $xgh;//学工号;
    $res = request_post($url, $post_data);
    $ret = json_decode($res,true);
    if($ret['e']==0) return $ret['d'];
    return $ret;
}

function work_get_access_token() {
    //echo("start;\n");
    global $mysqli;
    global $work_wechat_app_id;
    global $work_wechat_app_secret;
    global $work_api_host;

/*tempB   */ 
    $sql = "SELECT
                token
            FROM
                token
            WHERE
                (NOW()<`time`)
            ORDER BY id DESC
            LIMIT 1";
    $mysqli->real_query($sql);
    $result = $mysqli->store_result();
    if($result->num_rows == 1)
    {
        $row = $result->fetch_array();
        return $row["token"];
    }
    else
    {
    /**/    $appid = $work_wechat_app_id;
        $appsecret = $work_wechat_app_secret;
        $host = $work_api_host;

        $token_access_url = "https://$host/api/third/get-token?appid=" . $appid . "&appsecret=" . $appsecret;

        $res = http($token_access_url); //获取文件内容或获取网络请求的内容
        if($res[ 0 ] == 200)
        {
            $result = json_decode($res[ 1 ], true); //接受一个 JSON 格式的字符串并且把它转换为 PHP 变量
            $expt=$result["d"]["expires_in"];
            $access_token = $result['d']['access_token'];
/*tempC*/
            $sql = "INSERT INTO token (`token`, `time`)
                    VALUE
                        ('$access_token', NOW()+ interval $expt second)";
            $mysqli->real_query($sql);
/**/
            return $access_token;
        }
        return $res[ 1 ];
   }
}
function get_access_token() {
    
    //echo("start;\n");
    global $mysqli;
    global $wechat_app_id;
    global $wechat_app_secret;
    global $iswork;
//work version
if($iswork) return work_get_access_token();
    $sql = "SELECT
                token
            FROM
                token
            WHERE
                ((NOW() - `time`) < 7000)
            ORDER BY id DESC
            LIMIT 1";
    $mysqli->real_query($sql);
    $result = $mysqli->store_result();
    if($result->num_rows == 1)
    {
        $row = $result->fetch_array();
        return $row["token"];
    }
    else
    {
        $appid = $wechat_app_id;
        $appsecret = $wechat_app_secret;

        $token_access_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $appsecret;
        $res = http($token_access_url); //获取文件内容或获取网络请求的内容
        //echo $res;
        if($res[ 0 ] == 200)
        {
            $result = json_decode($res[ 1 ], true); //接受一个 JSON 格式的字符串并且把它转换为 PHP 变量
            $access_token = $result['access_token'];

            $sql = "INSERT INTO token (`token`, `time`)
                    VALUE
                        ('$access_token', NOW())";
            $mysqli->real_query($sql);
            return $access_token;
        }
        return $res[ 1 ];
    }
}
function work_send_msg( $repairid="", $status="", $remark="",$template_id="")
{
    global $mysqli;
    global $work_api_host;
    global $wechat_template_id_1;
    global $wechat_template_id_2;
    global $wechat_template_id_3;
    
    $sql = "SELECT
                wechatid
            FROM
                queue
            WHERE
                repairid = '$repairid'
            LIMIT 1";
    $mysqli->real_query($sql);
    $result = $mysqli->store_result();
    if($result->num_rows == 1)
    {
        $row = $result->fetch_array();
        $xgh = $row["wechatid"];
        //echo $openid;
        $time = date("Y-m-d H:i:s");
		$ct_title = '';
        $ct = '';

        switch($template_id)
        {
            case 1:
				$ct_title="您的报修已经成功提交";
				$ct = "同学您好,您刚刚通过网上报修平台提交了报修请求($repairid)!\n\n"."|状态:$status\n\n".$remark."\n\n|".$time.
				"|\n\n感谢您对我们的支持,如果在整个过程中有任何疑问,请致电:029-81891357.\t也可直接在微信中留言哦~";
            break;
            case 2:
				$ct_title = "您的报修处理进度已更新";
				$ct = "同学您好,您的报修请求($repairid)有新的进度!\n\n"."|状态:$status\n\n".$remark."\n\n|".$time.
				"|\n\n感谢您对我们的支持,如果在整个过程中有任何疑问,请致电:029-81891357.也可直接在微信中留言哦~";
            break;
            case 3:
				$ct_title = "报修处理结果";
				$ct = "同学您好,您的报修请求已经处理完成($repairid)!\n\n"."|状态:$status\n\n".$remark."\n\n|".$time.
				"|\n\n感谢您对我们的支持,祝您生活愉快,学业有成!";
            break;
            case 4:
				$ct_title = "今日报告";
				$ct = "今日报告已生成！\n".$repairid."\n".$status."\n".$remark."\n|".$time."|\n".
				"点击查看交换机状态报告\n"."\nPowered by Tifer King";
            break;
        }
	$url = "https://$work_api_host/uc/api/ucs/qywx";
    $post_data['access_token']  = work_get_access_token();
    $post_data['numbers[]']  =$xgh;//学工号列表，数组;
    $post_data['wid']  = '158';
//    $post_data['content[msgtype]'] = 'text';
//    $post_data['content[text][content]'] = $ct;
	$post_data['content[msgtype]'] = 'news';
	$post_data['content[news][articles][0][title]']=$ct_title;
	$post_data['content[news][articles][0][description]']=$ct;
	$post_data['content[news][articles][0][url]']="http://wlbx.xidian.edu.cn/?搜索.".$repairid;
	$post_data['content[news][articles][0][picurl]']="";
    return request_post($url,$post_data);
	}
	return null;
}
function send_msg( $repairid, $status, $remark, $template_id)
{
    return work_send_msg($repairid, $status, $remark, $template_id);
	global $mysqli;
    global $Domain;
    global $wechat_template_id_1;
    global $wechat_template_id_2;
    global $wechat_template_id_3;
    global $iswork;
	
    $sql = "SELECT
                wechatid
            FROM
                queue
            WHERE
                repairid = '$repairid'
            LIMIT 1";
    $mysqli->real_query($sql);
    $result = $mysqli->store_result();
    if($result->num_rows == 1)
    {
        $row = $result->fetch_array();
        $openid = $row["wechatid"];
        //echo $openid;
        $time = date("Y-m-d H:i:s");
        $post = '';
        switch($template_id)
        {
            case 1:$post = array(
            "touser"=>"$openid",
            "template_id"=>"$wechat_template_id_1",
            "url"=>"http://". $Domain ."/?搜索.".$repairid,
            "topcolor"=>"#00FF00",
            "data"=>array(
                "first"=>array(
                    "value"=>"同学您好：\n您刚刚通过网上报修平台提交了报修请求！",
                    "color"=>"#000000"),
                "keyword1"=>array(
                    "value"=>"$repairid",
                    "color"=>"#173177"),
                "keyword2"=>array(
                    "value"=>"$status",
                    "color"=>"#34c136"),
                "keyword3"=>array(
                    "value"=>"$remark",
                    "color"=>"#000000"),
                "keyword4"=>array(
                    "value"=>"$time",
                    "color"=>"#000000"),
                "remark"=>array(
                    "value"=>"感谢您对我们的支持\n如果在整个过程中有任何疑问，请致电:029-81891357.\n也可直接在微信中留言哦~",
                    "color"=>"#000000")
            )
            );
            break;
            case 2:$post = array(
            "touser"=>"$openid",
            "template_id"=>"$wechat_template_id_2",
            "url"=>"http://". $Domain ."/?搜索.".$repairid,
            "topcolor"=>"#00FF00",
            "data"=>array(
                "first"=>array(
                    "value"=>"同学您好：\n您的报修请求有新的进度！",
                    "color"=>"#000000"),
                "keyword1"=>array(
                    "value"=>"$repairid",
                    "color"=>"#173177"),
                "keyword2"=>array(
                    "value"=>"$status",
                    "color"=>"#34c136"),
                "keyword3"=>array(
                    "value"=>"$remark",
                    "color"=>"#000000"),
                "keyword4"=>array(
                    "value"=>"$time",
                    "color"=>"#000000"),
                "remark"=>array(
                    "value"=>"感谢您对我们的支持\n如果在整个过程中有任何疑问，请致电:029-81891357.\n也可直接在微信中留言哦~",
                    "color"=>"#000000")
            )
            );
            break;
            case 3:$post = array(
            "touser"=>"$openid",
            "template_id"=>"$wechat_template_id_3",
            "url"=>"http://". $Domain ."/?搜索.".$repairid,
            "topcolor"=>"#00FF00",
            "data"=>array(
                "first"=>array(
                    "value"=>"同学您好：\n您的报修请求已经处理完成！",
                    "color"=>"#000000"),
                "keyword1"=>array(
                    "value"=>"$repairid",
                    "color"=>"#173177"),
                "keyword2"=>array(
                    "value"=>"$status",
                    "color"=>"#34c136"),
                "keyword3"=>array(
                    "value"=>"$remark",
                    "color"=>"#000000"),
                "keyword4"=>array(
                    "value"=>"$time",
                    "color"=>"#000000"),
                "remark"=>array(
                    "value"=>"感谢您对我们的支持\n祝您生活愉快，学业有成。",
                    "color"=>"#000000")
            )
            );
            break;
            case 4:$post = array(
            "touser"=>"$openid",
            "template_id"=>"$wechat_template_id_3",
            "url"=>"http://". $Domain ."/".$status,
            "topcolor"=>"#00FF00",
            "data"=>array(
                "first"=>array(
                    "value"=>"今日报告已生成！",
                    "color"=>"#000000"),
                "keyword1"=>array(
                    "value"=>"无",
                    "color"=>"#173177"),
                "keyword2"=>array(
                    "value"=>"$remark",
                    "color"=>"#34c136"),
                "keyword3"=>array(
                    "value"=>"点击查看交换机状态报告",
                    "color"=>"#000000"),
                "keyword4"=>array(
                    "value"=>"$time",
                    "color"=>"#000000"),
                "remark"=>array(
                    "value"=>"Powered by Tifer King",
                    "color"=>"#000000")
            )
            );
            break;
        }
        
        $postfields = json_encode($post, true);
        //echo $postfields;
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".get_access_token();
        http( $url,'POST', $postfields );
        //echo $postfields;
        //echo http( $url,'POST', $postfields )[1];
    }
    else
    {
        
    }
}
?>