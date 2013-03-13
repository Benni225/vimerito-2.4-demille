<?php
abstract class aModel{
	public function __construct(){
		parent::__construct();
	}

	public function setQuerybuilder($querybuilder){
		$this->__querybuilder = $querybuilder::create();
	}
}
