<?php

namespace RockEinstein\Lib\Client;

interface DataProvider {

    /**
     * 
     */
    public function findAll();

    /**
     * 
     * @param type $filter
     */
    public function findBy($filter);

    /**
     * 
     * @param type $filter
     */
    public function findOneBy($filter);

    /**
     * 
     * @param type $identifier
     */
    public function findOneById($identifier);

    /**
     * 
     * @param type $identifier
     * @param type $data
     */
    public function update($identifier, $data);

    /**
     * 
     * @param type $data
     */
    public function insert($data);

    /**
     * 
     * @param type $identifier
     */
    public function delete($identifier);
}
