<?php
class Auth{
	private static $isAuth = FALSE;
	public static function is($login = FALSE){
		if(!self::$isAuth && !User::is()){
			$__query = new Query();
			$__result = $__query->connect()->query("
				SELECT
					*
				FROM
					".Config::get('authTable')."
				WHERE
					`".Config::get('authUsername')."` = '".Session::get("__username")."' AND
					`".Config::get('authPassword')."` = '".Session::get("__password")."'
				LIMIT 1
			");
			if($__query->resultCount > 0){
				static::$isAuth = TRUE;
			}else{
				static::$isAuth = FALSE;
			}
			if(static::$isAuth == TRUE){
				User::create($__query->getRessource());
			};
		}
		return static::$isAuth;
	}
	//@todo strange bug if after login a Redirection::to-call
	public static function login($username, $password){
		Session::set("__username", $username);
		Session::set("__password", $password);
		Session::save();
		$r = static::is(TRUE);
		return $r;
	}

	public static function logout(){
		Session::destroy();
		User::destroy();
	}
}
