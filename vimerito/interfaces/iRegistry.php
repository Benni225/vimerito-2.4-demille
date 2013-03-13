<?php
/**
 * An interface for a registry-like class.
 * @author Benjamin Werner
 *
 */
interface iRegistry{
	/**
	 * Adds a new key to the registry.
	 * @param string $name
	 * @param mixed $data
	 * @param object $dataHandler
	 */
	public static function add($name, $data, $dataHandler);
	/**
	 * Returns a value filtered by its key from the registry.
	 * @param string $name
	 * @return mixed
	 */
	public static function get($name);
	/**
	 * Updates the data of a specified key in the registry.
	 * @param string $name
	 * @param mixed $data
	 */
	public static function update($name, $data);
}
