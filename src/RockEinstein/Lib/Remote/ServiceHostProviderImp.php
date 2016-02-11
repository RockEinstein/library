<?php

namespace RockEinstein\Lib\Remote;

class ServiceHostProviderImp implements ServiceHostProvider{

	private $routes;
	private $cache =array();


	public static function makeFromArrayFile($arrayFile){
		$routes = require $arrayFile;
		return new ServiceHostProviderImp($routes);
	}

	public function __construct(Array $routes){
		$this->routes = $routes;
	}

	protected function findHost($serviceName){
		foreach ($this->routes as $regex => $host) {
			if(!preg_match($regex,$serviceName)){
				continue;
			}
			if(is_string($host))
				return $host;
			if(is_array($host))
				return array_rand($host);
			return (string)$host;
		}
		throw new \Exception('Host do Serviço('.$serviceName.') não encontrado');
	}

	public function getHost($serviceName){
		if(array_key_exists($serviceName,$this->cache)){
			return $this->cache[$serviceName];
		}
		$host = $this->findHost($serviceName);
		$this->cache[$serviceName] = $host;
		return $host;
	}	

}