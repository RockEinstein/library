<?php


namespace RockEinstein\Lib\Api\Request;


interface ApiResquest {

    public function getResource();

    public function getURLParameters();

    public function getBodyParameters();

    public function getHeaderParameters();

    public function getToken();
    
    public function getMethod();
    
    public function getParameter($paramName);
}
