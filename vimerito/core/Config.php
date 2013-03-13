<?php
class Config{
	private static $config = Array();
	public static function set(Array $c){
		self::$config = $c;
		//Application::$configuration = $c;
	}

	public static function get($key){
		return array_key_exists($key, self::$config)?self::$config[$key]:NULL;
	}

	public static function add(Array $c){
		array_merge(self::$config, $c);
	}

	public static function change($key, $value){
		self::$config[$key] = $value;
	}

	public static function isEmpty($key){
		return empty(self::$config[$key]);
	}
}