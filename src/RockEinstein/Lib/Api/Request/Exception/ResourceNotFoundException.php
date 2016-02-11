<?php



namespace RockEinstein\Lib\Api\Request\Exception;


class ResourceNotFoundException extends RequestException{

    function __construct($resource) {
        parent::__construct('Resource (' . $resource . ') not found', 404);
    }

}
