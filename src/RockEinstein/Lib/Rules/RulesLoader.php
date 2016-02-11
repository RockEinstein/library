<?php
namespace RockEinstein\Lib\Rules;

class RulesLoader {
    private static $ruleMap = null;

    private static $filePath;

    public static function setRuleFilePath($path){
        if(!file_exists($path)){
            throw new \Exception('Rules File Not Found');
        }
        self::$filePath = $path;
    }
    public static function getGlobalRule(){
        return require_once self::$filePath;

    }
    public static function setRuleMap($client){

        $globalRule = self::getGlobalRule();
        if(!array_key_exists($client,$globalRule)){
            throw new \Exception('Client Rules not Found');
        }

        self::$ruleMap = $globalRule[$client];
    }

    public static function getRule($client, $ruleName){
        if(self::$ruleMap === null){
            self::setRuleMap($client);
        }
        if(!empty($ruleName)){
           $Rule = self::parseRuleName($ruleName);
        }
        return new $Rule;
    }

    /**
     * @param $rule 'Cost/Estimation/NationalizationCost'
     */
    private static function parseRuleName($ruleName){
        $arrRules = explode('/', $ruleName);
        $rule = null;
        for($i = 0; $i < count($arrRules); $i++){
            if($rule === null){
                $rule = self::$ruleMap[$arrRules[$i]];
                continue;
            }
            $rule = $rule[$arrRules[$i]];
        }
        if(empty($rule)){
            throw new \Exception('Rule not loaded');
        }
        return $rule;
    }
}