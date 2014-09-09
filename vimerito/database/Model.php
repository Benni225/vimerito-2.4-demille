<?php
class Model extends Query{
	public $table;
	private $sql;
	private $sqlKeyword = '';
	private $chacheTime = false;
	public $columns = array();
	public $hasOne;
	public $hasMany;
	public $hasLink;
	private static $query;
	protected static $__constants = array(
		"current_timestamp", "date", "from_days","current_time","now()", "from_unixtime", "last_day", "sec_to_time",
		"sysdate", "time", "timestamp", "utc_date", "utc_time", "utc_timestamp", "year", "abs", "acos", "aes_encrypt",
		"ascii", "asin", "atan", "bin", "bit_count", "bit_length", "ceiling", "char", "char_length", "compress",
		"connection_id", "cos", "cot", "crc32", "current_user", "database", "dayname", "dayofmonth", "dayofweek",
		"dayofyear", "degrees", "des_decrypt", "des_encrypt", "encrypt", "exp", "floor", "hex", "hour", "inet_aton",
		"inet_nota", "length", "ln", "load_file", "log", "log10", "log2", "lower", "ltrim", "md5", "microsecond",
		"minute", "month", "monthname", "oct", "old_password", "ord", "password", "pi", "quarter", "quote", "radians",
		"rand", "reverse", "round", "rtrim", "second", "sha1", "sign", "sin", "soundex", "space", "sqrt", "tan",
		"time_to_sec", "to_days", "to_seconds", "trim", "uncompress", "uncompressed_length", "unhex", "unix_timestamp",
		"upper", "user", "uuid", "uuid_short", "version", "week", "weekday", "weekofyear", "yearweek"
	);

	private $__objects = array();

	public function escape($string){
		$string = str_replace("'", "\'", $string);
		$string = str_replace('"', '\"', $string);
		$string = str_replace('\n', '\\n', $string);
		$string = str_replace('\r', '\\r', $string);
		return $string;
	}

	public function __set($name, $value){
		if(is_object($value) && in_array('Model', class_parents($value))){
			$this->__objects[(string)$name] = $value;
		}else{
			$this->__current[(string)$name] = $this->escape((preg_replace("/\n/", "<br />", $value)));
		}
	}

	public function __get($name){
		try{
			if(array_key_exists((string)$name, $this->__current)){
					return !is_object($this->__current[(string)$name])?
						preg_replace('/<br(\s+)?\/?>/i', "\n", utf8_encode($this->__current[(string)$name])):
						$this->__current[(string)$name];
			}elseif(array_key_exists((string)$name, $this->__objects)){
				return $this->__objects[(string)$name];
			}else{
				throw new Exception("Property '{$name}' does not exist.");
				return NULL;
			}
		}catch(Exception $e){
			return Null;
		}
	}

	public function toArray($d = false){
		$array = $this->getRessource();
		$n = array();
		while(list($key, $value) = each($array)){
			if(is_array($value)){
				$tuple = array();
				while(list($i, $v) = each($value)){
					if(!is_numeric($i))
						$tuple[$i] = is_object($v)?$v->toArray(true):$v;
				}
				$n[$key] = $tuple;
			}else{
				$n[$key] =  is_object($value)?$value->toArray(true):$value;
			}
		}
		if(count($n) == 1 && $d == true){
			$n = $n[0];
		}
		$n["resultCount"] = $this->resultCount;
		return $n;
	}

	public function __call($function, $arguments){
		$funcArg = explode("_", $function);
		switch($funcArg[0]){
			/**
			*	@example
			*	//myTable1 has a one-to-one-connection row in otherTable.
			*	//One result in myTable1 has one or less results in otherTable.
			*	//The linked column in otherTable is myTable1Id
			*	$model = new Model_myTable1;
			*	$model->hasOne('otherTable');
			*	//The linked column in otherTable is column2
			*	$model->hasOne('otherTable', 'column2');
			*/
			case 'hasOne':
				$reference_id = count($arguments) < 2?'Id':$arguments[1];
				$class = 'Model_'.$arguments[0];
				$this->hasOne[] = array(
					$arguments[0],
					$reference_id
				);
				$this->{'Model_'.$arguments[0]} = new $class;
				return $this;
				break;
			/**
			*	@example
			*	//myTable has a one-to-many-connection in otherTable.
			*	//Ont result in myTable1 has no, one or more results in otherTable.
			*	//The linked column in otherTable is myTable1Id
			*	$model = new Model_myTable1;
			*	$model->hasMany('otherTable');
			*	//The linked column in otherTable is column2
			*	$model->hasMany('otherTable', 'column2');
			*	//The linked column in otherTable is column2
			*	//Limit the results to 20
			*	$model->hasMany('otherTable', 'column2', 20);
			*/
			case 'hasMany':
				$reference_id = '';
				$reference_id = count($arguments) < 2?'Id':$arguments[1];
				$class = 'Model_'.$arguments[0];
				if(count($arguments) > 2){
					$this->hasMany[] = array(
						$arguments[0],
						$reference_id,
						'',
						$arguments[2]
					);
				}else{
					$this->hasMany[] = array(
						$arguments[0],
						$reference_id
					);
				}
				$this->{'Model_'.$arguments[0]} = new $class;
				return $this;
				break;
			/**
			*	Links the table to another.
			*	@example
			*	<?php
			*	$model->link("otherModel", "parentColumn", "childColumn"[, 3]);
			*
			*/
			case 'hasLink':
				$class = 'Model_'.$arguments[0];
				if(count($arguments) > 3){
					$this->hasLink[] = array(
						$arguments[0],
						$arguments[1],
						$arguments[2],
						$arguments[3]
					);
				}else{
					$this->hasLink[] = array(
						$arguments[0],
						$arguments[1],
						$arguments[2],
						''
					);
				}
				$this->{'Model_'.$arguments[0]} = new $class;
				return $this;
				break;
			case 'delete':
				$this->sql("delete");
				$this->sqlKeyword = 'delete';
				return $this;
				break;
			/**
			*	@example
			*	$model = new Model_profiles;
			*	$model->where_id_or_username('1', 'Theodor');
			*/
			case 'where':
				if($this->sqlKeyword != 'delete')
					$this->sql("select");
				$this->sqlKeyword = 'where';
				$a = array();
				$counter = 0;
				$pos = 0;
				if(count($funcArg)>1){
					for($p = 1; $p < count($funcArg); $p++){
						$pos_and = array_search("and", $funcArg);
						$pos_or = array_search("or", $funcArg);

						if($pos_and != false && $pos_or != false){
							$pos = $pos_and < $pos_or?$pos_and:$pos_or;
						}else
							$pos = $pos_and == false?$pos_or:$pos_and;

						if($pos === false){
							$length = count($funcArg) - $p;

							if($length == 2)	$a[] = array($funcArg[$p], $funcArg[$p+1], $arguments[$counter]);

							if($length == 1)	$a[] = array($funcArg[$p], 'is', $arguments[$counter]);
						}else{
							$length = $pos - $p;
							if($length == 1)	$a[] = array('id', 'is', $arguments[$counter], $funcArg[$pos]);

							if($length == 2)	$a[] = array($funcArg[$p], 'is', $arguments[$counter], $funcArg[$pos]);

							if($length == 3)	$a[] = array($funcArg[$p], $funcArg[$p+1], $arguments[$counter], $funcArg[$pos]);
						}
						//Delete the first "or" or "and"
						if($pos){
							$funcArg[$pos]=NULL;
							$p = $pos;
						}else {
							break;
						}
						$counter++;
					}
				}else{
					$a[] = array($arguments[0]);
				}
				$this->sql('where');
				$this->sql('whereClause', $a);
				return $this;
				break;
			/**
			*	@example
			*	$model = new Model_profiles;
			*	//Where profiles.id = 1 and profiles.username = 'Theodor'
			*	$model->where('1')->andWhere_username('Theodor');
			*/
			case 'andWhere':
				$a = array();
				$counter = 0;
				$pos = 0;
				if(count($funcArg)>1){
					for($p = 1; $p < count($funcArg); $p++){
						$length = count($funcArg) - $p;

						if($length == 2)	$a[] = array($funcArg[$p], $funcArg[$p+1], $arguments[$counter]);

						if($length == 1)	$a[] = array($funcArg[$p], 'is', $arguments[$counter]);

						break;
					}
				}else
					$a[] = array($arguments[0]);

				$this->sql('and');
				$this->sql('whereClause', $a);
				return $this;
				break;
			/**
			*	@example
			*	$model = new Model_profiles;
			*	//Where profiles.id = 1 or profiles.username = 'Theodor'
			*	$model->where('1')->orWhere_username('Theodor');
			*/
			case 'orWhere':
				$a = array();
				$counter = 0;
				$pos = 0;
				if(count($funcArg)>1){
					for($p = 1; $p < count($funcArg); $p++){
						$length = count($funcArg) - $p;

						if($length == 2)	$a[] = array($funcArg[$p], $funcArg[$p+1], $arguments[$counter]);

						if($length == 1)	$a[] = array($funcArg[$p], 'is', $arguments[$counter]);

						break;
					}
				}else
					$a[] = array($arguments[0]);

				$this->sql('or');
				$this->sql('whereClause', $a);
				return $this;
				break;
			case 'all':
				$this->sqlKeyword = 'where';
				$this->sql("select");
				return $this;
				break;
			/**
			*	@example
			*	$model = new Model_comments;
			*	//Where comments.username = 'Theodor' sorted down by the comments.date and sorted up by comments.title
			*	$model->where_username('Theodor')->orderBy('date', 'DESC', 'title', ASC);
			*	//or like that way
			*	$model->where('1')->orderBy('date', 'DESC', 'title');
			*/
			case 'orderBy':
				$this->sql("orderBy", $arguments);
				return $this;
				break;
			/**
			*	@example
			*	$model = new Model_comments;
			*	//Set the limit to 20 results;
			*	$model->where_username('Theodor')->limit(20);
			*	//or take the results 10 to 30
			*	$model->where('1')->limit(10, 20);
			*/
			case 'limit':
				$this->sql("limit", $arguments);
				return $this;
				break;
			case 'rand':
				$this->sql("rand");
				return $this;
				break;
			/**
			*	@example
			*	$model = new Model_comments;
			*	$model->title = 'My blabla';
			*	$model->text = 'With that post I want to sent my own blabla to you!';
			*	$model->username = 'Theodor';
			*	//Save the actual values!
			*	$model->save();
			*	//Save the values to the database.
			*	$model->insert();
			*
			*	//You can also do it on that way:
			*	$model->insert(array(
			*		'title'	=>	'My blabla',
			*		'text'	=>	'With that post I want to sent my own blabla to you!',
			*		'username' => 'Theodor'
			*	));
			*/
			case 'insert':
				$this->sqlKeyword = 'insert';
				if(empty($arguments)){
					$this->sql("insert", $this->get());
				}else{
					$this->sql("insert", $arguments[0]);
				}
				return $this;
				break;
			case 'insertMany':
				$this->sqlKeyword = 'insert';
				if(empty($arguments)){
					$this->sql("insert", $this->getRessource());
				}else{
					$this->sql("insert", $arguments[0]);
				}
				return $this;
				break;
			/**
			*	@example
			*	//Fill the model with values
			*	$model->id = 10;
			*	$model->username = 'Theodor';
			*	$model->email = 'theodor@gmail.com';
			*	$model->save();
			*	...
			*	...
			*	//Update where id = 10
			*	$model->update();
			*	//Or update where email = theodor@gmail.com
			*	$model->update('email');
			*	//Or use an array with values instead of the values stored in
			*	//model. In addtion update where username = Theodor.
			*	$model->update(array(
			*		'id'	=>	10,
			*		'username'	=>	'Theodor',
			*		'email'	=>	'theodor@gmail.com'
			*	), 'username');
			*	//Or use an array with values instead of the values stored in
			*	//model. In addtion update where username = Marcus.
			*	$model->update(array(
			*		'id'	=>	10,
			*		'username'	=>	'Theodor',
			*		'email'	=>	'theodor@gmail.com'
			*	), 'username', 'Marcus');
			*/
			case 'update':
				$this->sqlKeyword = 'update';
				if(empty($arguments) || is_string($arguments[0])){
					$values = $this->get();
					if(count($arguments) == 1){
						$whereClauseArray = array(array($arguments[0], 'is', $values[$arguments[0]]));
					}elseif(count($arguments) == 2){
						$whereClauseArray = array(array($arguments[0], 'is', $arguments[1]));
					}else{
						$whereClauseArray = array(array("id", "is", $values["id"]));
					}
					if(!empty($arguments[0]))
						$values[$arguments[0]] = Null;
					else
						$values['id'] = Null;
					$this->sql("update", $values);
					$this->sql("where");
				}else{
					$this->sql("update", $arguments[0]);
					$this->sql("where");
					if(!array_key_exists(2, $arguments) && array_key_exists(1, $arguments)){
						$whereClauseArray = array(array($arguments[1], 'is', $arguments[0][$arguments[1]]));
					}elseif(array_key_exists(2, $arguments)){
						$whereClauseArray = array(array($arguments[1], 'is', $arguments[2]));
					}else{
						$whereClauseArray = array(array('id', 'is', $arguments[0]['id']));
					}
				}
				$this->sql("whereClause", $whereClauseArray);
				return $this;
				break;
			default:
				return $this->sql($funcArg[0]);
				break;
		}
	}

	public function __construct(){
	}

	private function sql($type, $args = NULL){
		switch($type){
			case 'select':
				$this->sql = 'SELECT * FROM '.$this->table.' ';
				break;
			case 'distinct':
				if($this->sql != '')
					$this->sql = str_replace("SELECT", "SELECT DISTINCT", $this->sql);
				else
					$this->sql = 'SELECT DISTINCT * FROM '.$this->table.' ';
				return $this;
				break;
			case 'delete':
				$this->sql = 'DELETE FROM '.$this->table.' ';
				break;
			case 'and':
				$this->sql.= ' AND ';
				break;
			case 'or':
				$this->sql.= ' OR ';
				break;
			case 'where':
				$this->sql .= ' WHERE ';
				break;
			case 'update':
				$this->sql = 'UPDATE '.$this->table.' SET ';
				$columnValues = '';
				foreach($args AS $column=>$value){
					if($column != "" && $value != ""){
						if(in_array($value, static::$__constants))
							$columnValues.= $this->table.'.'.$column.' = '.$value.', ';
						else
							$columnValues.= $this->table.'.'.$column.' = "'.$value.'", ';
					}
				}
				$columnValues = substr($columnValues, 0, -2);
				$this->sql .= ' '.$columnValues.' ';
				break;
			case 'insert':
				$this->sql = 'INSERT INTO '.$this->table.' ';
				$columns = '';
				$values = '';
				foreach($args AS $column=>$value){
					$columns .= $this->table.'.'.$column.', ';
					if(in_array(strtolower($value), static::$__constants))
						$values .= $value.', ';
					else
						$values .= '"'.$value.'", ';
				}

				$columns = substr($columns, 0, -2);
				$values = substr($values, 0, -2);
				$this->sql.= '('.$columns.') VALUES ('.$values.')';
				return $this;
				break;
			case 'whereClause':
				for($t = 0; $t < count($args); $t++){
					$arg = $args[$t];
					$arg = $this->arrayReplaceValue($arg, "is", "=");
					$arg = $this->arrayReplaceValue($arg, "isNot", "<>");
					$arg = $this->arrayReplaceValue($arg, "bigger", ">");
					$arg = $this->arrayReplaceValue($arg, "lower", "<");
					$arg = $this->arrayReplaceValue($arg, "like", "LIKE");
					$arg = $this->arrayReplaceValue($arg, "biggerThan", ">=");
					$arg = $this->arrayReplaceValue($arg, "lowerThan", "<=");
					$arg = $this->arrayReplaceValue($arg, "regexp", "REGEXP");
					//$arg = array('value') => $arg = array('id', '=', 'value', '');
					if(count($arg) == 1){
						$arg[2] = $arg[0];
						$arg[0] = $this->table.'.id';
						$arg[1] = '=';
						$arg[3] = '';
						$arg[2] = "'".$arg[2]."'";

					}
					//$arg = array('row', 'value') => $arg = array('row', '=', 'value', '');
					elseif(count($arg) == 2){
						$arg[0] = $this->table.'.'.$arg[0];
						$arg[2] = "'".$arg[1]."'";
						$arg[1] = '=';
						$arg[3] = '';
					}
					//$arg = array('row', 'value') => $arg = array('row', '=', 'value', '');
					elseif(count($arg) === 3){
						$arg[0] = $this->table.'.'.$arg[0];
						//var_dump($arg[2]);
						$arg[2] = "'".$arg[2]."'";
						$arg[3] = '';
					}

					elseif(count($arg) === 4){
						$arg[0] = $this->table.'.'.$arg[0];
						$arg[2] = "'".$arg[2]."'";
					}
					$this->sql.= $arg[0].' '.$arg[1].' '.$arg[2].' '.$arg[3].' ';
				}
				break;
			case 'orderBy':
				$order = array();
				$count = 0;
				for($t = 0; $t < count($args); $t++) {
					$order[$count] = $this->table.'.'.$args[$t];
					if(array_key_exists($t+1, $args)){
						if(strtoupper($args[$t+1]) != 'ASC' && strtoupper($args[$t+1]) != 'DESC'){
							$order[$count].=' ASC';
						}else{
							$order[$count].=' '.strtoupper($args[$t+1]);
							$t++;
						}
					}else{
						$order[$count].=' ASC';
					}
					$count++;
				}
				$this->sql.= ' ORDER BY '.implode(', ', $order);
				break;
			case 'limit':
				$this->sql.= ' LIMIT '.implode(', ', $args);
				break;
			case 'rand':
				$this->sql.= ' ORDER BY RAND() ';
				break;
		}
	}

	private function arrayReplaceValue($a, $needle, $replacement){
		$s = array_keys($a, $needle);
		if(count($s) > 0){
			for($i = 0; $i < count($s); $i++){
				$a[$s[$i]] = $replacement;
			}
		}
		return $a;
	}

	public function getSql(){
		return $this->sql;
	}

	public function setSql($sql){
		$this->sql = $sql;
		return $this;
	}

	public function chache($time){
		$this->chacheTime = time();
		return $this;
	}

	public function validate(){
		return NULL;
	}

	public function exec(){
		$this->query($this->sql);
		$this->first();
		for($t = 0; $t < $this->resultCount; $t++){
			$this->__execFromArray($this->hasOne);
			$this->__execFromArray($this->hasMany);
			$this->__execFromArray($this->hasLink);
			$this->next();
		}
		$this->first();
	}

	private function __execFromArray($a){
		if(count($a) > 0){
			for($p = 0; $p < count($a); $p++){
				switch($this->sqlKeyword){
					case 'where':
						$classname = 'Model_'.$a[$p][0];
						$__sql = $this->{'Model_'.$a[$p][0]}->getSql();
						$this->{'Model_'.$a[$p][0]} = new $classname;
						$this->{'Model_'.$a[$p][0]}->setSql($__sql);
						$a[$p][1] = $a[$p][1]==''||$a[$p][1]=='Id'?$this->table.'Id':$a[$p][1];
						if(count($a[$p])<3 || $a[$p][2] === ''){
							$this->{'Model_'.$a[$p][0]}->{"where_".$a[$p][1]}($this->id);
						}
						else
							$this->{'Model_'.$a[$p][0]}->{"where_".$a[$p][2]}($this->{$a[$p][1]});
						if(count($a[$p]) > 3 && is_numeric($a[$p][3])){
							$this->{'Model_'.$a[$p][0]}->limit($a[$p][3]);
						}
						$this->{'Model_'.$a[$p][0]}->exec();
						$this->__current[$a[$p][0]] = &$this->{'Model_'.$a[$p][0]};
						$this->save();
						break;
					/*
					case 'insert':
						$this->{$this->hasOne[$p][0]}->last();
						$this->{$this->hasOne[$p][0]}->{$this->hasOne[$p][1]} = $this->id;
						$this->{$this->hasOne[$p][0]}->insert()->exec();
						break;
					case 'update':
						$this->{$this->hasOne[$p][0]}->last();
						$this->{$this->hasOne[$p][0]}->{$this->hasOne[$p][1]} = $this->id;
						$this->{$this->hasOne[$p][0]}->update()->exec();
						break; */
				}
			}
		}
	}
}
