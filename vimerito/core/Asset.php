<?php
class Asset{
	public static function get(){
		$path = Router::getParameters();
		return file_get_contents(__BASEDIR.Package::get("app").implode('/', $path));
	}
}