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

$package = array(
	'MerchantId' => '',
	'WarehouseId' => 'US',
	'Skus' => array(
		array(
		'Sku' => 'a10001',
		'Quantity' => '1'
		),
	),
	'Country' => 'US',
	'Postcode' => '83301',
	'Address' => '172 Meadowview lane',
	'Province' => 'Idaho',
	'City' => 'Twin Falls'
);

echo "<pre>";

try
{
	if(isset($_GET['api'])){
		$api = $_GET['api'];
		switch ($api) {
			case 'pricing':
				$package['ServiceCode'] = 'USRLS';
				$response = $ck1_openapi->post('pricing/outbound/skus', json_encode($package));
				break;
			case 'pricing-all':
				$package['IncludeUnsuccessful'] = 'True';
				$response = $ck1_openapi->post('pricing/all/outbound/skus', json_encode($package));
				break;
			default:
				break;
		}
		
		echo "\r\n\r\n";
		echo "=====custom infos======\r\n";
		echo 'http status code: ' . $ck1_openapi->restapi_client->http_code . "\r\n";
		echo 'url: ' . $ck1_openapi->restapi_client->url . "\r\n";
		echo 'response info: ';
		print_r($response);
	}
	else{
		echo "please input ?api=pricing or ?api=pricing-all at the end of url";
	}
}
catch(Exception $e){	
	echo $e;
}

echo "</pre>";


