<?php
/**
 * Abstract singleton-class.
 * @author Benjamin Werner
 *
 */
abstract class aSingleton implements iSingleton{
	protected function __construct(){}
	protected function __clone(){}
}
