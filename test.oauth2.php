<?php

/**
 * PHP SDK for chukou1.com (using OAuth2)
 * 
 * @author J.Ning <bnjg@qq.com>
 * @contact API <api@chukou1.com>
 * @date 2016.4
 */

/*
 * 测试方法
 * 1. 先访问 http://{测试域名}/ck1.oauth2.class.php，得到AuthorizeURL
 * 2. 在浏览器中访问AuthorizeURL，并登陆出口易账号完成授权，授权成功后，将跳转到RedirectUri并返回code参数
 * 3. 在返回页面url中得到code，如http://appdemo.test-ck1.cn/return_sample.php?code=MWY1MjM1MTYtMDk5Yy00NzZmLWFkYmUtYWJhM2YxZjEyOWVl
 * 4. 然后访问 http://{测试域名}/ck1.oauth2.class.php?code={Url返回的code}
 * 5. 获取完整的token信息，可获取RefreshToken和AccessToken以及对应的有效期信息
 * 6. 然后访问 http://{测试域名}/ck1.oauth2.class.php?refresh_token={返回的RefreshToken}，可以重新获取AccessToken
 * */

include_once('ck1.oauth2.class.php');
include_once('test.config.php');

$ck1_oauth2 = new Ck1OAuth2($oauth2_base_url, $client_id, $client_secret, $redirect_uri);
$ck1_oauth2->restapi_client->debug = true;	//调试状态，显示请求信息

echo "<pre>";

try
{
	if(isset($_GET['code'])){
		$token = $ck1_oauth2->getToken($_GET['code']);
		print_r($token);
		
	}
	else if(isset($_GET['refresh_token'])){	
		$token = $ck1_oauth2->getAccessToken($_GET['refresh_token']);
		print_r($token);
	}
	else{
		echo "Visit AuthorizeURL:" . "\r\n";
		echo $ck1_oauth2->getAuthorizeURL($return_code_redirect_uri). "\r\n";		
		echo "\r\n";
		echo "Then input ?code={code} or ?refresh_token={refresh_token} at the end of url";
	}
}
catch(Exception $e){	
	echo $e;
}

echo "</pre>";


