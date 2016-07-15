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

$sku = array(
	'MerchantId' => '',
	'WarehouseId' => 'US',
	'Skus' => array(
		'a10001',
	),
);

$query = array(
	'WarehouseId' => 'US',
	'PageIndex' => '1',
	'PageSize' => '10',
	'Sorting' => 'CreatedDesc',
);

echo "<pre>";

try
{
	if(isset($_GET['api'])){
		$api = $_GET['api'];
		switch ($api) {
			case 'inventory':
				$response = $ck1_openapi->post('inventories', json_encode($sku));
				break;
			case 'list':
				$response = $ck1_openapi->get('storageSkus', $query);
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
		echo "please input ?api=inventory or ?api=list at the end of url";
	}
}
catch(Exception $e){	
	echo $e;
}

echo "</pre>";


