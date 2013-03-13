<?php
class Post{
	public static function getValue($key){
		$val = array_key_exists($key, $_POST)?$_POST[$key]:NULL;
		return is_string($val)?utf8_decode($val):$val;
	}

	public static function has($key){
		return array_key_exists($key, $_POST);
	}
}