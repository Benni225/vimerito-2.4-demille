<?php
/**
 * This class handles array-data.
 * @author Benjamin Werner
 *
 */
class ArrayData implements iArrayData{
	private $data;
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
