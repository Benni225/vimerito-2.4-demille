<?php
class AssoziativArrayIterator extends aIterator implements iIterator{
	/**
	 *
	 * Is the stack. It filled with the current array of the ressource.
	 * @var array
	 */
	private $__current = array();
	/**
	 *
	 * Internal counter
	 * @var integer
	 */
	private $__counter;
	/**
	 * @param array $ressource
	 */
	public function __construct(Array $ressource = NULL){
		if(!empty($ressource)){
			$this->ressource = $ressource;
			$this->first();
		}
	}
	/**
	 * Gets a specified value of the stack.
	 * @param string $name
	 */
	public function __get($name){
		if(key_exists((string)$name, $this->__current)){
			return $this->__current[(string)$name];
		}else{
			throw new Exception("Property does not exist.");
			return NULL;
		}
	}
	/**
	 * Sets a value of the stack.
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set($name, $value){
		$this->__current[(string)$name] = $value;
	}
	/**
	 * (non-PHPdoc)
	 * @see marvel\interfaces.iIterator::first()
	 */
	public function first(){
		if(count($this->ressource) > 0){
			$this->save();
			$this->__counter = 0;
			reset($this->ressource);
			$this->fillCurrent();
		}
		return $this;
	}
	/**
	 * (non-PHPdoc)
	 * @see marvel\interfaces.iIterator::next()
	 */
	public function next(){
		if(count($this->ressource) > 0){
			$this->save();
			$this->__counter++;
			next($this->ressource);
			$this->fillCurrent();
		}
		return $this;
	}
	/**
	 * (non-PHPdoc)
	 * @see marvel\interfaces.iIterator::previous()
	 */
	public function previous(){
		if(count($this->ressource) > 0){
			$this->save();
			$this->__counter--;
			prev($this->ressource);
			$this->fillCurrent();
		}
		return $this;
	}
	/**
	 * (non-PHPdoc)
	 * @see marvel\interfaces.iIterator::last()
	 */
	public function last(){
		if(count($this->ressource) > 0){
			$this->save();
			$this->__counter = count($this->ressource)-1;
			end($this->ressource);
			$this->fillCurrent();
		}
		return $this;
	}
	/**
	 *
	 * Returns the current array of the ressource.
	 */
	public function get(){
		return current($this->ressource);
	}
	/**
	 * (non-PHPdoc)
	 * @see marvel\interfaces.iIterator::isLast()
	 */
	public function isLast(){
		if(count($this->ressource) > 0){
			if(next($this->ressource) == false){
				return true;
			}else{
				prev($this->ressource);
				return false;
			}
		}else{
			return true;
		}
	}
	/**
	 * Saves the stack to the current array.
	 * @return object
	 */
	public function save(){
		if(!empty($this->__current)){
			$array = current($this->ressource);
			foreach($this->__current AS $key=>$value){
				$this->ressource[$this->__counter][$key] = $value;
			}
		}
		return $this;
	}
	/**
	 * Returns the ressource.
	 * @return array
	 */
	public function getRessource(){
		return $this->ressource;
	}
	/**
	 *
	 * Fills the stack with the current array.
	 */
	protected function fillCurrent(){
		if(current($this->ressource)){
			foreach(current($this->ressource) AS $key=>$value){
				$this->__current[$key] = $value;
			}
		}
	}
	/**
	 * Runs a callbackfunction on every item of the ressource.
	 * Following numbers of parameters the callbackfunction can have:
	 * 1: - the value given
	 * 2: - the key and the value given
	 * >2: - the key, each value and additional data
	 * <code>
	 *	$a = new AssoziativArrayIterator(array(
	 *		0	=>	array(
	 *			"key1"	=>	"value1",
	 *			"key2"	=>	"value2",
	 * 			"key3"	=>	"value3"
	 *		),
	 *		1	=>	array(
	 *			"key1"	=>	"value4",
	 *			"key2"	=>	"value5",
	 *			"key3"	=>	"value6"
	 *		),
	 *		2	=>	array(
	 *			"key1"	=>	"value7",
	 *			"key2"	=>	"value8",
	 *			"key3"	=>	"value9"
	 *		)
	 *	));
	 *	$a->each(function($index, $value){
	 *		echo $index." = ".$value."<br />";
	 *	});
	 *	$a->each(function($index, $value, $text){
	 *		echo $text." ".$index." = ".$value."<br />";
	 *	}, "Following value is registered:");
	 *	$a->each(function($value){
	 *		var_dump($value);
	 *		echo "<br />";
	 *	});
	 * </code>
	 * @param function $callback
	 * @param mixed (optional)$additionalData
	 */
	public function each($callback, $additionalData = NULL){
		$rFunction = new ReflectionFunction($callback);
		$numberOfParameters = $rFunction->getNumberOfParameters();
		echo "Ressource: <br />";
		var_dump($this->ressource);
		echo "<br /><br />";
		foreach($this->ressource AS $index=>$value){
			if($numberOfParameters == 1)
				call_user_func($callback, $value);
			elseif($numberOfParameters == 2)
				call_user_func($callback, $index, $value);
			else{
				$data = array($index, $value);
				if($additionalData != NULL){
					array_push($data, $additionalData);
				}
				call_user_func_array($callback, $data);
			}
		}
		return $this;
	}
	/**
	 * Runs a callbackfunction on every item of the ressource.
	 * If the value of an item is an array, the number of
	 * parameters has to be equal to the number of items of that array.
	 * <code>
	 *	$a = new AssoziativArrayIterator(array(
	 *		0	=>	array(
	 *			"key1"	=>	"value1",
	 *			"key2"	=>	"value2",
	 * 			"key3"	=>	"value3"
	 *		),
	 *		1	=>	array(
	 *			"key1"	=>	"value4",
	 *			"key2"	=>	"value5",
	 *			"key3"	=>	"value6"
	 *		),
	 *		2	=>	array(
	 *			"key1"	=>	"value7",
	 *			"key2"	=>	"value8",
	 *			"key3"	=>	"value9"
	 *		)
	 *	));
	 *	$a->eachValue(function($val1, $val2, $val3){
	 *		echo "Val1: ".$val1." - Val2: ".$val2." - Val3: ".$val3."<br />";
	 *	});
	 *	</code>
	 */
	public function eachValue($callback){
		$rFunction = new ReflectionFunction($callback);
		$numberOfParameters = $rFunction->getNumberOfParameters();
		foreach($this->ressource AS $value){
			call_user_func_array($callback, $value);
		}
	}
	/**
	* Returns the actuall keyname of the ressource.
	* @return string
	*/
	public function returnKey(){
		return key($this->ressource);
	}
}
