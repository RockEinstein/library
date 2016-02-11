<?php

namespace RockEinstein\Lib\Api;

interface ControllerProvider {

	public function getController($resource);

}