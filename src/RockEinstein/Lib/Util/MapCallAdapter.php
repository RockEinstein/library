<?php

namespace RockEinstein\Lib\Util;

class MapCallAdapter implements MapCall {

	private $reflection;
	private $target;

	public function __construct($target){
		$this->target = $target;
		$this->reflection = new \ReflectionClass($target);
	}

	public function getReflection(){
		return $this->reflection;
	}

	public function getTarget(){
		return $this->target;
	}

	public function callWithMapArgs($methodName,Array $mapArgs){
		$notFoundParameter = array();
		$method = $this->reflection->getMethod($methodName);
		$orderedArgs = array();
		foreach ($method->getParameters() as $parameter) {
			$parameterName = $parameter->getName();
			if(array_key_exists($parameterName,$mapArgs)){
				$orderedArgs[] = $mapArgs[$parameterName];
				continue;
			}
			try{
				$defaultValue = $parameter->getDefaultValue();
				$orderedArgs[] = $defaultValue;
			}
			catch(\ReflectionException $exception){
				$notFoundParameter[] = $parameterName;
			}
		}
		if(!empty($notFoundParameter)){
			throw new \Exception('Required Parameter(s) not found ('.implode(',',$notFoundParameter).')',400);
		}
		return $method->invokeArgs($this->target,$orderedArgs);
	}

}