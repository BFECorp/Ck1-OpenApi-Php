<?php

/**
 * PHP SDK for chukou1.com (using OAuth2)
 * 
 * @author J.Ning <bnjg@qq.com>
 * @contact API <api@chukou1.com>
 * @date 2016.4
 * 
 * References PHP SDK for weibo.com (using OAuth2)
 * https://github.com/xiaosier/libweibo/blob/master/saetv2.ex.class.php
 */

/**
 * CK1 RestAPIExecutor 请求执行类
 */
class Ck1RestAPIExecutor {
	/**
	 * @ignore
	 */
	public $base_url;
	/**
	 * @ignore
	 */
	public $access_token;
	/**
	 * Set timeout default.
	 *
	 * @ignore
	 */
	public $timeout = 30;
	/**
	 * Set connect timeout.
	 *
	 * @ignore
	 */
	public $connecttimeout = 30;
	/**
	 * Verify SSL Cert.
	 *
	 * @ignore
	 */
	public $ssl_verifypeer = FALSE;
	/**
	 * Respons format.
	 *
	 * @ignore
	 */
	public $format = 'json';
	/**
	 * Decode returned json data.
	 *
	 * @ignore
	 */
	public $decode_json = TRUE;
	/**
	 * Contains the last HTTP status code returned. 
	 *
	 * @ignore
	 */
	public $http_code;
	/**
	 * Contains the last API call.
	 *
	 * @ignore
	 */
	public $url;
	/**
	 * Contains the last HTTP headers returned.
	 *
	 * @ignore
	 */
	public $http_info;
	/**
	 * Set the useragnet.
	 *
	 * @ignore
	 */
	public $useragent = 'CK1 OpenApi SDK/1.0(PHP)';
	/**
	 * print the debug info
	 *
	 * @ignore
	 */
	public $debug = FALSE;
	/**
	 * construct object
	 */
	function __construct($base_url, $access_token = NULL) {
		$this->base_url = $base_url;
		$this->access_token = $access_token;
	}
	/**
	 * GET wrappwer for oAuthRequest.
	 *
	 * @return mixed
	 */
	function get($resource, $parameters = array()) {
		$url = $this->base_url . $resource;
		$response = $this->oAuthRequest($url, 'GET', $parameters);
		return json_decode($response, true);
		if ($this->format === 'json' && $this->decode_json) {
			return json_decode($response, true);
		}
		return $response;
	}
	/**
	 * POST wreapper for oAuthRequest.
	 *
	 * @return mixed
	 */
	function post($resource, $parameters = array()) {
		$url = $this->base_url . $resource;
		$response = $this->oAuthRequest($url, 'POST', $parameters);
		if ($this->format === 'json' && $this->decode_json) {
			return json_decode($response, true);
		}
		return $response;
	}
	/**
	 * Format and sign an OAuth / API request
	 *
	 * @return string
	 * @ignore
	 */
	function oAuthRequest($url, $method, $parameters) {
		switch ($method) {
			case 'GET':
				$url = $url . '?' . http_build_query($parameters);
				return $this->http($url, 'GET');
			default:
				$headers = array();
				$headers[] = "Content-Type: application/json; charset=utf-8";
				if (is_array($parameters) || is_object($parameters)) {
					$body = http_build_query($parameters);
				}
				else if($parameters !== ''){
					$body = $parameters;
				}
				return $this->http($url, $method, $body, $headers);
		}
	}
	/**
	 * Make an HTTP request
	 *
	 * @return string API results
	 * @ignore
	 */
	function http($url, $method, $postfields = NULL, $headers = array()) {
		$this->http_info = array();
		$ci = curl_init();
		/* Curl settings */
		curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
		curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ci, CURLOPT_ENCODING, "utf-8");
		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
		if (version_compare(phpversion(), '5.4.0', '<')) {
			curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, 1);
		} else {
			curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, 2);
		}
		//curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));
		curl_setopt($ci, CURLOPT_HEADER, FALSE);
		switch ($method) {
			case 'POST':
				curl_setopt($ci, CURLOPT_POST, TRUE);
				if (!empty($postfields)) {
					curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
					//$this->postdata = $postfields;
				}
				break;
		}
		if ( isset($this->access_token) && $this->access_token ){
			$headers[] = "Authorization: Bearer ".$this->access_token;
		}
		$headers[] = "API-RemoteIP: " . $_SERVER['REMOTE_ADDR'];
		curl_setopt($ci, CURLOPT_URL, $url );
		curl_setopt($ci, CURLOPT_HTTPHEADER, $headers );
		curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE );
		$response = curl_exec($ci);
		$this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
		$this->http_info = array_merge($this->http_info, curl_getinfo($ci));
		$this->url = $url;
		if ($this->debug) {
			echo "=====post data======\r\n";
			var_dump($postfields);
			echo "=====headers======\r\n";
			print_r($headers);
			echo '=====request info====='."\r\n";
			print_r( curl_getinfo($ci) );
			echo '=====response====='."\r\n";
			print_r( $response );
		}
		curl_close ($ci);
		return $response;
	}
}