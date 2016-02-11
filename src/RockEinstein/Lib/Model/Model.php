<?php

namespace RockEinstein\Lib\Model;



abstract class Model{

	/**
	* @var string
	*/
	protected static $connectionName = 'default';

	/**
	* @var EntityManagerProvider
	*/
	public static $entityManagerProvider = null;


	public static function getEntityManager(){
		return self::$entityManagerProvider->getEntityManager(static::$connectionName);
	}

	public static function find(){
		$args = array_merge(
			array(get_called_class()),
			func_get_args()
		);
		$callable = array(static::getEntityManager(),'find');
		return call_user_func_array($callable,$args);
	}

	public static function getReference(){
		$args = array_merge(
			array(get_called_class()),
			func_get_args()
		);
		$callable = array(static::getEntityManager(),'getReference');
		return call_user_func_array($callable,$args);	
	}

	public static function getPartialReference(){
		$args = array_merge(
			array(get_called_class()),
			func_get_args()
		);
		$callable = array(static::getEntityManager(),'getPartialReference');
		return call_user_func_array($callable,$args);		
	}

	public static function clear(){
		$args = array_merge(
			array(get_called_class()),
			func_get_args()
		);
		$callable = array(static::getEntityManager(),'clear');
		return call_user_func_array($callable,$args);		
	}

	public static function getRepository(){
		$args = array_merge(
			array(get_called_class()),
			func_get_args()
		);
		$callable = array(static::getEntityManager(),'getRepository');
		return call_user_func_array($callable,$args);
	}

	public static function __callStatic($name,$arguments){
		$callable = array(static::getEntityManager(),$name);
		return call_user_func_array($callable,$arguments);
	}

}