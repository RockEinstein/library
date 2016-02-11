<?php

namespace RockEinstein\Lib\Model;

interface ConnectionProvider{

	public function getConnection($connectionName='default');

}