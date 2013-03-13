<?php
class Driver extends aDatabaseDriver{
	private $result;
	public function __connect(Array $parameters){
		if(!key_exists('database', $parameters) || empty($parameters['database']) || empty($parameters['server']) || empty($parameters['server']) || empty($parameters['username']) || empty($parameters['username'])){
			throw new Exception("Can not connect to server, because some data is missing!");
		}else{
			if(!key_exists('newLink', $parameters) || empty($parameters['newLink'])) $parameters['newLink'] = false;
			if(!key_exists('flags', $parameters) || empty($parameters['flags'])) $parameters['flags'] = NULL;

			if(!key_exists('port', $parameters) || empty($parameters['port'])){
				$this->connection = mysqli_connect(
				$parameters['server'],
				$parameters['username'],
				$parameters['password'],
				$parameters['newLink'],
				$parameters['flags']
				);
			}else{
				$this->connection = mysqli_connect(
				$parameters['server'].":".$parameters['port'],
				$parameters['username'],
				$parameters['password'],
				$parameters['newLink'],
				$parameters['flags']
				);
			}
			if($this->connection == FALSE)
				throw new Exception("Can not connect to mysql-server! Following error detected: ".$this->__error());
			else{
				if(!mysqli_select_db($this->connection, $parameters['database'])){
					throw new Exception("Can not select a database! Following error detected: ".$this->__error(), $this->__errorCode());
				}
			}
		}
	}
	public function __isConnected(){
		if($this->connection != Null || $this->connection != FALSE){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	public function __prepare($query){
		return $query;
	}
	public function __query($query){
		$this->result = mysqli_query($this->connection, $query);
	}
	public function __result(){
		return $this->result;
	}
	public function __affectedRows(){
		return $this->connection&&mysqli_affected_rows($this->connection) >= 0?mysqli_affected_rows($this->connection):-1;
	}

	public function __numRows(){
		return $this->result&&mysqli_num_rows($this->result) >= 0?mysqli_num_rows($this->result):-1;
	}

	public function __error(){
		return mysqli_error($this->connection)?"There was a mysql-error: ".mysqli_error($this->connection)."! Code: ".$this->__errorCode():NULL;
	}

	public function __errorCode(){
		return mysqli_errno($this->connection);
	}

	public function __destruct(){
		mysqli_close($this->connection);
	}
}
