<?php
/**
 * An interfaces for the handling of array-data.
 * @author Benjamin Werner
 *
 */
interface iArrayData{
	/**
	 * Return the data of an array, filtered by the key $name.
	 * @param String $name
	 */
	public function get($name);
	/**
	 * Stores an array.
	 * @param array $data
	 */
	public function set(Array $data);
}
