<?php
class Url{
	private static $url = "";
	public static function to($controller_action, $parameters = array()){
		$to = str_replace("@", "", $controller_action) == $controller_action?
				array($controller_action):explode("@", $controller_action);
		self::$url = "http://";
		self::$url.= !Config::isEmpty("baseURL")&&!Application::development()?
			Config::get("baseURL")."/":"localhost".dirname($_SERVER['PHP_SELF'])."/";


		self::$url.= implode("/", $to);
		foreach($parameters AS $parameter=>$value) self::$url.='/'.$parameter.'/'.$value;
		return self::$url;
	}

	public static function asset($path){
		self::$url = "http://";
		self::$url.= !Config::isEmpty('baseURL')&&!Application::development()?
			Config::get('baseURL'):"localhost".dirname($_SERVER['PHP_SELF']);
		self::$url.="/".Package::get('public')."{$path}";
		return self::$url;
	}
}