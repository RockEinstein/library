<?php

namespace RockEinstein\Lib\Rest;

interface ClassFormatter {

    /**
     * @param array $data
     * @return mixed
     */
    public function prepareInput($data);

    /**
     * @param type $instance
     * @return array formatted properties
     */
    public function prepareOutput($instance);
}
