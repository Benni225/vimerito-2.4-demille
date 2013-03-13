<?php
/**
 * At the moment this class stores different application-packages.
 * @author Benjamin Werner
 *
 */
class Package extends DataRegistry{
	private static $usePackage = "";
	/**
	 * Adds a new package. Packages are applications driven by the router.
	 * A package is equal to the namespace of a class, at the beginning and
	 * the end there has to be a "\".
	 * @param String $name
	 * @param String $namespace
	 * @param  Object $dataHandler
	 */
	public static function addPackage($name, $class){
		self::add($name, $class, new PackageData);
	}

	public static function checkPackage($name){
		if(self::get($name) !== NULL){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	public static function usePackage($package){
		if(self::checkPackage($package)){
			self::$usePackage = $package;
		}else{
			throw Exception("Package: ".$package." is not registered.");
		}
	}

	public static function getActualPackage(){
		return self::$usePackage;
	}

}
