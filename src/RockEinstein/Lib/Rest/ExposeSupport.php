<?php

namespace RockEinstein\Lib\Rest;

use Doctrine\Common\Annotations\AnnotationReader;
use RockEinstein\Lib\Rest\Formatter\ClassFormatterImp;
use RockEinstein\Lib\Rest\Formatter\DefaultClassFormatter;
use RockEinstein\Lib\Rest\PropertyFormatter;

class ExposeSupport {

    /**
     *
     * @var \RockEinstein\Lib\Rest\ClassFormatter[]
     */
    private static $reflectionCache = array();

    public static function processClass($class) {
        if ($class instanceof \ReflectionClass) {
            $reflection = $class;
        } else {
            $reflection = new \ReflectionClass($class);
        }
        $format = array();
        $reader = new AnnotationReader();
        foreach ($reflection->getProperties() as $property) {
            $annotations = $reader->getPropertyAnnotations($property);
            foreach ($annotations as $annotation) {
                if ($annotation instanceof PropertyFormatter) {
                    $format[$property->getName()] = $annotation;
                    break;
                }
            }
        }
        if (empty($format)) {
            static::$reflectionCache[$reflection->getName()] = new DefaultClassFormatter($reflection);
        } else {
            static::$reflectionCache[$reflection->getName()] = new ClassFormatterImp($reflection, $format);
        }
    }

    /**
     * 
     * @param type $class
     * @return \RockEinstein\Lib\Rest\ClassFormatter
     */
    public static function getClassFormatter($class) {
        if (!array_key_exists($class, static::$reflectionCache)) {
            self::processClass($class);
        }
        return static::$reflectionCache[$class];
    }

    public static function toOutput($model) {
        if (!is_object($model)) {
            return $model;
        }
        $class = get_class($model);
        $classFormatter = static::getClassFormatter($class);
        if (empty($classFormatter)) {
            return $model;
        }
        return $classFormatter->prepareOutput($model);
    }

    public static function toJson($model) {
        return json_encode(static::toOutput($model));
    }

    public static function fromInput(Array $input, $class) {
        $classFormatter = static::getClassFormatter($class);
        return $classFormatter->prepareInput($input);
    }

    public static function fromJson($json, $class) {
        return static::fromInput(json_decode($json, true), $class);
    }

}
