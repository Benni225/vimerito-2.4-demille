<?php
class Json{
	public static function returnJson(Array $json){
		Response::addHeader(array("Content-Type", "application/json; charset:utf-8;", true));
		$json = static::prepareJson($json);
		return array("type"	=>"json", "content"=>json_encode($json, JSON_FORCE_OBJECT));
	}

	private static function prepareJson(Array $json){
		$p = array();
		foreach($json AS $key=>$value){
			if(is_array($value)){
				$p[utf8_encode($key)] = static::prepareJson($value);
			}else{
				$p[utf8_encode($key)] = utf8_encode($value);
			}
		}
		return $p;
	}
}