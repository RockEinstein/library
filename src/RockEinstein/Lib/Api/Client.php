<?php

namespace RockEinstein\Lib\Api;

class Client {

	public $url;
	public $header     = array('Accept' => 'application/json', 'Content-Type' => 'application/json');
	public $urlParams  = array();
	public $bodyParams = array();

	public static function makeLocalClient($project, $resource) {
		$client      = new Client();
		$client->url = 'http://127.0.0.1/' . $project . '/' . $resource;
		return $client;
	}

	public function prepareUrl($moreParams = array()) {
		$params = array_merge($this->urlParams, $moreParams);
		$suffix = '';
		foreach ($params as $name => $value) {
			if (is_numeric($name)) {
				continue;
			}

			$suffix .= '/' . urlencode($name) . '/' . urlencode((string) $value);
		}
		return $this->url . $suffix;
	}

	public function prepareHeader($moreHeaders = array()) {
		$headers = array_merge($this->header, $moreHeaders);
		$h       = array();
		foreach ($headers as $name => $value) {
			if (is_numeric($name)) {
				$h[] = (string) $value;
				continue;
			}
			$h[] = $name . ': ' . (string) $value;
		}
		return $h;
	}

	public function preprareJsonBody($moreParams = array()) {
		$body = array_merge($this->bodyParams, $moreParams);
		if (empty($body)) {
			return '{}';
		}

		$body = json_encode($body);
		return $body;
	}

	public function get($urlParams = array()) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->prepareUrl($urlParams));
		curl_setopt($curl, CURLOPT_HTTPHEADER, $this->prepareHeader());
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,2);
		$result = curl_exec($curl);
		if ($result === false) {
			throw new \Exception(curl_error($curl));
		}

		$response = json_decode($result, true);
		if ($response['exception']) {
			throw new \Exception($response['message']);
		}

		return $response;
	}

	public function post($urlParams = array(), $bodyParams = array()) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->prepareUrl($urlParams));
		curl_setopt($curl, CURLOPT_HTTPHEADER, $this->prepareHeader());
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $this->preprareJsonBody($bodyParams));
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,2);
		$result = curl_exec($curl);
		if ($result === false) {
			throw new \Exception(curl_error($curl));
		}

		$response = json_decode($result, true);
		if ($response['exception']) {
			throw new \Exception($response['message']);
		}

		return $response;
	}

	public function put($urlParams = array(), $bodyParams = array()) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->prepareUrl($urlParams));
		curl_setopt($curl, CURLOPT_HTTPHEADER, $this->prepareHeader());
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $this->preprareJsonBody($bodyParams));
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,2);
		$result = curl_exec($curl);
		if ($result === false) {
			throw new \Exception(curl_error($curl));
		}

		$response = json_decode($result, true);
		if ($response['exception']) {
			throw new \Exception($response['message']);
		}

		return $response;
	}

	public function delete($urlParams = array()) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->prepareUrl($urlParams));
		curl_setopt($curl, CURLOPT_HTTPHEADER, $this->prepareHeader());
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,2);
		$result = curl_exec($curl);
		if ($result === false) {
			throw new \Exception(curl_error($curl));
		}

		$response = json_decode($result, true);
		if ($response['exception']) {
			throw new \Exception($response['message']);
		}

		return $response;
	}

}