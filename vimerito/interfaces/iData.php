<?php
/**
 * An interface for storing a not specified kind of data.
 * @author Benjamin Werner
 *
 */
interface iData{
	/**
	 * Returns the stored data.
	 * @return mixed
	 */
	public function get();
	/**
	 * Sets the data.
	 * @param mixed $data
	 */
	public function set($data);
}
