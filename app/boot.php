<?php
Config::set(array(
	'baseURL'	=>	'www.my-site.com',
	/**
	 * This controller will be called if you type in the URL
	 * www.my-site.com or http://localhost/myproject
	 */
	'defaultController'	=>	'lesson1',
	'defaultAction'	=>	'index',
	/**
	 * This controller called if a site could not
	 * found.
	 */
	'404Controller' =>	'notfound',
	'404Action'		=>	'index',
	/**
	 * Configuring the template-features
	 */
	'viewCacheDir'	=>	'cache',
	'viewCheckUpdate'=>	true,
	'viewFileExtension'=>'php',
	'viewEnablePHP'	=>	true,
	/**
	 * The default templateengine
	 */
	'layoutAdapter'	=>	'RainTplAdapter',
	/**
	 * Configure the authentication
	 */
	'authTable'	=>	'user',
	'authUsername'=>'username',
	'authPassword'=>'password',
	/**
	 * Configuration for the default database
	 */
	'defaultDatabase'=>array(
		'server'=>'localhost',
		'port'=>'',
		'username'=>'root',
		'password'=>'',
		'database'=>'',
		'newLink'=>'',
		'flags'=>''
	)
));
/**
 * All links you create with the class "Url"
 * will be locale ones (http://localhost/myproject/controller)
 */
Application::development(true);
/**
 * For binding in JavaScript use the class "JavaScript".
 *
 * At the moment you have to use it in connection
 * with the layout-class.
 *
 * This method receives the URL to a JavaScript-file and
 * includes it the HEAD-element of your layout.
 *
 */
JavaScript::add(Url::asset("javascript/jquery-1.9.1.js"));

