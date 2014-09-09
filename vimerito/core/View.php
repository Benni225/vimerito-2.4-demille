<?php
class View{
	/**
	 * Instance of the adapter
	 * @var aViewAdapter
	 */
	private $adapter;
	private $defaultAdapter = Null;
	/**
	 * Includes all methods of View
	 * @var array
	 */
	private $methods = array('configure', 'load', 'render', 'assign');
	/**
	 * Checks if it is an own function and call it instead of the adapter
	 * @param string $method
	 * @param array $args
	 */
	public function __call($method, $args){
		return in_array($method, $this->methods)?$this->{$method}($args):$this->adapter->{$method}($args);
	}
	public function __construct(aViewAdapter $adapter = Null){
		if($adapter != Null)
			$this->adapter = $adapter;
		else{
			$__a = Config::get('defaultAdapter');
			if(!empty($__a)){
				$this->adapter = new $__a;
			}else{
				throw('No adapter is given for View');
			}
		}
		return $this->adapter;
	}

	public function configure($settings = array()){
		$this->adapter->configure($settings);
		return $this->adapter;
	}

	public function load($path){
		$this->adapter->load($path);
		return $this->adapter;
	}

	public function render($options = Array()){
		return $this->adapter->render($options);
	}

	public function assign($name, $value=NULL){
		$this->adapter->assign($name, $value);
		return $this->adapter;
	}
}