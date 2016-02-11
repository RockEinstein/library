<?php

namespace RockEinstein\Lib\Rest\Formatter;

use RockEinstein\Lib\Rest\ExposeSupport;

class DefaultClassFormatter {

    /**
     * @var \ReflectionClass
     */
    private $reflection;

    public function __construct(\ReflectionClass $classType) {
        $this->reflection = $classType;
    }

    public function prototype() {
        return $this->reflection->newInstance();
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function prepareInput($data) {
        $instance = $this->prototype();
        foreach ($instance as $attr => $value) {
            if (!array_key_exists($attr, $data)) {
                continue;
            }
            $instance->$attr = $data[$attr];
        }
        return $instance;
    }

    /**
     * @param type $instance
     * @return array formatted properties
     */
    public function prepareOutput($instance) {
        $output = array();
        foreach ($instance as $attr => $value) {
            if ($value instanceof \DateTime) {
                $output[$attr] = $value->format(\DateTime::ISO8601);
                continue;
            }
            if ($value instanceof Doctrine\Common\Collections\Collection) {
                $array = array();
                foreach ($value->toArray() as $e) {
                    $array[] = is_object($e) ? $e->id : $e;
                }
                $output[$attr] = $array;
                continue;
            }
            if ($value instanceof \Doctrine\ORM\Proxy\Proxy) {
                $output[$attr] = $value->id;
                continue;
            }
            if (is_object($value)) {
                $output[$attr] = ExposeSupport::toOutput($value);
                continue;
            }
            $output[$attr] = $value;
        }
        try {
            $id = $instance->id;
            if (isset($id)) {
                $output['id'] = $id;
            }
        } catch (\Exception $ex) {
            
        }
        return $output;
    }

}
