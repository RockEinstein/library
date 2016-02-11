<?php
namespace RockEinstein\Lib\Rules;

abstract class AbstractRule implements Rule {
    protected $notNull;
    protected $parameters;
    protected $entityManager;
    protected $beforeRunRules;
    protected $afterRunRules;

    public function run(){
        $this->triggerBeforeRun();
        $this->runRule();
        $this->triggerAfterRun();
    }

    public function triggerBeforeRun(){
        if(!isset($this->beforeRunRules)){
            return;
        }

        $beforeRunRules = null;
        if(!is_array($this->beforeRunRules)){
            $beforeRunRules = [$this->beforeRunRules];
        }
        $this->trigger($beforeRunRules);
    }

    public function triggerAfterRun(){
        if(!isset($this->afterRunRules)){
            return;
        }

        $afterRunRules = null;
        if(!is_array($this->afterRunRules)){
            $afterRunRules = [$this->afterRunRules];
        }
        $this->trigger($afterRunRules);
    }
    public function trigger(array $rules){

        foreach($rules as $rule){
            if($rule instanceof AbstractRule){
                $rule->runRule();
            }else{
                $rule();
            }

        }
    }

    abstract protected function runRule();

    public function setNotNullFields(array $fieldNames){
        $this->notNull = $fieldNames;
    }

    /**
     * @param array $parameters
     *
     */
    public function setParameters($parameters){
        $this->parameters = $parameters;
        $this->validateParameters();
    }

    public function getParameters(){
        return $this->parameters;
    }

    public function validateParameters(){
        foreach($this->notNull as $field){
            if(empty($this->parameters[$field])){
                throw new \Exception('RuleLoader: Parameter "'. $field . '" cannot be empty.');
            }
        }
        return true;
    }

    /**
     * @return mixed
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param mixed $entityManager
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }


}