<?php

/**
 * PHP SDK for chukou1.com (using OAuth2)
 * 
 * @author J.Ning <bnjg@qq.com>
 * @contact API <api@chukou1.com>
 * @date 2016.4
 */

include_once('ck1.openapiv1.class.php');
include_once('test.config.php');

$ck1_openapi = new Ck1OpenApiV1($base_url, $access_token);
$ck1_openapi->restapi_client->debug = true;	//调试状态，显示请求信息

$trackingNumber = "CGT151028TST000002";

echo "<pre>";

try
{
	$response = $ck1_openapi->get('trackings/' . $trackingNumber);
	
	echo "\r\n\r\n";
	echo "=====custom infos======\r\n";
	echo 'http status code: ' . $ck1_openapi->restapi_client->http_code . "\r\n";
	echo 'url: ' . $ck1_openapi->restapi_client->url . "\r\n";
	echo 'response info: ';
	print_r($response);
}
catch(Exception $e){	
	echo $e;
}

echo "</pre>";


