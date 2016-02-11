<?php

namespace RockEinstein\Lib\Api;

use RockEinstein\Lib\Api\Controller\AbstractController;
use RockEinstein\Lib\Api\Request\Exception\ResourceBrokenException;
use RockEinstein\Lib\Api\Request\Exception\ResourceNotFoundException;

class ControllerProviderImp implements ControllerProvider,RoutesProvider{

	private $controllersNamespaces;
	private $routes = array();

	public static function makeFromArrayFile($file){
		if(!file_exists($file))
			throw new \InvalidArgumentException();
		$arrayFile = require $file;
		if(empty($arrayFile))
			throw new \InvalidArgumentException();
		return new ControllerProviderImp(array(),$arrayFile);
	}

	public function __construct(Array $namespaces,Array $routes){
		$this->controllersNamespaces = $namespaces;
		foreach ($routes as $key => $route) {
			if(is_numeric($key)){
				$this->controllersNamespaces[] = $route;
				continue;
			}
			$this->routes[$key] = $route;
		}
	}

	private function toClassName($resource){
		$explode = preg_split('@[_ -]+@',$resource,-1,PREG_SPLIT_NO_EMPTY);
		return implode(array_map('ucfirst',$explode));
	}

	public function getController($resource){
		if(array_key_exists($resource,$this->routes)){
			$controllerName = $this->routes[$resource];
			if(class_exists($controllerName)){
				return new $controllerName();
			}
			foreach ($this->controllersNamespaces as $namespace) {
				$className = $namespace.$controllerName;
				if(class_exists($className)){
					return new $className();
				}
			}
		}
		$controllerName = $this->toClassName($resource);
		if(class_exists($controllerName)){
			return new $controllerName();
		}
		foreach ($this->controllersNamespaces as $namespace) {
			$className = $namespace.$controllerName;
			if(class_exists($className)){
				return new $className();
			}
		}
		throw new \Exception('Controller não Encontrada (resource = '.$resource.')');
	}

	public function getRoute($route) {
		try{
			$controller = $this->getController($route);
		}
		catch(\Exception $ex){
			throw new ResourceNotFoundException($route);
		}
		if(!$controller instanceof AbstractController){
			throw new ResourceBrokenException($route, $controller);
		}
		return $controller;
	}

}