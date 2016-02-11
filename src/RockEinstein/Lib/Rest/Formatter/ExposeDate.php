<?php

namespace RockEinstein\Lib\Rest\Formatter;

use Doctrine\Common\Annotations\Annotation;
use RockEinstein\Lib\Rest\PropertyFormatter;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class ExposeDate extends Annotation implements PropertyFormatter {

    public $format = \DateTime::ISO8601;

    public function prepareInput($instance, $property, $value) {
        return \DateTime::createFromFormat($this->format, $value);
    }

    public function prepareOutput($instance, $property, $value) {
        if ($value instanceof \DateTime) {
            return $value->format($this->format);
        }
        return $value;
    }

}
