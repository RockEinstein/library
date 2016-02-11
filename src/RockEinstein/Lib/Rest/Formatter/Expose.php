<?php

namespace RockEinstein\Lib\Rest\Formatter;

use Doctrine\Common\Annotations\Annotation;
use RockEinstein\Lib\Rest\PropertyFormatter;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class Expose extends Annotation implements PropertyFormatter {

    public $inputFormat = null;
    public $outputFormat = null;

    public function prepareInput($instance, $property, $value) {
        $format = $this->inputFormat;
        if (empty($format)) {
            return $value;
        }
        if (is_callable($format)) {
            return call_user_func($format, $value);
        }
        if (is_callable(array($instance, $format))) {
            return call_user_func(array($instance, $format), $value);
        }
        if (is_string($format)) {
            $result = null;
            eval('$result = ' . sprintf($format, $value)).';';
            return $result;
        }
        throw new \Exception();
    }

    public function prepareOutput($instance, $property, $value) {
        $format = $this->outputFormat;
        if (empty($format)) {
            return $value;
        }
        if (is_callable($format)) {
            return call_user_func($format, $value);
        }
        if (is_callable(array($instance, $format))) {
            return call_user_func(array($instance, $format), $value);
        }
        if (is_string($format)) {
            $result = null;
            eval('$result = ' . sprintf($format, $value) . ';');
            return $result;
        }
        throw new \Exception();
    }

}
