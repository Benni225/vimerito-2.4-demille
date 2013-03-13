<?php
class Session{
	public static $sessionVal = array();
	private static $__sessionId;
	public function __construct(){}

	public static function save(){
		foreach(self::$sessionVal AS $key=>$value){
			$_SESSION[$key] = $value;
		}
	}

	public static function destroy(){
		self::$sessionVal = Null;
		session_unset($_SESSION);
		session_destroy();
	}

	public static function generateID($lng = 15){
		mt_srand((double)microtime()*1000000);
		$charset = "123456789ABCDEFGHIJKLMNPQRSTUVWXYZ";
		$length = strlen($charset)-1;
		$code = '';
		for($i=0;$i<$lng;$i++){
			$code.= $charset{mt_rand(0, $length)};
		}
		return md5($code);
	}

	public static function create(){
		session_start();
		self::$sessionVal = $_SESSION;
	}

	public static function regenerate($delete = false){
		self::$__sessionId = self::generateID();
		session_regenerate_id($delete);
		session_id(self::$__sessionId);
	}

	public static function get($name){
		return isset(self::$sessionVal[$name])?self::$sessionVal[$name]:Null;
	}

	public static function set($name, $value = 0){
		is_array($name)?array_merge(self::$sessionVal, $name):self::$sessionVal[$name] = $value;
	}
}