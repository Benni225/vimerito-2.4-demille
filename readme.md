Vimerito 2.4 Demille
======================
Vimerito Demille is a lightweight php framework for PHP.

* It's easy
* It's fast
* Is's flexible

Some features of Vimerito Demille
* model-view-controller-paradigm
* Authentification
* MySql-databases
* JavaScript-support
* Assets
* support for different templateengines
* Layout
* Session
* ...

How using it
---------------------
Creating a controller:

Create the file my.php in the folder `app/controller` and type in
```php
<?php
class Controller_my{

}
```

Now, you can write your "actions". Actions are methods who will be 
public for the user. "indexAction" is the default action of 
every controller. If the call the site *http://www.your-site.com/my* will 
call your Controller_my-class and the indexAction.

```php
<?php 
class Controller_my{
  public function indexAction(){
  	return 'Welcome on my new site!';
  }
}
```
Now, the user will see the text "Welcome on my new site!"
Every action has the suffix "Action".

If you want to use templates and the layout you need only an adapter for 
your favourite templateengine. At the moment the default templateengine is
RainTPL.
```php
<?php 
class Controller_my{
  public $layout = "layout.php";
  public function indexAction(){
  	$view = new View(new RainTplAdapter);
  	$view->load("myview.php");
  	$view->assign("text", "Welcome to my new site!");
  	Layout::get()->add($view, "#content");
  }
}
```
Have you noticed the attribute `$layout`? The value of this 
attribute is a templatefile outgoning from the folder *app/view*.
When we add the view to the layout we have to specify in which 
HTML-element the result of our view copied. This we do with a CSS-selector
like in jQuery (`#content` is an element with the id "content").

If you don't want to use the layout, you have to return the render-result of your
template.

```php
<?php 
class Controller_my{
  public function indexAction(){
  	$view = new View(new RainTplAdapter);
  	$view->load("myview.php");
  	$view->assign("text", "Welcome to my new site!");
  	return $view-render();
  }
}
```

If you want to use the authentification-feature you have to configure the file
*boot.php*. All the configuration of your project will placed here. 
```php
 ...
	'authTable'	=>	'user',
	'authUsername'=>'username',
	'authPassword'=>'password',
 ...
``` 
```php
<?php 
class Controller_my{
  public $layout = "layout.php";
  public function indexAction(){
  	if(Auth::is()){
  	  $view = new View(new RainTplAdapter);
  	  $view->load("myview.php");
  	  $view->assign("text", "Welcome to my new site!");
  	  $view->assign("user", User::get("username");
  	  Layout::get()->add($view, "#content");
  	}else{
  	  $view = new View(new RainTplAdapter);
  	  $view->load("myview.php");
  	  $view->assign("text", "You are not welcome!");
  	  Layout::get()->add($view, "#content");
  	}
  }
}
```
After the authentification you have the possibility to 
use the class `User` to get all information of the user, which saved in 
the databasetable.

For loging a user in use the method `Auth::login($username, md5($password))`.
For loging out use `Auth::logout()` 

To bind in a JavaScript-file use `JavaScript::add($url)` and call it somewhere
in your script (in the *boot.php* or in your actions) that method
```php
  ...
  JavaScript::add(Url::asset('myscript.js'));
  ...
```

The class `Url` helps you to clean your code. `Url::asset` returns URLs to
files in the folder *public* `Url::to` creates links to other controllers and actions

```php
  ...
  Url::to("my@nextside", array("parameter1"=>"value1", "parameter2"=>"value2"));
  ...
```
This will create the URL:
http://www.my-site.com/my/nextside/parameter1/value1/parameter2/value2
and calls the class `Controller_my` and the action `nextsideAction`.

If you want that your site returns a JSON-ressource  use the class `Json`

```php
  ...
  public function myjsonAction(){
    $result = Array(
      'parameter1' => 'value1',
      'parameter2' => 'value2' 
    );
    return Json::returnJson($result);
  }
  ...
```

So far. If you have questions, don't be afraid: ask!

Plans for the future 
--------------------
- [ ] Add an ORM
- [ ] Support for more templateengines
- [ ] A more flexible output-class
- [ ] A more flexible session-class

We need help. Help us to make Vimerito Demille better.







