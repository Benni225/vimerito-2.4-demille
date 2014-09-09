<?php
class Autoload{
	private static $__classList = Array();
	private static $__classAlias = Array();
	/**
	 * Autoloader
	 * @author Benjamin Werner
	 * @param String $classname
	 */
	public static function	load($classname){#
		if(self::checkClassList($classname)){
			self::load(self::getClassname($classname));
		}else{
			$file = $classname.".php";
			if(file_exists(__BASEDIR.$file) == TRUE){
				require_once __BASEDIR.$file;
			}elseif(self::checkClassAlias($classname)){
				self::load(self::getClassAlias($classname));
			}elseif(str_replace("_", "", $classname)){
				$file = __BASEDIR.Package::get("app").str_replace("_", "/", strtolower($classname)).'.php';
				if(file_exists($file))
					require_once $file;
				else{
					//Page not found
					if(str_replace("controller", "", strtolower($file)) != strtolower($file)){
						throw new ExceptionPage("Page not found: {$classname} in {$file}");
					}else{
						throw new Exception("Class not found: {$classname} in {$file}");
					}
				}
			}else{
				throw new Exception("Class {$classname} not found");
			}
		}
	}

	public static function checkClasslist($classname){
		if(empty(self::$__classList)){
			self::loadClassList();
		}
		return array_key_exists($classname, self::$__classList)?TRUE:FALSE;
	}

	public static function getClassname($classname){
		return array_key_exists($classname, self::$__classList)?self::$__classList[$classname]:new Exception("Class ".$classname." not found.");
	}

	public static function loadClassList(){
		require_once __SCRIPTDIR.'classList.php';
		self::$__classList = $__classList;
	}

	public static function addClassAlias(Array $alias){
		self::$__classAlias[] = $alias;
	}

	public static function checkClassAlias($classname){
		return array_key_exists($classname, self::$__classAlias)?TRUE:FALSE;
	}

	public static function getClassAlias($classname){
		return array_key_exists($classname, self::$__classAlias)?self::$__classAlias[$classname]:new Exception("Class ".$classname." not found.");
	}
}