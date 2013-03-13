<?php
/**
 * Routes to a in the URI specified controller and action and extractes
 * given parameters and values.
 * @author Benjamin Werner
 * @version 0.5
 */
class Router extends aSingleton{
	protected static $instance = NULL;
	/**
	 * The called controller.
	 * @var string
	 */
	public static $controller = "";
	/**
	 * The called action.
	 * @var string
	 */
	public static $action = "";
	/**
	 * Stores additional data from the url
	 * @var array
	 */
	private static $urlData = Array();
	/**
	 * Stores routing aliases
	 * @var array
	 */
	private static $alias = Array();

	private static $parameters = Array();
	/**
	 * (non-PHPdoc)
	 * @see marvel\interfaces.iSingleton::create()
	 */
	public static function create(){
		if(self::$instance === NULL){
			self::$instance = new self;
		}
		return self::$instance;
	}
	/**
	 * Extractes the controller and the action out of the
	 * URI and the parameters with their values.
	 * @version 0.0.1 failure removed: instead of division it has to use modulo
	 */
	public function route(){
		if(!empty($_GET['param'])){
			$parameter = $_GET['param'];
			$parameter = explode("/", $parameter);
			self::$parameters = $parameter;
			for($i = 0; $i < count($parameter); $i++){
				if(in_array("", $parameter)){
					$p = array_search("", $parameter);
					unset($parameter[$p]);
				}
			}
			if(count($parameter) % 2 != 0){
				/*
				 * No action is given.
				 * The first parameter is the name of the controller.
				 */
				self::$controller = $parameter[0];
				for($i = 1; $i < count($parameter); $i+=2)
					$urlParameters[$parameter[$i]] = $parameter[$i+1];
			}else{
				/*
				 * An action is given.
				 * The first parameter is the name of the controller,
				 * the second parameter is the name of the action. ({parameter2}Action).
				 */
				self::$controller = $parameter[0];
				self::$action = $parameter[1];
				for($i = 2; $i < count($parameter); $i+=2)
					$urlParameters[$parameter[$i]] = $parameter[$i+1];
			}
			//Checking if the route has to be redirected to an other controller
			$result = self::checkAlias($parameter);
			if($result === NULL){
				self::$controller = self::$controller;
			}else{
				self::$controller = !empty(self::$alias[$result]['alias'][0])?self::$alias[$result]['alias'][0]:NULL;
				self::$action = !empty(self::$alias[$result]['alias'][1])?self::$alias[$result]['alias'][1]:NULL;
			}
		}
		//If there is something in $_GET, now it is also in our url-parameters
		$urlParameters[] = $_GET;
		self::$urlData = $urlParameters;

		return $this;
	}
	/**
	 * Returns the singleton-instance.
	 */
	public static function get(){
		return self::$instance;
	}
	/**
	 * Returns the called controller.
	 * @return string
	 */
	public function controller(){
		return self::$controller;
	}
	/**
	 * Returns the called action.
	 * @return string
	 * Enter description here ...
	 */
	public function action(){
		return self::$action;
	}
	/**
	 * Returns the additional data from the url. For example:
	 *
	 * http://www.mysite.com/controller/action/username/Marcus/birthday/11-3-1988
	 *
	 * will return an multidimensional array like:
	 * <code>
	 * <?php
	 * $urlData(
	 * 		'username'	=>	'Marcus',
	 * 		'birthday'	=>	'11-3-1988'
	 * )
	 * ?>
	 * </code>
	 * @return array
	 */
	public function urlData(){
		return self::$urlData;
	}
	/**
	 * Register a new alias for an url.
	 * @param String $url
	 * @param array $alias
	 * @example Router::addAlias('my/url', array('controller'[, 'action']));
	 */
	public static function addAlias($url, Array $alias){
		self::$alias[] = array(
			'url'	=>	$url,
			'alias'	=>	$alias
		);
	}
	/**
	 * Checks if the url is equal to an alias
	 * @param array $parameters
	 * @return NULL for not or the index of the alias-array
	 */
	private static function checkAlias(Array $parameters){
		foreach(self::$alias AS $index=>$alias){
			$aliasParameter = explode("/", $alias['url']);
			$failure = FALSE;
			for($i = 0; $i < count($aliasParameter); $i++){
				if(!empty($aliasParameter[$i])){
					if(!empty($parameters[$i])){
						if($aliasParameter[$i] != $parameters[$i]){
							$failure = TRUE;
						}
					}else{
						$failure = TRUE;
					}
				}
			}
			if($failure == FALSE){
				return $index;
			}
		}
		return NULL;
	}
	/**
	 * Returns all router-parameters as an array
	 */
	public static function getParameters(){
		return self::$parameters;
	}
	/**
	 * Checks if a  specified parameter exists. Return true or false.
	 * @param BOOL
	 */
	public function hasParameter($parameter){
		return array_key_exists($parameter, self::$urlData)?TRUE:FALSE;
	}
	/**
	 * Retruns the value of a specified parameter. If the
	 * parameter does not exist this method returns NULL.
	 * @param mixed
	 */
	public function getParameter($parameter){
		return $this->hasParameter($parameter)?self::$urlData[$parameter]:NULL;
	}
	/**
	 * Manipulates the parameters. An array has to be given.
	 * @param none
	 */
	public static function setParameters(Array $parameters){
		self::$urlData = $parameters;
	}
}
