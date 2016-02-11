<?php

namespace RockEinstein\Lib\Model;

trait PdoSupport{

	public static $pdoProvider;

	private static $pdo;

	private function initPdo(){
		$connectionName = 'default';
		if(isset(static::$connectionName))
			$connectionName = static::$connectionName;
		static::$pdo = PdoSupport::$pdoProvider->getPdo($connectionName);
	}

	public function getPdo(){
		if(!isset(static::$pdo))
			$this->initPdo();
		return static::$pdo;
	}

}