<?php


namespace RockEinstein\Lib\Api\Request\Exception;


class UnauthorizedException extends RequestException {

    function __construct($resource, $method) {
        parent::__construct('Access resource(' . $resource . ') with method(' . $method . ') unauthorized', 401);
    }

}
