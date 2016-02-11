<?php

namespace RockEinstein\Lib\Api\Controller\Exception;


class RequiredParameterNotFoundException extends ControllerException {

    private $parameter;

    public function __construct($source, $parameter) {
        parent::__construct($source,400);
        $this->parameter = $parameter;
    }

}
