<?php

namespace RockEinstein\Lib\Model;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class EntityManagerProviderImp implements EntityManagerProvider{

	private $devMode;
	private $modelsPaths;
	private $connectionProvider;
	private $cache = array();	

	public function __construct($modelsPaths,ConnectionProvider $connectionProvider,$devMode=false){
		$this->devMode = $devMode;
		$this->modelsPaths = $modelsPaths;
		$this->connectionProvider = $connectionProvider;
	}

	public function getEntityManager($connectionName = 'default'){
		if(array_key_exists($connectionName,$this->cache))
			return $this->cache[$connectionName];
		$connection = $this->connectionProvider->getConnection($connectionName);
		$config = Setup::createAnnotationMetadataConfiguration($this->modelsPaths,$this->devMode);
		$entityManager = EntityManager::create($connection,$config);
		$this->cache[$connectionName] = $entityManager;
		return $entityManager;
	}

}