<?php
class JavaScript{
	private static $scripts = Array();
	private static $sources = Array();
	private static $html = NULL;

	public static function add($url){
		static::$scripts[] = $url;
	}

	public static function addScript($script){
		static::$sources[] = $script;
	}

	public static function getHtml(){
		static::$html = '';
		foreach(static::$sources AS $source){
			static::$html.= "<script type='text/javascript'>{$source}</script>\r\n";
		}
		foreach(static::$scripts AS $script){
			static::$html.= "<script type='text/javascript' src='{$script}'></script>\r\n";
		}
		return static::$html;
	}
}