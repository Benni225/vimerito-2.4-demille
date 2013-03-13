<?php
abstract class aViewAdapter{
	abstract public function load($file);

	abstract public function render(Array $option=Array());

	abstract public function configure(Array $option=Array());

	abstract public function assign($name, $value=NULL);
}