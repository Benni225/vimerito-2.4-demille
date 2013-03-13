<?php
class Model extends aModel{
	private $DEFAULT_QUERYBUILDER = "MySqlQuerybuilder";
	private $__id;
	private $__tableColumns;
	private $__querybuilder;
	public function __construct(){
		$r = new ReflectionClass($this);
		$this->__tableColumns = new AssoziativArrayIterator($r->getDefaultProperties());
		$this->__tableColumns->each(function($index, $value, $instance){
			if(strtolower($index) == 'id')
				$instance->__id = $index;
			elseif(array_key_exists("type", $value) && strtolower($value["type"]) == "id")
				$instance->__id = $index;

			echo "Instance ID is ".$instance->__id;
		}, $this);

		echo "ID is ".$this->__id;
		$this->setQuerybuilder($this->DEFAULT_QUERYBUILDER);
	}
}