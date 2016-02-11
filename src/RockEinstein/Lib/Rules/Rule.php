<?php


namespace RockEinstein\Lib\Rules;


interface Rule {
    public function triggerBeforeRun();

    public function triggerAfterRun();

    public function setParameters($parameters);
    public function getParameters();
    public function validateParameters();
}