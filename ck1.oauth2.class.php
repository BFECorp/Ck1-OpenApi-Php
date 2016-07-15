<?php

/**
 * PHP SDK for chukou1.com (using OAuth2)
 * 
 * @author J.Ning <bnjg@qq.com>
 * @contact API <api@chukou1.com>
 * @date 2016.4
 */

include_once('ck1.restapi.executor.class.php');

/**
 * CK1 OAuth2 认证类
 */
class Ck1OAuth2 {
	/**
	 * @ignore
	 */
	public $client_id;
	/**
	 * @ignore
	 */
	public $client_secret;
	/**
	 * @ignore
	 */
	public $base_url;
	/**
	 * @ignore
	 */
	public $redirect_uri;
	/**
	 * @ignore
	 */
	public $access_token;
	/**
	 * @ignore
	 */
	public $refresh_token;
	/**
	 * @ignore
	 */
	public $restapi_client;
	/**
	 * construct object
	 * @param string base_url 对应CK1 OAuth2基地址:(http://openapi.chukou1.cn/oauth2/)
	 * @param string client_id 对应已注册APP的client_id
	 * @param string client_secret 对应已注册APP的client_secret
	 * @param string redirect_uri 对应已注册APP的redirect_uri
	 */
	function __construct($base_url, $client_id, $client_secret, $redirect_uri) {
		$this->base_url = $base_url;
		$this->client_id = $client_id;
		$this->client_secret = $client_secret;
		$this->redirect_uri = $redirect_uri;
		$this->restapi_client = new Ck1RestAPIExecutor($base_url);
	}
	/**
	 * 构建授权的Url，授权成功后，将跳转到RedirectUri并返回code参数
	 * 
	 * 对应URL: (http://openapi.chukou1.cn/oauth2/authorization)
	 * 
	 * @param string $redirect_uri 授权成功后，需要跳转到的URL
	 * @param string $scope 授权的范围，多个scope用逗号分隔，默认值为OpenApi
	 * @param string $response_type 支持的值包括 code 和token 默认值为code，本SDK流程使用code
	 * 注意redirect_uri需要与注册App的RedirectUri同源，否则会返回非同源错误
	 */
	function getAuthorizeURL($redirect_uri, $scope = 'OpenApi', $response_type = 'code') {
		$params = array();
		$params['client_id'] = $this->client_id;
		$params['response_type'] = $response_type;
		$params['scope'] = $scope;
		$params['redirect_uri'] = $redirect_uri;
		return $this->base_url . "authorization" . "?" . http_build_query($params);
	}
	/**
	 * 通过AutorizationCode获取RefreshToken和AccessToken
	 * 
	 * 对应API: (http://openapi.chukou1.cn/oauth2/token)
	 * 
	 * @return array
	 */
	function getToken($code) {
		$params = array();
		$params['client_id'] = $this->client_id;
		$params['client_secret'] = $this->client_secret;
		$params['redirect_uri'] = $this->redirect_uri;
		$params['grant_type'] = 'authorization_code';
		$params['code'] = $code;
		$url = $this->base_url . 'token' . "?" . http_build_query($params);
		$response = $this->restapi_client->oAuthRequest($url, 'POST', $params);
		$token = json_decode($response, true);
		if ( is_array($token) && !isset($token['Message']) ) {
			$this->access_token = $token['AccessToken'];
			$this->refresh_token = $token['RefreshToken'];
		} else {
			throw new Exception("get token failed." . $token['Message']);
		}
		return $token;
	}
	/**
	 * 通过RefreshToken获取AccessToken
	 * 
	 * 对应API: (http://openapi.chukou1.cn/oauth2/token)
	 * 
	 * @return array
	 */
	function getAccessToken($refresh_token) {
		$params = array();
		$params['client_id'] = $this->client_id;
		$params['client_secret'] = $this->client_secret;
		$params['redirect_uri'] = $this->redirect_uri;
		$params['grant_type'] = 'refresh_token';
		$params['refresh_token'] = $refresh_token;
		$url = $this->base_url . 'token' . "?" . http_build_query($params);
		$response = $this->restapi_client->oAuthRequest($url, 'POST', $params);
		$token = json_decode($response, true);
		if ( is_array($token) && !isset($token['Message']) ) {
			$this->access_token = $token['AccessToken'];
			$this->refresh_token = $token['RefreshToken'];
		} else {
			throw new Exception("get access token failed." . $token['Message']);
		}
		return $token;
	}
}