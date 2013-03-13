<?php
class MySqlQuerybuilder extends aQuerybuilder implements iQuerybuilder{
	private static $instance = NULL;
	public static function create(){
		if(self::$instance === NULL){
			self::$instance = new self;
		}
		return self::$instance;
	}
	public static function select(){
		$args = func_get_args();
		foreach ($args AS $value){
			$value = "`".$value."`";
		}
		return "SELECT ".implode(", ", $args);
	}

	public static function from(){
		$args = func_get_args();
		foreach ($args AS $value){
			$value = "`".$value."`";
		}
		return "FROM ".implode(", ", $args);
	}

	public static function delete(){
		return "DELETE";
	}

	public static function orderBy(){
		$args = func_get_args();
		$orders = Array();
		foreach($args AS $column=>$order){
			$orders[] = "`".$column."` ".$order;
		}
		return "ORDER BY ".implode(", ", $orders);
	}

	public static function limit(){
		$args = func_get_args();
		return "LIMIT ".implode(", ", $args);
	}

	public static function update(){
		$args = func_get_args();
		foreach ($args AS $value){
			$value = "`".$value."`";
		}
		return "UPDATE (".implode(", ", $args).")";
	}

	public static function where(){
		$args = func_get_args();
		$clauses = "";
		foreach($args AS $column=>$clause){
			if(is_array($clause) && count($clause) == 1){
				$clause[1] = $clause[0];
				$clause[0] = "=";
			}elseif(!is_array($clause)){
				$_t = array();
				$_t[0] = "=";
				$_t[1] = $clause;
				$clause = NULL; $clause = $_t;
			}
			$clauses = "`".$column."` ".$clause[0]." '".$clause[1]."'";
		}
		return "WHERE ".$clauses;
	}

	public static function _and(){
		return "AND";
	}

	public static function _or(){
		return "OR";
	}
}
