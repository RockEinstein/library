<?php

namespace RockEinstein\Lib\Rest;

interface PropertyFormatter {

    /**
     * @param type $instance
     * @param string $property
     * @param string $value
     * @return mixed 
     */
    public function prepareInput($instance,$property,$value);
    
    /**
     * @param type $instance
     * @param string $property
     * @param mixed $value
     * @return string formatted value
     */
    public function prepareOutput($instance,$property,$value);
    
}
