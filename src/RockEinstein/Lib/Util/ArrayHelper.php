<?php


namespace RockEinstein\Lib\Util;


class ArrayHelper{

	public static function recursivePrepareArrayToUTF8($array){
		return array_map(function($item){
			if(is_string($item))
				return utf8_encode($item);
			if(is_array($item))
				return ArrayHelper::recursivePrepareArrayToUTF8($item);
			return $item;
		},$array);
	}

	public static function groupArray($array,$glue='__'){
		$grouped = array();
		foreach ($array as $name => $value) {
			if(!strpos($name,$glue)){
				$grouped[$name] = $value;
				continue;
			}
			list($obj,$attr) = explode($glue,$name);
			$grouped[$obj][$attr] = $value;
		}
		return $grouped;
	}

}