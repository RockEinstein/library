<?php

namespace RockEinstein\Lib\Client;

use RockEinstein\Lib\Client\DataProvider;

class RestModel {

    /**
     *
     * @var RockEinstein\Lib\Client\DataProvider
     */
    private $__dataProvider__;

    /**
     *
     * @var RockEinstein\Lib\Client\DataProvider[]
     */
    private $__hasMany__ = array();

    /**
     *
     * @var RockEinstein\Lib\Client\DataProvider[]
     */
    private $__hasOne__ = array();

    /**
     *
     * @var Array
     */
    private $__changes__;

    /**
     *
     * @var Array
     */
    private $__data__;

    public function __construct(DataProvider $dataProvider, $data, $hasOne = array(), $hasMany = array()) {
        $this->__dataProvider__ = $dataProvider;
        $this->__data__ = is_array($data) ? $data : array('id' => $data);
        $this->__changes__ = array();
        $this->__hasOne__ = $hasOne;
        $this->__hasMany__ = $hasMany;
    }

    public function update() {
        $values = $this->__dataProvider__->update($this->__data__['id'], $this->__changes__);
        $this->__data__ = (array) $values;
        $this->__changes__ = array();
    }

    public function delete() {
        $this->__dataProvider__->delete($this->__data__['id']);
    }

    public function insert() {
        $values = $this->__dataProvider__->insert($this->__data__);
        $this->__changes__ = array();
        $this->__data__ = (array) $values;
    }

    public function __set($name, $value) {
        if (!array_key_exists($name, $this->__data__)) {
            $this->__data__[$name] = $value;
            $this->__changes__[$name] = $value;
            return $value;
        }
        if ($this->__data__[$name] != $value) {
            $this->__changes__[$name] = $value;
        }
        return $this->__data__[$name] = $value;
    }

    private function getHasManyFiled($name) {
        $value = $this->__data__[$name];
        if (empty($value)) {
            return $value;
        }
        if (!is_array($value)) {
            return $value;
        }
        $newValue = array();
        $dataProvider = $this->__hasMany__[$name];
        foreach ($value as $objOrId) {
            if (is_object($objOrId)) {
                $newValue[] = $objOrId;
                continue;
            }
            $newValue[] = $dataProvider->findOneById($objOrId);
        }
        $this->__data__[$name] = $newValue;
        return $newValue;
    }

    private function getHasOneField($name) {
        $value = $this->__data__[$name];
        if (is_null($value)) {
            return $value;
        }
        if (is_object($value)) {
            return $value;
        }
        $dataProvider = $this->__hasOne__[$name];
        $value = $dataProvider->findOneById($value);
        $this->__data__[$name] = $value;
        return $value;
    }

    public function __get($name) {
        if (array_key_exists($name, $this->__hasMany__)) {
            return $this->getHasManyFiled($name);
        }
        if (array_key_exists($name, $this->__hasOne__)) {
            return $this->getHasOneField($name);
        }
        if (array_key_exists($name, $this->__data__)) {
            return $this->__data__[$name];
        }
        return null;
    }
    
    public function getDataArray(){
        if(empty($this->__data__)){
            return [];
        }
        
        return $this->__data__;
    }

}
