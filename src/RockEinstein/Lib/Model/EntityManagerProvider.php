<?php

namespace RockEinstein\Lib\Model;

interface EntityManagerProvider{

	public function getEntityManager($connectionName = 'default');

}