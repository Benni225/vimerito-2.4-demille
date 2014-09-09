<?php
/**
 * Manage the application. Runs the router, stores data from GET and POST
 *
 * @author Benjamin Werner
 * @version 0.0.2
 *
 */
class Application extends aSingleton{
	/**
	 * Name of the controllerclass
	 * @var string
	 */
	public static $controller = "";
	/**
	 * Name of the action-method.
	 * @var String
	 */
	public static $action = "";
	/**
	 * Object that stores the POST-data.
	 * @var Post
	 */
	public static $dataPost;
	/**
	 * Object that stores the GET-data
	 * @var Get
	 */
	public static $dataGet;
	public static $configuration = array();
	private static $developmentMode = false;
	/**
	 * The application-package to route to.
	 * @var string
	 */
	private static $applicationPackage = "";
	/**
	 * Singleton-instance
	 * @var Application
	 */
	private static $instance = NULL;
	private static $controllerInstance = NULL;
	/**
	 * Initialize the application and add the standard-package.
	 * @return Application
	 */
	public static function create(){
		Session::create();
		Package::addPackage("app", "app/", new PackageData());
		Package::addPackage("view", "app/view/", new PackageData());
		Package::addPackage("model", "app/model/", new PackageData());
		Package::addPackage("cache", "app/cache/", new PackageData());
		Package::addPackage("public", "public/", new PackageData());
		Package::addPackage("vimerito", "vimerito/", new PackageData());

		ob_start();

		Router::create()->route();

		//Initialize the app
		require_once Package::get("app")."boot.php";

		self::setController(Router::get()->controller());
		self::setAction(Router::get()->action());
		self::storeData();

		if(self::$instance === NULL){
			self::$instance = new self;
		}
		return self::$instance;
	}
	/**
	 * Sets the configuration of the application. Normaly called in boot.php.
	 * @param array $configuration
	 */
	public static function configuration(Array $configuration){
		Config::set($configuration);
	}
	/**
	 * Routes and run the called controller and the called action.
	 */
	public static function run(){
		if(empty(self::$action)){
			self::setAction(Config::get('defaultAction'));
		}
		try{
			self::$controllerInstance = new self::$controller;
			if(method_exists(self::$controllerInstance, self::$action)){
				$output = self::$controllerInstance->{self::$action}();
				if(is_array($output)){
					switch($output["type"]){
						case "redirectionController":
							self::setController($output["controller"]);
							self::setAction($output["action"]);
							Router::setParameters($output["parameters"]);
							self::run();
							break;
						case "redirection":
							Response::addHeader(array("Location", Url::to("{$output["controller"]}@{$output["action"]}", $output["parameters"])));
							break;
						case "json":
							//Response::addHeader(array("Content-type", "application/json"));
							Response::setContent($output["content"]);
							break;
					}
				}else{
					/*
					 * If no layout used
					 */
					if(!empty($output)){
						Response::setContent($output);
					/*
					 * If a layout used
					 */
					}elseif(property_exists(self::$controllerInstance, "layout") && Layout::get()->isEmpty() != TRUE){
						$reflectedClass = new ReflectionClass(self::$controllerInstance);
						$layoutFile = $reflectedClass->getProperty("layout")->getValue(self::$controllerInstance);
						$adapter = Config::get('layoutAdapter');
						$output = Layout::get()->load(new $adapter, $layoutFile)->render();
						Response::setContent($output);
					}else{
						Response::status(204);
					}
					Session::save();
				}
				self::output();
			}else{
				throw new ExceptionPage("Page not found.");
			}
		}catch(ExceptionPage $e){
			Response::status(404);
			self::setController(Config::get('404Controller'));
			self::setAction(Config::get('404Action'));
			self::$controllerInstance = new self::$controller;
			$output = self::$controllerInstance->{self::$action}();
			/*
			 * If no layout used
			 */
			if(!empty($output)){
				Response::setContent($output);
			/*
			 * If a layout used
			 */
			}elseif(property_exists(self::$controllerInstance, "layout") && Layout::get()->isEmpty() != TRUE){
				$reflectedClass = new ReflectionClass(self::$controllerInstance);
				$layoutFile = $reflectedClass->getProperty("layout")->getValue(self::$controllerInstance);
				$adapter = Config::get('layoutAdapter');
				$output = Layout::get()->load(new $adapter, $layoutFile)->render();
				Response::setContent($output);
			}else{
				Response::status(204);
			}
			self::output();
		}
	}
	/**
	 *
	 * Set the called controller.
	 * @param string $controller
	 */
	public static function setController($controller){
		self::$controller = empty($controller)?"Controller_".Config::get('defaultController'):"Controller_".$controller;
	}
	/**
	 * Set the called action.
	 * @param string $action
	 */
	public static function setAction($action){
		self::$action = empty($action)?Config::get('defaultAction').'Action':$action.'Action';
	}
	/**
	 * Stores the POST-data and GET-data.
	 */
	public static function storeData(){
		DataRegistry::create()->add("POST", $_POST, new Data());
		DataRegistry::create()->add("GET", Router::get()->urlData(), new Data());
	}

	public static function development($flag = NULL){
		if($flag === NULL){
			return self::$developmentMode;
		}else{
			self::$developmentMode = $flag;
		}
	}
	/**
	 * Creates the output of the application.
	 * @param mixed $output
	 */
	private static function output(){
		Response::buildStatus();
		Response::buildHeader();
		Response::output();
		ob_end_flush();
	}
}