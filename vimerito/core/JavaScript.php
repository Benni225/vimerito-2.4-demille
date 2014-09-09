<?php
class JavaScript{
	private static $scripts = Array();
	private static $sources = Array();
	private static $config = Array();
	private static $html = NULL;

	public static function add($url){
		static::$scripts[] = $url;
	}
	
	public static function addConfig($config){
		foreach($config AS $key=>$value){
			static::$config[$key] = $value;
		}
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
		static::$html.= '<script>Vimerito.Config.init({';
		foreach(static::$config AS $key => $value){
			static::$html.=$key.':"'.$value.'",';
		}
		static::$html = substr(static::$html, 0, -1).'});</script>';
		return static::$html;
	}
}