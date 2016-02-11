<?php

namespace RockEinstein\Lib\Util;

interface MapCall {

    /**
     * @param string $methodName
     * @param array $mapArgs
     */
    public function callWithMapArgs($methodName, Array $mapArgs);
}
