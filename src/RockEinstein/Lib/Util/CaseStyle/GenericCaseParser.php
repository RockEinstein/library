<?php

namespace RockEinstein\Lib\Util\CaseStyle;

class GenericCaseParser implements CaseParser {

    /**
     *
     * @var string
     */
    private $preffix;

    /**
     *
     * @var string
     */
    private $suffix;

    /**
     *
     * @var string
     */
    private $join;

    /**
     * @var callable
     */
    private $atomPreparer;

    /**
     *
     * @var callable
     */
    private $endPreparer;

    /**
     * 
     * @param string $toExplode
     * @return Array
     */
    protected function explode($toExplode) {
        $toExplode = preg_replace('@([a-z])([A-Z])@', '\1-\2', $toExplode);
        $explode = preg_split('@(?!^)[\\-_]+@', $toExplode, -1, PREG_SPLIT_NO_EMPTY);
        $explodeName = array_map('strtolower', $explode);
        return $explodeName;
    }

    /**
     * 
     * @param string $toParse
     * @return string
     */
    protected function parseString($toParse) {
        $explode = $this->explode($toParse);
        $preparedExplode = (array_map($this->atomPreparer, $explode));
        return $this->preffix . call_user_func($this->endPreparer, (implode($this->join, $preparedExplode))) . $this->suffix;
    }

    /**
     * 
     * @param array $toParse
     * @return array
     */
    protected function parseArray(Array $toParse) {
        $newArray = array();
        foreach ($toParse as $key => $value) {
            $newKey = is_numeric($key) ? $key : $this->parseString($key);
            $newValue = is_array($value) ? $this->parseArray($value) : $value;
            $newArray[$newKey] = $newValue;
        }
        return $newArray;
    }

    /**
     * 
     * @param mixed $toParse
     * @return mixed
     */
    public function parse($toParse) {
        if (is_string($toParse)) {
            return $this->parseString($toParse);
        }
        if (is_array($toParse)) {
            return $this->parseArray($toParse);
        }
        return $toParse;
    }

    /**
     * 
     * @param mixed $toParse
     * @return mixed
     */
    public function __invoke($toParse) {
        return $this->parse($toParse);
    }

    /**
     * 
     * @param callable $atomPreparer
     * @param callable $endPreparer
     * @param string $join
     * @param string $preffix
     * @param string $suffix
     */
    function __construct($atomPreparer, $endPreparer, $join, $preffix, $suffix) {
        $this->preffix = $preffix;
        $this->suffix = $suffix;
        $this->join = $join;
        $this->atomPreparer = $atomPreparer;
        $this->endPreparer = $endPreparer;
    }

}
