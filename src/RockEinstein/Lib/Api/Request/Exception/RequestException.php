<?php

namespace RockEinstein\Lib\Api\Request\Exception;


abstract class RequestException extends \Exception {

    public function __construct($message, $code = 400) {
        parent::__construct($message, $code);
    }

}
