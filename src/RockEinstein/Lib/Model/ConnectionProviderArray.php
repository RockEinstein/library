<?php

namespace RockEinstein\Lib\Model;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

class ConnectionProviderArray implements ConnectionProvider,PdoProvider {

	private $configArray;

	private $currentContext;

	private $currentConnections;


	public static function makeFromArrayFile($phpFile){
		if(!file_exists($phpFile))
			throw new \InvalidArgumentException();
		$configArray = require $phpFile;
		return new ConnectionProviderArray($configArray);
	}

	public static function makeFromJsonFile($jsonFile){
		if(!file_exists($jsonFile))
			throw new \InvalidArgumentException();
		$json = json_decode(file_get_contents($jsonFile),true);
		if(empty($json))
			throw new \InvalidArgumentException();
		return new ConnectionProviderArray($json);
	}


	public function __construct(Array $configArray){
		$this->configArray = $configArray;
		foreach($configArray as $context => $connections){
			$this->currentContext = $context;
			$this->currentConnections = $connections;
			break;
		}
	}

	public function getContext(){
		return $this->currentContext;
	}

	public function setContext($context){
		if(!in_array($context,array_keys($this->configArray)))
			throw new \InvalidArgumentException();
		$this->currentContext = $context;
		$this->currentConnections = $this->configArray[$context];
	}

	public function getConnection($connectionName = 'default'){
		if(!in_array($connectionName,array_keys($this->currentConnections)))
			throw new \InvalidArgumentException();
		return $this->currentConnections[$connectionName];
	}

	public function listConnections(){
		return array_keys($this->currentConnections);
	}

	private $pdoCache = array();

	public function getPdo($connectionName = 'default'){
		if(isset($this->pdoCache[$connectionName])){
			return $this->pdoCache[$connectionName];
		}
		$config = new Configuration();
		$pdo = DriverManager::getConnection($this->getConnection($connectionName),$config);
		$this->pdoCache[$connectionName] = $pdo;
		return $pdo;
	}

}