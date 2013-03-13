<?php
/**
 * Exception-class.
 * @author Benjamin Werner
 *
 */
class ExceptionMySql extends Exception{
	public function __construct($message, $code=''){
		$this->message = $message;
		$this->code = $code;
	}

	public function message(){
		return $this->message;
	}
}