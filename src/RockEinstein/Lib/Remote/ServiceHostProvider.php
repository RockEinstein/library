<?php

namespace RockEinstein\Lib\Remote;

interface ServiceHostProvider{

	public function getHost($serviceName);

}