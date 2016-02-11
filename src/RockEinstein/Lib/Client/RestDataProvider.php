<?php

namespace RockEinstein\Lib\Client;

use RockEinstein\Lib\Client\DataProvider;
use RockEinstein\Lib\Remote\CurlHelper;
use RockEinstein\Lib\Client\RestModel;

class RestDataProvider implements DataProvider {

    /**
     * @var stdClass[]
     */
    private $cache = array();

    /**
     *
     * @var RockEinstein\Lib\Remote\CurlHelper;
     */
    private $curl;

    /**
     *
     * @var \RockEinstein\Lib\Client\DataProvider[]
     */
    public $hasOne;

    /**
     *
     * @var \RockEinstein\Lib\Client\DataProvider[]
     */
    public $hasMany;

    /**
     *
     * @param type $url
     * @param \RockEinstein\Lib\Client\DataProvider[] $hasOne
     * @param \RockEinstein\Lib\Client\DataProvider[] $hasMany
     */
    public function __construct($url, $hasOne = array(), $hasMany = array()) {

        $this->curl = CurlHelper::makeJsonCurl($url);
        $this->hasMany = $hasMany;
        $this->hasOne = $hasOne;
    }

    public function setRequestHeaders($headers) {

        foreach ($headers as $header => $value) {
            $this->curl->headerParameters[$header] = $value;
        }
    }

    /**
     *
     * @return RestModel[]
     */
    public function findAll($processData = true) {

        $all = $this->curl->get();
        if (!$processData) {
            return [$all];
        }
        $models = array();
        foreach ($all as $data) {
            $models[] = new RestModel($this, (array)$data, $this->hasOne, $this->hasMany);
            $this->cache[$data->id] = $data;
        }

        return $models;
    }

    /**
     *
     * @param Array $filter
     * @return RestModel[]
     */
    public function findBy($filter = array(), $processData = true) {
        $all = $this->curl->get(array(), $filter);
        if (!$processData) {
            return [$all];
        }
        $models = array();
        foreach ($all as $data) {
            $models[] = new RestModel($this, (array)$data, $this->hasOne, $this->hasMany);
            $this->cache[$data->id] = $data;
        }

        return $models;
    }

    /**
     *
     * @param type $filter
     * @return RestModel
     */
    public function findOneBy($filter = array(), $processData = true) {
        return $this->findBy($filter, $processData)[0];
    }

    /**
     *
     * @param mixed $identifier
     * @return RestModel
     */
    public function findOneById($identifier) {

        if (is_array($identifier)) {
            $model = $this->curl->get($identifier);
        } elseif (array_key_exists($identifier, $this->cache)) {
            $model = $this->cache[$identifier];
        } else {
            $model = $this->curl->get(array('id' => $identifier));
        }
        if (is_array($model)) {
            $model = $model[0];
        }
        $this->cache[$model->id] = $model;

        return new RestModel($this, (array)$model, $this->hasOne, $this->hasMany);
    }

    public function insert($data) {
        return (array)$this->curl->post(array(), array(), $data);
    }

    public function update($identifier, $data) {
        if (is_array($identifier)) {
            if (array_key_exists("id", $identifier)) {
                return (array)$this->curl->put(array('id' => $identifier['id']), array(), $data);
            }

            return $this->curl->put(array(), $identifier, $data);
        }

        return (array)$this->curl->put(array('id' => $identifier), array(), $data);
    }

    public function delete($identifier) {
        if (is_array($identifier)) {
            if (array_key_exists("id", $identifier)) {
                return $this->curl->delete(array('id' => $identifier['id']));
            }

            return $this->curl->delete($identifier);
        }

        return $this->curl->delete(array('id' => $identifier));
    }

}
