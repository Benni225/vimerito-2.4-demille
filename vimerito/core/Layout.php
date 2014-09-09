<?php
class Layout extends aSingleton{
	private static $layoutFile = NULL;
	private static $viewInstances = Array();
	private static $adapter = NULL;
	private static $output = '';
	private static $instance = NULL;
	private static $css = Array();
	private static $title;
	private static $description;
	private static $vars = Array();
	private $methods = array('load', 'render', 'add', 'delete');

	public function __call($method, $args){
		return in_array($method, $this->methods)?self::get()->{$method}($args):self::$adapter->{$method}($args);
	}

	public static function create(){
		if(self::$instance === NULL){
			static::$instance = new self;
		}
		return static::$instance;
	}

	public static function get(){
		return static::$instance === NULL?static::create():self::$instance;
	}

	public function load(aViewAdapter $adapter, $file){
		static::$adapter = $adapter;
		static::$adapter->load($file);
		return $this;
	}
	
	public function assign($name, $value=NULL){
		static::$vars[$name] = $value;
		return $this;
	}

	public function add($view, $cssBlock){
		static::$viewInstances[$cssBlock] = $view;
		return $this;
	}

	public function addCss($url){
		static::$css[] = $url;
	}

	public function setTitle($title){
		static::$title = $title;
	}
	
	public function setDescription($description){
		static::$description = $description;
	}
	private static function renderCss(){
		$html = "";
		foreach(static::$css AS $css){
			$html.= "<link rel='stylesheet' href='{$css}' type='text/css' />\r\n";
		}
		return $html;
	}

	public function delete($cssBlock){
		static::$viewInstances[$cssBlock] = NULL;
		return $this;
	}
	
	private static function renderTitle(){
		if(static::$title != ""){
			$html = "";
			$html = "<title>".static::$title."</title>";
			return $html;
		}else{
			return "";
		}
	}
	
	private static function renderDescription(){
		$html = '';
		$html = '<meta name="description" content="'.static::$description.'"/>';
		return $html;
	}

	public function isEmpty(){
		return empty(static::$viewInstances);
	}

	public function render($options = Array()){
		foreach(static::$vars as $key=>$value){
			static::$adapter->assign($key, $value);
		}
		static::$output = static::$adapter->render($options);
		/*
		 * Includes the view-files
		 */
		foreach(self::$viewInstances AS $cssBlock=>$viewInstance){
			$dom = new SimpleHtmlDom(static::$output);
			$viewOutput = $viewInstance->render();
			$element = $dom->find($cssBlock);
			foreach($element AS $e){
				$e->innertext = $viewOutput;
			}
			static::$output = $dom->save();
		}
		//searches for the HEAD-element
		$headElement = $dom->find("head");
		/*
		 * Adds css-files
		 */
		$headElement[0]->innertext = $headElement[0]->innertext.static::renderCss();
		$headElement[0]->innertext = $headElement[0]->innertext.static::renderTitle();
		$headElement[0]->innertext = $headElement[0]->innertext.static::renderDescription();
		/*
		 * Adds javascript-files and -sources
		 */
		$headElement[0]->innertext = $headElement[0]->innertext.JavaScript::getHtml();
		static::$output = $dom->save();
		return static::$output;
	}
}