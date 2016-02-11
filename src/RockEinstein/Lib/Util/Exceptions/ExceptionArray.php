<?php

namespace RockEinstein\Lib\Util\Exceptions;

class ExceptionArray extends \Exception {

    public $exceptions;

    /**
     * @param \Exception[] $exceptions
     */
    public function __construct(Array $exceptions) {
        parent::__construct();
        $this->exceptions = $exceptions;
    }

}
