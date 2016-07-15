<?php

/**
 * PHP SDK for chukou1.com (using OAuth2)
 * 
 * @author J.Ning <bnjg@qq.com>
 * @contact API <api@chukou1.com>
 * @date 2016.4
 */

/* config for api */
$base_url = 'https://openapi-release.chukou1.cn/v1/';
$access_token = 'YmE4MzBmN2EtMzJkMi00MjJkLWEyZjktZTdlODkyMDJhMWY1';

/* config for oauth2 */
$oauth2_base_url = 'https://openapi-release.chukou1.cn/oauth2/';
$client_id = 'your app client_id';
$client_secret = 'your app client_secret';
$redirect_uri = 'your app redirect_uri';
$return_code_redirect_uri = 'http://{your domain}/return_sample.php';	//注意redirect_uri需要与注册App的RedirectUri同源，否则会返回非同源错误

/* config for webpage */
header("Content-Type: text/html; charset=UTF-8");
