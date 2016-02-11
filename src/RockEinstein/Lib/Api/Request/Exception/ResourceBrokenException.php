<?php

namespace RockEinstein\Lib\Api\Request\Exception;

class ResourceBrokenException extends RequestException{
    
    public function __construct($resouce,$controller) {
        parent::__construct('The resource ('.$resouce.') is broken ('.  get_class($controller).')', 501);
    }
}
