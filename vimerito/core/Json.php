<?php
class Json{
	public static function _encode_json($j, $i = false, $rc = 0){
		$json = "";
		foreach($j as $key => $value){
			if(!is_numeric($key)) $json.='"'.$key.'":';

			if(is_array($value)){
				$json.= (array_key_exists("resultCount", $value) && $value["resultCount"] > 1)?
					"[\n":"{\n";

				$json.=self::_encode_json($value, true);
				$json.=array_key_exists("resultCount", $value) && $value["resultCount"] > 1?
					"],\n":"\n},";
			}else{
				$json.=is_numeric($value)?$value.",":'"'.$value.'",';
			}

		}

		$json = preg_replace("/,?\"resultCount\":?\d?/", "", $json);
		$json = preg_replace("/(},\s*},)/", "}},", $json);
		$json = preg_replace("/(}},\s*$)/", "}\n}", $json);
		$json = preg_replace_callback("/(\:{1}\"[0-9_a-z_A-Z]*[\r|\n|\r\n]*[0-9_a-z_A-Z]*\")/", function($t){
			return nl2br($t[1]);
		}, $json);
		if($i == true)
			$json = substr($json, 0, -1);

		$json = trim($json);

		if($i == false){
			$json = trim(substr($json, 0, -1));
			$json = str_replace("'", '"', $json);
			//if($rc > 1)
				$json = "[".$json."]";
		}
		//Bugfix -
		$json = preg_replace("/\d]/", "]", $json);
		return $json;
	}
	public static function returnJson(Array $json, $simple = false){
		Response::addHeader(array("Content-Type", "application/json; charset:utf-8;", true));
		if((array_key_exists("resultCount", $json) && $json["resultCount"] > 0) || (count($json) > 0 && !array_key_exists("resultCount", $json))){
			$rc = array_key_exists("resultCount", $json)?$json["resultCount"]:1;
			if($simple == false)
				$json = self::_encode_json($json, false, $rc);
			else
				$json = json_encode($json);
		}else{
			$json = "[]";
		}
		return array("type"	=>"json", "content"=>utf8_encode($json));

	}

	public static function returnJsonAsString(Array $json, $simple = false){
		if((array_key_exists("resultCount", $json) && $json["resultCount"] > 0) || (count($json) > 0 && !array_key_exists("resultCount", $json))){
			$rc = array_key_exists("resultCount", $json)?$json["resultCount"]:1;
			if($simple == false)
				$json = self::_encode_json($json, false, $rc);
			else
				$json = json_encode($json);
		}else{
			$json = "[]";
		}
		return $json;
	}

	public static function toJson(Array $json){
		$json = static::prepareJson($json);
		return json_encode($json, JSON_FORCE_OBJECT);
	}

	private static function prepareJson(Array $json){
 		$p = array();
		foreach($json AS $key=>$value){
			if(is_array($value)){
				if(!is_numeric($key))
					$p[utf8_encode($key)] = static::prepareJson($value);
				else
					$p[] = static::prepareJson($value);
			}else{
				if(empty($value))
					$value = '';
				if(empty($key))
					$key = count($p)-1;
				if(!is_numeric($key))
					$p[utf8_encode((string)$key)] = utf8_encode((string)$value);
				else
					$p[] = utf8_encode((string)$value);
			}
		}
		$obj = json_decode(json_encode($p, FALSE));
		return $obj;
	}

	private static function writeJson($json){
		$j = "";
		$notAList = false;
		foreach($json AS $key=>$value){
			if(is_numeric($key))
				$key = null;
			if(gettype($value) == "array"){
				$ks = array_keys($value);
				foreach($ks as $k){
					//is not a list
					if(!is_numeric($k))
						$notAList = true;
				}
				if($notAList === false)
					$j.= "[".implode(",", $value)."]";
				else{
					$j.="{";
					foreach($value as $k=>$v){
						if(!is_array($value))
							$j.="'$k':$value,";
						else
							$j.="'$k':{".self::writeJson($value)."},";
					}
					$j= substr($j, 0, -1)."}";
				}
			}
		}
		return $j;
	}
}
