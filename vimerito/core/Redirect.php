<?php
class Redirect{
	public static function toController($redirection, $parameters = Array()){
		$redirection = explode("@", $redirection);
		return array(
			"type"=>"redirectionController",
			"controller"=>$redirection[0],
			"action"=>!empty($redirection[1])?$redirection[1]:NULL,
			"parameters"=>$parameters
		);
	}

	public static function to($redirection, $parameters = Array()){
		$redirection = explode("@", $redirection);
		return array(
			"type"=>"redirection",
			"controller"=>$redirection[0],
			"action"=>!empty($redirection[1])?$redirection[1]:NULL,
			"parameters"=>$parameters
		);
	}


}