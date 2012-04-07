<?php
/*
 * Copyright (c) 2011 Joao Pinto. All rights reserved.
 */

require getLibFilePath('sn.platform.twitter.TwitterOAuth');

class TwitterAdapter {
	private $tweetObj;
	
	private $consumer_key;
	private $consumer_secret;
	private $access_token;
	
	public function __construct($consumer_key, $consumer_secret) {
		$this->consumer_key = $consumer_key;
		$this->consumer_secret = $consumer_secret;
		
		$this->tweetObj = NULL;
		$this->access_token = NULL;
	}
	
	public function initViaAccessToken($access_token) {
		$this->tweetObj = NULL;
		$this->access_token = NULL;
		
		if (isset($access_token['oauth_token']) && isset($access_token['oauth_token_secret'])) {
			$this->access_token = $access_token;
			
			$tweetObj = $this->initTwitterObj($this->access_token['oauth_token'], $this->access_token['oauth_token_secret']);
			
			if (isset($tweetObj)) {
				$this->tweetObj = $tweetObj;
				
				$content = $this->get("account/verify_credentials");
				if (isset($content->error) && $content->error && isset($content->request) && $content->request) {
					$tweetObj = NULL;
					$this->access_token = NULL;
				}
				else {
					$this->access_token['user_id'] = isset($content->id_str) ? $content->id_str : NULL;
				}
			}
			else {
				$this->access_token = NULL;
			}
		}
		return $this->access_token;
	}
	
	public function initViaUserLogin($username, $password) {
		$tweetObj = $this->initTwitterObj();
		$this->access_token = $tweetObj->getXAuthToken($username, $password);

		if ($tweetObj->http_code == 200 && isset($this->access_token) && is_array($this->access_token)) {
			$tweetObj = $this->initTwitterObj($this->access_token['oauth_token'], $this->access_token['oauth_token_secret']);
			
			if (isset($tweetObj)) {
				$this->tweetObj = $tweetObj;
				
				$content = $this->get("account/verify_credentials");
				if (isset($content->error) && $content->error && isset($content->request) && $content->request) {
					$tweetObj = NULL;
					$this->access_token = NULL;
				}
				else {
					$this->access_token['user_id'] = isset($content->id_str) ? $content->id_str : NULL;
				}
			}
			else {
				$this->access_token = NULL;
			}
		}
		else {
			$this->access_token = NULL;
		}
		return $this->access_token;
	}
	
	public function initViaTwitterLoginFirstStep($callback_url = NULL) {
		/* Build TwitterOAuth object with client credentials. */
		$tweetObj = $this->initTwitterObj();
		 
		/* Get temporary credentials. */
		$request_token = $tweetObj->getRequestToken($callback_url);
		
		/* Save temporary credentials to session. */
		$token = isset($request_token['oauth_token']) ? $request_token['oauth_token'] : NULL;
		$secret = isset($request_token['oauth_token_secret']) ? $request_token['oauth_token_secret'] : NULL;
	 
		/* If last connection failed don't display authorization link. */
		switch ($tweetObj->http_code) {
			case 200:
				/* Build authorize URL and redirect user to Twitter. */
				$url = $tweetObj->getAuthorizeURL($token);
				break;
				
			default:
				/* Show notification if something went wrong. */
				$url = NULL;
		}
		
		return array('oauth_token' => $token, 'oauth_token_secret' => $secret, 'url' => $url);
	}
	
	public function initViaTwitterLoginSecondStep($token, $secret, $verifier) {
		/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
		$tweetObj = $this->initTwitterObj($token, $secret);

		/* Request access tokens from twitter */
		$this->access_token = $tweetObj->getAccessToken($verifier);

		/* If HTTP response is 200 continue otherwise send to connect page to retry */
		if (200 != $tweetObj->http_code) {
			/* The user has been verified and the access tokens can be saved for future use */
			$this->access_token = NULL;
		}
		else {
			$tweetObj = $this->initTwitterObj($this->access_token['oauth_token'], $this->access_token['oauth_token_secret']);
			
			if (isset($tweetObj)) {
				$this->tweetObj = $tweetObj;
				
				$content = $this->get("account/verify_credentials");
				if (isset($content->error) && $content->error && isset($content->request) && $content->request) {
					$tweetObj = NULL;
					$this->access_token = NULL;
				}
				else {
					$this->access_token['user_id'] = isset($content->id_str) ? $content->id_str : NULL;
				}
			}
			else {
				$this->access_token = NULL;
			}
		}
	
		return $this->access_token;
	}
	
	public function isTweetObjValid() {
		return $this->tweetObj != NULL && $this->access_token != NULL;
	}
	
	public function getAccessToken() {
		return $this->access_token;
	}
	
	public function getUserId() {
		return isset($this->access_token['user_id']) ? $this->access_token['user_id'] : NULL;
	}
	
	private function initTwitterObj($token = NULL, $secret = NULL) {
		if (!isset($token) || !isset($secret)) {
			$tweetObj = new TwitterOAuth($this->consumer_key, $this->consumer_secret);
		}
		else {
			$tweetObj = new TwitterOAuth($this->consumer_key, $this->consumer_secret, $token, $secret);
		}
		
		if (!isset($tweetObj)) {
			return NULL;
		}
		return $tweetObj;
	}

	/**
	* GET wrapper for oAuthRequest.
	*/
	public function get($url, $parameters = array(), $format = "json") {
		
		if (isset($this->tweetObj)) {
			if (isset($format) && $format) {
				$format_orig = $this->tweetObj->format;
				$this->tweetObj->format = strtolower($format);
			}
			
			$result = $this->tweetObj->get($url, $parameters);
			$this->tweetObj->format = $format_orig;
			
			return $result;
		}
		return NULL;
	}

	/**
	* POST wrapper for oAuthRequest.
	*/
	public function post($url, $parameters = array(), $format = "json") {
		if (isset($this->tweetObj)) {
			if (isset($format) && $format) {
				$format_orig = $this->tweetObj->format;
				$this->tweetObj->format = strtolower($format);
			}
			
			$result = $this->tweetObj->post($url, $parameters);
			$this->tweetObj->format = $format_orig;
			
			return $result;
		}
		return NULL;
	}

	/**
	* DELETE wrapper for oAuthReqeust.
	*/
	public function delete($url, $parameters = array(), $format = "json") {
		if (isset($this->tweetObj)) {
			if (isset($format) && $format) {
				$format_orig = $this->tweetObj->format;
				$this->tweetObj->format = strtolower($format);
			}
			
			$result = $this->tweetObj->delete($url, $parameters);
			$this->tweetObj->format = $format_orig;
			
			return $result;
		}
		return NULL;
	}
}
?>
