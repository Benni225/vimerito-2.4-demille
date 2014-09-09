<?php
class Query extends AssoziativArrayIterator{
	protected static $instance = Null;
	private static $database = NULL;
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
		if(!self::$database){
			self::$database = new Database($databaseConfiguration, $driver);
			if(self::$database){
				$this->connected = true;
			}else{
				throw new Exception('Can not connect to database!');
			}
		}else{
			$this->connected = true;
		}
		return $this;
	}

	public function query($query, $driver = NULL, $databaseConfiguration = NULL){
		$this->resultCount = 0;
		$this->ressource = array();
		if(!$this->connected){
			$this->connect($driver, $databaseConfiguration);
		}
		if($this->isAddAble($query)){
			$this->ressource = NULL;
			self::$database->query($query);
			try{
				if(self::$database->error() != NULL){
					throw new ExceptionMySql(self::$database->error());
				}
				while($r = mysqli_fetch_array(self::$database->result())){
					$this->ressource[] = $r;
				}
				$this->first();
				$this->resultCount = self::$database->numRows();
			}catch(ExceptionMySql $e){
				echo $e->message();
			}
		}else{
			$this->ressource = NULL;
			self::$database->query($query);
			$this->resultCount = self::$database->affectedRows();
		}
		return $this;
	}
	
	private function isAddAble($query){
		$__query = explode(" ", trim(strtolower($query)));
		return $__query[0] == "update"||$__query[0]=="insert"||$__query[0]=="delete"?FALSE:TRUE;
	}

	public function error(){
		$e = self::$database->error();
		if(!$e)
			return FALSE;
		else
			return $e;
	}
	
	/**
	 * Fixes a bug with a duplicated element in $ressource
	 * @return array
	 */
	public function getRessource(){
		$__a = array();for($i = 0; $i < $this->resultCount; $i++){$__a[$i] = $this->ressource[$i];}
		return $__a;
	}
}