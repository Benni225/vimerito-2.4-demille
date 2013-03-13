<?php
class Get{
	public static function getValue($key){
		return Router::get()->getParameter($key);
	}

	public static function has($key){
		return Router::get()->hasParameter($key);
	}
}