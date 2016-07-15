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
	'Sku' => 'a10001',
	'ProductName' => 'test product',
	'ProductDescription' => 'my product description',
	'Weight' => '100',
	'Length' => '10',
	'Width' => '5',
	'Height' => '0.2',
	'DeclareName' => 'phone',
	'DeclareValue' => '150',
	'ProductFlag' => 'Simple',
	'ProductCategory' => 'phone',
	'ProductRemark' => 'for testing',
);

$label = array(
	'MerchantId' => '',
	'WarehouseId' => 'US',
	'Sku' => 'a10001',
	'Quantity' => '10',
	'PrintFormat' => 'ClassicA4',
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
				$response = $ck1_openapi->post('merchantSkus', json_encode($sku));
				break;
			case 'label':
				$response = $ck1_openapi->get('merchantSkus/label', $label);
				if($ck1_openapi->restapi_client->http_code == 200){
					$file_name = "skuLabel/sku-" . $label['WarehouseId'] . "-" . 
						$label['Sku'] . "-" . $label['PrintFormat'] . ".pdf";
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
		echo "please input ?api=create or ?api=label at the end of url";
	}
}
catch(Exception $e){	
	echo $e;
}

echo "</pre>";


