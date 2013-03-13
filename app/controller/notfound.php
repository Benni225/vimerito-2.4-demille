<?php
class Controller_notfound{
	public $layout = "layout.php";
	public function indexAction(){
		$view = new View(new RainTplAdapter());
		$view->load("404.php");
		Layout::get()->add($view, "#content");
	}
}