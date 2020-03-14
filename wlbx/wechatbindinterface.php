
<?php
/**
 * 微信授权相关接口
 * 
 * @link http://www.phpddt.com
 */
//echo "start\n";
require_once("include/global.php");
function auth_success()
{
    ?><!doctype html>
<html>
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<title>成功</title>
</head>

<body>
    <div style="text-align: center">
	 <h2>绑定微信信息成功!</h2>
	 <span>授权成功后返回:关闭本页面即可</span>
	 <h3>关注西安电子科技大学企业微信<br/>实时跟踪报修进度</h3>
    <p></p>
<img src="images/ew-weixinqiye.png" width="65%" ></img>
    <p>长按图中二维码<br/>关注企业微信并绑定信息.</p>
    <p>子板块:南校区网络报修(学生宿舍)</p>
    <p>版权所有©2019.西电学生网管会.</p>
    </div>
</body>
</html><?php
}
function auth_fail($id)
{
    ?><!doctype html>
<html>
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<title>失败</title>
</head>

<body>
    <h1>请重新扫描二维码！错误代码！<?php echo $id;?>;</h1>
</body>
</html><?php
}
class Wchat {
    private $app_id;
    private $app_secret;
    private $state;
	private $work_api;
    public
    function __construct()
    {
        global $work_wechat_app_id;
        global $work_wechat_app_secret;
        global $mysqli;
		global $work_api_host;
        $this->app_id = $work_wechat_app_id;
        $this->app_secret = $work_wechat_app_secret;
        $this->state = '';
		$this->work_api = $work_api_host;
    }

    /**
     * 获取微信授权链接
     * 
     * @param string $redirect_uri 跳转地址
     * @param mixed $state 参数
     */
    public
    function get_authorize_url( $redirect_uri = '', $state = '' ) {
        $redirect_uri = urlencode( $redirect_uri );
        $state = $_GET[ 'key' ];
        return "https://$this->work_api/uc/api/oauth/default?appid={$this->app_id}&redirect={$redirect_uri}&state={$state}";
    }
    /**
     * 获取微信openid
     */
    public
    function getOpenid( $turl ) {
        //echo "getopenid\n";
        if ( !isset( $_GET[ 'code' ] ) ) {
            //触发微信返回code�?
            //echo "nocode\n";
            $url = $this->get_authorize_url( $turl, $this->state );
			
            Header( "Location: $url" );
            exit();
        } else {
            //echo "havecode\n";
            //获取code码，以获取openid
            $code = $_GET[ 'code' ];
			$actoken = get_access_token();
			$http_url = "https://{$this->work_api}"."/uc/api/oauth/user-by-code?code=$code&access_token=$actoken";
            //echo "$code\n";
            $access_info = $this->http($http_url);
			if($access_info[0]==200){
				return json_decode($access_info[1],true);
			}
            return $access_info;
        }

    }
    /**
     * 获取授权token网页授权
     * 
     * @param string $code 通过get_authorize_url获取到的code
     */
    public
    function get_access_token( $code = '' ) {
        //echo "get_access_token\n";
        $appid = $this->app_id;
        $appsecret = $this->app_secret;

        $token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appid . "&secret=" . $appsecret . "&code=" . $code . "&grant_type=authorization_code";
        //echo $token_url;
        $token_data = $this->http( $token_url );
        // var_dump( $token_data);
        if ( $token_data[ 0 ] == 200 ) {
            $ar = json_decode( $token_data[ 1 ], TRUE );
            return $ar;
        }

        return $token_data[ 1 ];
    }


    public
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
                    $this->postdata = $postfields;
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

}
$openid = isset( $_SESSION[ 'wechatid' ] ) ? $_SESSION[ 'wechatid' ] : '';
//phpinfo();    
if ( empty( $openid ) ) {
    //echo "empty\n";
    $wchat = new wchat();
    $t_url = 'http://'.$Domain.'/wechatbindinterface.php';

    $info = $wchat->getOpenid( $t_url );

    if ( $info ) {
        $openid = $info['d']['role']['number'];
        $_SESSION[ 'wechatid' ] = $openid;
        //setcookie( 'openid', $openid, time() + 86400 * 30 );
        if(isset($_GET['state']))
        {
            $key = hash("SHA256", $_GET['state']);
            $sql = "UPDATE wechatbind SET wechatid = '$openid' WHERE `key` = '$key'";
            $mysqli->real_query($sql);
            if($mysqli->affected_rows == 1)
            {
                auth_success();
            }
            else
            {
                auth_fail(1);
            }
        }
        else
        {
            auth_fail(2);
        }
    }
}
else
{
    if(isset($_GET['key']))
    {
        $key = hash("SHA256", $_GET['key']);
        $sql = "UPDATE wechatbind SET wechatid = '$openid' WHERE `key` = '$key'";
        $mysqli->real_query($sql);
        if($mysqli->affected_rows == 1)
        {
            auth_success(); 
        }
        else
        {
            auth_fail(3);
        }
    }
    else
    {
        //auth_fail(4);
        auth_success(); 
    }
}
?>

