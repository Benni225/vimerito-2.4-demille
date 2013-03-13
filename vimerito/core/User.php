<?php
class User{
	public static $ressource = NULL;

	public static function create(Array $ressource = Array()){
		static::$ressource = new AssoziativArrayIterator($ressource);
	}

	public static function get($name){
		return static::$ressource->{$name};
	}

	public static function update($key, $value){
		static::$ressource->{$key} = $value;
	}

	public static function replace(Array $ressource = Array()){
		static::$ressource = new AssoziativArrayIterator($ressource);
	}

	public static function is(){
		return static::$ressource != NULL?TRUE:FALSE;
	}

	public static function destroy(){
		static::$ressource = NULL;
	}

}