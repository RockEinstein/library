<?php

namespace RockEinstein\Lib\Util\CaseStyle;

class CaseParserFactory {

    private static $instance = null;

    public static function getInstance() {
        if (static::$instance == null) {
            static::$instance = new CaseParserFactory();
        }
        return static::$instance;
    }

    private function __construct() {
        
    }

    public static function __callStatic($name, $arguments) {
        return call_user_func_array(array(static::getInstance(), $name), $arguments);
    }

    /**
     * 
     * @return \RockEinstein\Lib\Util\CaseStyle\CaseParser
     */
    public function makeUpperCamelCaseParser() {
        $atomPreparer = function($atom) {
            return ucfirst(strtolower($atom));
        };
        $allPreparer = function($all) {
            return $all;
        };
        $join = '';
        $preffix = '';
        $suffix = '';
        return new GenericCaseParser($atomPreparer, $allPreparer, $join, $preffix, $suffix);
    }

    /**
     * 
     * @return \RockEinstein\Lib\Util\CaseStyle\CaseParser
     */
    public function makeLowerCamelCaseParser() {
        $atomPreparer = function($atom) {
            return ucfirst(strtolower($atom));
        };
        $allPreparer = function($all) {
            return lcfirst($all);
        };
        $join = '';
        $preffix = '';
        $suffix = '';
        return new GenericCaseParser($atomPreparer, $allPreparer, $join, $preffix, $suffix);
    }

    /**
     * 
     * @return \RockEinstein\Lib\Util\CaseStyle\CaseParser
     */
    public function makeLowerUnderscoreCaseParser() {
        $atomPreparer = function($atom) {
            return strtolower($atom);
        };
        $allPreparer = function($all) {
            return $all;
        };
        $join = '_';
        $preffix = '';
        $suffix = '';
        return new GenericCaseParser($atomPreparer, $allPreparer, $join, $preffix, $suffix);
    }

    /**
     * 
     * @return \RockEinstein\Lib\Util\CaseStyle\CaseParser
     */
    public function makeUpperUnderscoreCaseParser() {
        $atomPreparer = function($atom) {
            return strtoupper($atom);
        };
        $allPreparer = function($all) {
            return $all;
        };
        $join = '_';
        $preffix = '';
        $suffix = '';
        return new GenericCaseParser($atomPreparer, $allPreparer, $join, $preffix, $suffix);
    }

    /**
     * 
     * @return \RockEinstein\Lib\Util\CaseStyle\CaseParser
     */
    public function makeHttpHeadCaseParser() {
        $atomPreparer = function($atom) {
            return ucfirst(strtolower($atom));
        };
        $allPreparer = function($all) {
            return $all;
        };
        $join = '-';
        $preffix = '';
        $suffix = '';
        return new GenericCaseParser($atomPreparer, $allPreparer, $join, $preffix, $suffix);
    }

}
