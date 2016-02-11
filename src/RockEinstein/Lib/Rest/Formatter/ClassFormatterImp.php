<?php

namespace RockEinstein\Lib\Rest\Formatter;

use RockEinstein\Lib\Rest\ClassFormatter;

class ClassFormatterImp implements ClassFormatter {

    /**
     * @var \ReflectionClass
     */
    private $reflection;

    /**
     *
     * @var RockEinstein\Lib\Rest\PropertyFormatter[]
     */
    private $propertyFormatters;

    public function __construct(\ReflectionClass $classType, Array $propertyFormatters) {
        $this->reflection = $classType;
        $this->propertyFormatters = $propertyFormatters;
    }

    public function prototype() {
        return $this->reflection->newInstance();
    }

    public function prepareInput($data) {
        $instance = $this->prototype();
        foreach ($this->propertyFormatters as $key => $propertyFormatter) {
            $instance->$key = $propertyFormatter->prepareInput($instance, $key, $data[$key]);
        }
        return $instance;
    }

    public function prepareOutput($instance) {
        $output = array();
        foreach ($this->propertyFormatters as $key => $propertyFormatter) {
            $output[$key] = $propertyFormatter->prepareOutput($instance, $key, $instance->$key);
        }
        return $output;
    }

}
