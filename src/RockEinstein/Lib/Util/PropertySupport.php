<?php

namespace RockEinstein\Lib\Util;

use RockEinstein\Lib\Util\Exceptions\ExceptionArray;

trait PropertySupport {

    private $__iterable__ = null;

    public function __set($name, $value) {
        $setMethod = 'set' . ucfirst($name);
        $callable = array($this, $setMethod);
        if (is_callable($callable))
            return call_user_func($callable, $value);
        throw new \InvalidArgumentException('Set method to property(' . $name . ') not found ');
    }

    public function __get($name) {
        $getMethod = 'get' . ucfirst($name);
        $callable = array($this, $getMethod);
        if (is_callable($callable))
            return call_user_func($callable);
        $isMethod = 'is' . ucfirst($name);
        $callable = array($this, $isMethod);
        if (is_callable($callable))
            return call_user_func($callable);
        throw new \InvalidArgumentException('Get method to property(' . $name . ') not found ');
    }

    public function setProperties($properties) {
        $exceptions = array();
        foreach (get_object_vars($this) as $propertyName => $value) {
            if (!array_key_exists($propertyName, $properties)) {
                continue;
            }
            try {
                $this->__set($propertyName, $properties[$propertyName]);
            } catch (\Exception $ex) {
                $exceptions[$propertyName] = $ex;
            }
        }
        if (!empty($exceptions)) {
            throw new ExceptionArray($exceptions);
        }
    }

    public function getProperties() {
        $properties = array();
        foreach (get_object_vars($this) as $propertyName => $value) {
            if ($propertyName[0] == '_') {
                continue;
            }
            try {
                $properties[$propertyName] = $this->__get($propertyName);
            } catch (\Exception $ex) {
                $properties[$propertyName] = $value;
            }
        }
        return $properties;
    }

    public function current() {
        foreach ($this->__iterable__ as $value) {
            return $value;
        }
    }

    public function key() {
        foreach ($this->__iterable__ as $key => $value) {
            return $key;
        }
    }

    public function next() {
        array_shift($this->__iterable__);
    }

    public function rewind() {
        $this->__iterable__ = $this->getProperties();
    }

    public function valid() {
        return !empty($this->__iterable__);
    }

}
