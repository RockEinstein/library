<?php

namespace RockEinstein\Lib\Util;

trait MapCallSupport {

	public function callWithMapArgs($methodName,Array $mapArgs){
		$adapter = new MapCallAdapter($this);
		return $adapter->callWithMapArgs($methodName,$mapArgs);
	}

}