<?php



namespace RockEinstein\Lib\Api\Controller\Exception;

use \Exception;

abstract class ControllerException extends Exception  {

    protected $source;

    public function __construct($source,$code) {
        parent::__construct('ControllerException('. get_class($source) . ')',$code);
        $this->source = $source;
    }

    public function getSource() {
        return $this->source;
    }

}
