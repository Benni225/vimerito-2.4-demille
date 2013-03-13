<?php
/**
 * The interface for a singleton-class
 * @author Benjamin Werner
 *
 */
interface iSingleton{
	/**
	 * Create the singleton-instance, of it not exists and return it.
	 */
	public static function create();
}
