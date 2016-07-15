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

$packageId = "S10001";
$order = array(
	'MerchantId' => '',
	'WarehouseId' => 'US',
	'Remark' => 'testing open api',
	'Package' => array(	
		'PackageId' => $packageId,
		'ServiceCode' => 'USRLS',
		'ShipToAddress' => array(
			'Country' => 'US',
			'Province' => 'Idaho',
			'City' => 'Twin Falls',
			'Street1' => '9110 NW 21st street',
			'Street2' => '',
			'Postcode' => '83301',
			'Contact' => 'David Mcaffee',
			'Phone' => '937-689-8216',
			'Email' => '23541566@gmail.com',
		),
		'Skus' => array(
			'0' => array(
				'Sku' => 'a10001',
				'Quantity' => '1',
				'ProductName' => '小梦书包',
				'Price' => '5',
			),
		),
		'SellPrice' => '18.9',
		'SellPriceCurrency' => 'USD',	
		'SalesPlatform' => 'Wish',		
		'Remark' => 'testing packaeg',
	)
);

echo "<pre>";

try
{
	if(isset($_GET['api'])){
		$api = $_GET['api'];
		switch ($api) {
			case 'create':
				$response = $ck1_openapi->post('outboundOrders', json_encode($order));
				break;
			case 'status':
				$response = $ck1_openapi->get('outboundOrders/' . $packageId . '/status');
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
		echo "please input ?api=create or ?api=status at the end of url";
	}
}
catch(Exception $e){	
	echo $e;
}

echo "</pre>";


