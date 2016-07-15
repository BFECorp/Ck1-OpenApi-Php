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

$packageId = "P10001";
$order = array(
	'MerchantId' => '',
	'Location' => 'GZ',
	'Remark' => 'testing open api',
	'Package' => array(	
		'PackageId' => $packageId,
		'ServiceCode' => 'CUE',
		'Weight' => '100',
		'Length' => '20',
		'Width' => '10',
		'Height' => '15',
		'ShipToAddress' => array(
			'Country' => 'US',
			'Province' => 'Idaho',
			'City' => 'Twin Falls',
			'Street1' => '9110 NW 21st street',
			'Street2' => '',
			'Postcode' => '83301aaa',
			'Contact' => 'David Mcaffee',
			'Phone' => '937-689-8216',
			'Email' => '23541566@gmail.com',
		),
		'Skus' => array(
			'0' => array(
				'Sku' => 'a111',
				'Quantity' => '1',
				'Weight' => '90',
				'DeclareValue' => '5',
				'DeclareNameEn' => 'bag',
				'DeclareNameCn' => '小梦书包',
				'ProductName' => '小梦书包',
				'Price' => '5',
			),
			'1' => array(
				'Sku' => 'a222',
				'Quantity' => '1',
				'Weight' => '100',
				'DeclareValue' => '51',
				'DeclareNameEn' => 'bag2',
				'DeclareNameCn' => '小梦书包2',
				'ProductName' => '小梦书包2',
				'Price' => '51',
			)
		),
		'SellPrice' => '18.9',
		'SellPriceCurrency' => 'USD',	
		'SalesPlatform' => 'Wish',		
		'Remark' => 'testing packaeg',
	)
);

$label = array(
	'MerchantId' => '',
	'PackageIds' => array(
		$packageId,
	),
	'PrintFormat' => 'ClassicA4',
	'PrintContent' => 'Address',
	'CustomPrintOptions' => array(
		'RefNo',
		'Sku',
	),
);

function saveFileForLabel($file_name, $data){
	$byte = base64_decode($data);
    $fd = fopen($file_name, 'wb');
    fwrite($fd, $byte);
    fclose($fd);
	echo "save file" . $file_name;
}

echo "<pre>";

try
{
	if(isset($_GET['api'])){
		$api = $_GET['api'];
		switch ($api) {
			case 'create':
				$response = $ck1_openapi->post('directExpressOrders', json_encode($order));
				break;
			case 'status':
				$response = $ck1_openapi->get('directExpressOrders/' . $packageId . '/status');
				break;
			case 'label':
				$response = $ck1_openapi->post('directExpressOrders/label', json_encode($label));
				if($ck1_openapi->restapi_client->http_code == 200){
					$file_name = "directExpressLabel/package-" . $packageId . "-" . 
						$label['PrintFormat'] . "-" . $label['PrintContent'] . ".pdf";
					saveFileForLabel($file_name, $response["Label"]);
				}
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
		echo "please input ?api=create or ?api=status or ?api=label at the end of url";
	}
}
catch(Exception $e){	
	echo $e;
}

echo "</pre>";


