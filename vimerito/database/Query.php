<?php
class Query extends AssoziativArrayIterator{
	private $database = NULL;
	private $connected = FALSE;
	public $resultCount = 0;

	public function __construct($driver = NULL, $databaseConfiguration = NULL){
		if(!empty($driver) && !empty($databaseConfiguration)){
			$this->connect($driver, $databaseConfiguration);
		}
	}
	public function connect($driver = NULL, $databaseConfiguration = NULL){
		if(empty($databaseConfiguration)){
			$databaseConfiguration = Config::get('defaultDatabase');
		}
		$this->database = new Database($databaseConfiguration, $driver);
		if($this->database){
			$this->connected = true;
		}else{
			throw new Exception('Can not connect to database!');
		}
		return $this;
	}

	public function query($query, $driver = NULL, $databaseConfiguration = NULL){
		$this->resultCount = 0;
		if(!$this->connected){
			$this->connect($driver, $databaseConfiguration);
		}
		if($this->isAddAble($query)){
			$this->ressource = NULL;
			$this->database->query($query);
			try{
				if($this->database->error() != NULL){
					throw new ExceptionMySql($this->database->error());
				}
				while($r = mysqli_fetch_array($this->database->result())){
					$this->ressource[] = $r;
				}
				$this->first();
				$this->resultCount = $this->database->numRows();
			}catch(ExceptionMySql $e){
				echo $e->message();
			}
		}else{
			$this->ressource = NULL;
			$this->database->query($query);
			$this->resultCount = $this->database->affectedRows();
		}
		return $this;
	}

	private function isAddAble($query){
		$__query = explode(" ", trim(strtolower($query)));
		return $__query[0] == "update"||$__query[0]=="insert"||$__query[0]=="delete"?FALSE:TRUE;
	}

	public function error(){
		$e = $this->database->error();
		if(!$e)
			return FALSE;
		else
			return $e;
	}
}