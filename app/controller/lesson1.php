<?php
/**
 * Every controller is placed in the "controller"-dir or in
 * a subfolder. Every part of the classname seperated by a "_"
 * is a part of the file path. The class "Controller_admin_board"
 * will be placed in the file "app/controller/admin/board.php".
 * "Controller" every time has to begin with a capital letter.
 * This principle applies to every class in the app-folder.
 */
class Controller_lesson1{
	/**
	 * If you want to use a layout, do it just like that.
	 * Create the public attribute "layout". The value is the
	 * path to your layoutfile outgoing from "app/view/".
	 * If you don't want to use the layout, don't create that attribute.
	 */
	public $layout = "layout.php";
	/**
	 * The indexAction-method is the initial method of every
	 * controller. If this controller called, but no action
	 * this action will called by default. Every action has the suffix "Action".
	 * Only actions can called by the user.
	 */
	public function indexAction(){
		/**
		 * A view includes your template. Vimerito works with
		 * adapters for using different templateengines.
		 * At the moment Vimerito uses RainTPL, a free templateengine.
		 * More adapters follow in the future.
		 */
		$view = new View(new RainTplAdapter());
		/**
		 * Like the layoutfile, outgoing from "app/view"
		 */
		$view->load("lesson1.php");
		/**
		 * Sending variables to the template.
		 */
		$view->assign("title", "This is Vimerito Demille!");
		$view->assign("text", "Thank you for using this microframework.");
		/**
		 * Adds $view to the layout. Similar to jQuery
		 * the 2. parameter is a CSS-selector, in this case
		 * a HTML-element with the id "content".
		 *
		 * If you using the layout, don't do any return.
		 */
		Layout::get()->add($view, "#content");
	}

	public function noLayoutAction(){
		/**
		 * If you don't want to use the layout, load your
		 * templatefile and return the result of the rendering.
		 */
		$view = new View(new RainTplAdapter());
		$view->load("lesson1.php");
		$view->assign("title", "This is Vimerito Demille!");
		$view->assign("text", "Thank you for using this microframework.");

		return $view->render();
	}
}