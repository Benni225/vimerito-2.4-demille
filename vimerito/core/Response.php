<?php
class  Response{
	public static $content = "";
	public static $headers = Array();
	public static $status = 200;
	public function __construct($content, $status = 200, $headers = Array()){
		static::$content = $content;
		static::$status = $status;
		static::$headers = $headers;
	}
 	/**
	 * Adds a new header
	 * @param array $header
	 * @code array("option", "content"[, replace (true/false)]);
	 */
	public static function addHeader(Array $header){
		static::$headers[] = $header;
	}

	public static function status($status){
		static::$status = $status;
	}

	public static function setContent($content){
		static::$content = $content;
	}

	public static function buildHeader(){
		foreach(static::$headers AS $header){
			header("{$header[0]}:{$header[1]}", $header[2]|FALSE);
		}
	}

	public static function buildStatus(){
		$o = "HTTP/1.1 ".static::$status.":";

		switch(static::$status){
			case 100:
				$o.="Continue";
				break;
			case 101:
				$o.="Switching Protocols";
				break;
			case 102:
				$o.="Processing";
				break;
			case 200:
				$o.="OK";
				break;
			case 201:
				$o.="Created";
				break;
			case 202:
				$o.="Accepted";
				break;
			case 203:
				$o.="Non-Authoritative Information";
				break;
			case 204:
				$o.="No Content";
				break;
			case 205:
				$o.="Reset Content";
				break;
			case 206:
				$o.="Partitial Content";
				break;
			case 207:
				$o.="Multi-Status";
				break;
			case 208:
				$o.="Already Reported";
				break;
			case 226:
				$o.="IM Used";
				break;
			case 300:
				$o.="Multiple Choices";
				break;
			case 301:
				$o.="Moved Permanently";
				break;
			case 302:
				$o.="Found";
				break;
			case 303:
				$o.="See Other";
				break;
			case 304:
				$o.="Not Modified";
				break;
			case 305:
				$o.="Use Proxy";
				break;
			case 306:
				$o.="Switch Proxy";
				break;
			case 307:
				$o.="Temporary Redirect";
				break;
			case 308:
				$o.="Permanent Redirect";
				break;
			case 400:
				$o.="Bad Request";
				break;
			case 401:
				$o.="Unathorized";
				break;
			case 402:
				$o.="Payment Required";
				break;
			case 403:
				$o.="Forbidden";
				break;
			case 404:
				$o.="Not Found";
				break;
			case 405:
				$o.="Method Bot Allowed";
				break;
			case 406:
				$o.="Not Acceptable";
				break;
			case 407:
				$o.="Proxy Authentication Required";
				break;
			case 408:
				$o.="Request Timeout";
				break;
			case 409:
				$o.="Conflict";
				break;
			case 410:
				$o.="Gone";
				break;
			case 411:
				$o.="Length Required";
				break;
			case 412:
				$o.="Precondition Failed";
				break;
			case 413:
				$o.="Request Entity Too Large";
				break;
			case 414:
				$o.="Request-URI too Long";
				break;
			case 415:
				$o.="Unsupported Media Type";
				break;
			case 416:
				$o.="Request Range Not Satisfiable";
				break;
			case 417:
				$o.="Expactation Failed";
				break;
			case 422:
				$o.="Unprocessable Entity";
				break;
			case 423:
				$o.="Locked";
				break;
			case 424:
				$o.="Failed Dependency";
				break;
		}
	}

	public static function output(){
		echo static::$content;
	}


}