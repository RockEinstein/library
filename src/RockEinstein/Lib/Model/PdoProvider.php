<?php

namespace RockEinstein\Lib\Model;

interface PdoProvider{

	public function getPdo($connectionName = 'default');

}