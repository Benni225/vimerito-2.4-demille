<?php
class Controller_lesson2{
	public $layout = "layout.php";

	public function indexAction(){
		/**
		 * This is serverside redirection!
		 * The first parameter is the controller and / or the action
		 * seperated by a "@". Leave the action and the "@" if you only want
		 * to redirect to the indexAction of a controller, like:
		 * Redirect::to("lesson2")
		 * If you want send data use the second parameter.
		 * Take an assoziativ-array.
		 */
		return Redirect::to("lesson2@whereAreWeNow", array(
			"message" => "you have been redirected!"
		));
	}

	public function whereAreWeNowAction(){
		$view = new View(new RainTplAdapter());
		$view->load("lesson2.php");
		/**
		 * To read out the data, we sended with the redirection-method
		 * we use the class "Get". It contains the data from the URL and all the data
		 * of the global $_GET.
		 * And POST-data? Yes, you're right. This data you get by the class
		 * "Post": Post::getValue("my-data"); for example.
		 */
		$view->assign("message", Get::getValue("message"));
		Layout::get()->add($view, "#content");
	}
}