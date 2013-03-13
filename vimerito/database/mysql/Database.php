<?php

class Database{
	private $driver;
	public function __construct($parameters = NULL, $driver = NULL){
		$parameters!=NULL?$this->connect($parameters, $driver):NULL;
	}
	public function setDriver(aDatabaseDriver $driver){
		$this->driver = $driver;
	}

	public function connect($parameters, $driver = NULL){
		$driver==NULL?$this->setDriver(new Driver()):$this->setDriver($driver);
		$this->driver->__connect($parameters);
		return $this;
	}

	public function query($query){
		$this->driver->__query($this->driver->__prepare($query));
		return $this;
	}

	public function result(){
		return $this->driver->__result();
	}

	public function isConnected(){
		return $this->driver->__isConnected();
	}

	public function affectedRows(){
		return $this->driver->__affectedRows();
	}

	public function numRows(){
		return $this->driver->__numRows();
	}

	public function error(){
		return $this->driver->__error();
	}
}
