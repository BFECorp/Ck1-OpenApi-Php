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
 * CK1 OpenApiV1 业务操作类
 */
class Ck1OpenApiV1 {
	/**
	 * @ignore
	 */
	public $access_token;
	/**
	 * @ignore
	 */
	public $base_url;
	/**
	 * @ignore
	 */
	public $restapi_client;
	/**
	 * construct object
	 */
	function __construct($base_url, $access_token) {
		$this->base_url = $base_url;
		$this->access_token = $access_token;
		$this->restapi_client = new Ck1RestAPIExecutor($base_url, $access_token);
	}		

	/**
	 * GET wrappwer for oAuthRequest.
	 *
	 * @return mixed
	 */
	function get($resource, $params = array()) {
		return $this->restapi_client->get($resource, $params);
	}
	
	/**
	 * POST wreapper for oAuthRequest.
	 *
	 * @return mixed
	 */
	function post($resource, $params = array()) {
		return $this->restapi_client->post($resource, $params);
	}
	
}