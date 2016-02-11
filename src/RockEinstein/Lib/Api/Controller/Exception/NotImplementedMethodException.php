<?php

namespace RockEinstein\Lib\Api\Controller\Exception;

use RockEinstein\Lib\Api\Controller\Exception\ControllerException;


class NotImplementedMethodException extends ControllerException {

    private $className;
    private $methodName;
    private $methodArgs;

    public function __construct($source, $methodName, Array $args) {
        parent::__construct($source,404);
        $this->className = get_class($source);
        $this->methodName = $methodName;
        $this->methodArgs = $args;
    }

}
