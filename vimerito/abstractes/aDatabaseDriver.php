<?php
abstract class aDatabaseDriver{
	protected $connection;
	abstract public function __connect(Array $parameters);
	abstract public function __isConnected();
	abstract public function __prepare($query);
	abstract public function __query($query);
	abstract public function __result();
	abstract public function __affectedRows();

}
