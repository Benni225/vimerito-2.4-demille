<?php
/**
 * Stores and handles the POST-data.
 * @author Benjamin Werner
 *
 */
class Data implements iArrayData{
	private $data = Array();
	/**
	 * (non-PHPdoc)
	 * @see marvel\interfaces.iArrayData::get()
	 */
	public function get($name){
		if($this->data == NULL || !is_array($this->data)){
			return NULL;
		}else{
			if(array_key_exists($name, $this->data)){
				return $this->data[$name];
			}else{
				return NULL;
			}
		}
	}
	/**
	 * (non-PHPdoc)
	 * @see marvel\interfaces.iArrayData::set()
	 */
	public function set(Array $data){
		$this->data = $data;
	}
}
