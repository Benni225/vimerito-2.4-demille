Vimerito 2.4 Demille
======================
Vimerito Demille is a lightweight php framework for PHP.

* It's easy
* It's fast
* It's flexible

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

Now, you can write your "actions". Actions are methods which will be 
public for the user. "indexAction" is the default action of 
every controller. If the user call the site *http://www.your-site.com/my* Vimerito will 
call the Controller_my-class and the action indexAction.

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
  	//Set the pagetitle
  	Layout::get()->setTitle("My new page");
  	//Insert your view the layout
  	Layout::get()->add($view, "#content");
  }
}
```
Do you noticed the attribute `$layout`? The value of this 
attribute is a templatefile outgoning from the folder *app/view*.
When we add the view to the layout we have to specify in which 
HTML-element the result of our view copied. You specify it with a CSS-selector
like in querySelector or jQuery in JavaScript (`#content` is an element with the id "content").

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
  Url::to("my@nextsite", array("parameter1"=>"value1", "parameter2"=>"value2"));
  ...
```
This will create the URL:
http://www.my-site.com/my/nextsite/parameter1/value1/parameter2/value2
and calls the class `Controller_my` and the action `nextsiteAction`.

If you want that your site returns a JSON-ressource  use the class `Json`

```php
  ...
  public function myjsonAction(){
    $result = Array(
      'parameter1' => 'value1',
      'parameter2' => 'value2' 
    );
    return Json::returnJson($result, true);
  }
  ...
```
Use models:
```php
  ...
  //create  a mysql-table
  CREATE TABLE user(
    `id` INT(32) PRIMARY KEY AUTO_INCREMENT
    `email` VARCHAR(50),
    `username` VARCHAR(20),
    `password` VARCHAR(50)
  )
  ...
  //create a new file "app/model/user.php"
  <?php
  class Model_user extends Model{
    public function __construct(){
    	$this->table = "user";
    }
  }
  
  
  //Use your model in your controler
  ...
  $myUser = new Model_user;
  //Find a user with the ID 12
  $myUser->where_id_is("12")->exec();
  
  $newUser = new Model_user;
  $newUser->id = 13;
  $newUser->username = "Gustav";
  $newUser->email = "gustav@hotmail.com";
  $newUser->password = md5("gustavpassword");
  $newUser->save();
  $newUser->insert()->exec();
  
  $updateUser = new Model_user;
  $updateUser->id = 13;
  $updateUser->username = "Gustav";
  $updateUser->password = md5("new_gustavpassword");
  //update by ID 13
  $updateUser->update()->exec();
  //or update by username
  $updateUser->update("username")->exec();
  
  //find Gustav by email an password
  $findUser = new Model_user;
  $findUser->where_email_is("gustav@hotmail.com")->andWhere_password_is(md5("new_gustavpassword"))->exec();
  
  //echo username
  echo $findUser->username;
  
  //find all user ordered by username
  $findUser->all()->orderBy("username", "DESC")->exec();
  
  //run through the model
  for($i = 0; $i < $findUser->resultCount; $i++){
  	echo $username."<br />";
  	$findUser->next();
  }
  
  //return your users as a JSON
  return Json::returnJson($findUser->toArray());
  ...
```
Add a new table profile and posts
```php
  ...
  //create  a mysql-table
  CREATE TABLE profile(
    `id` INT(32) PRIMARY KEY AUTO_INCREMENT,
    `userId` INT(32),
    `name` VARCHAR(20),
    `firstname` VARCHAR(20),
    `address` VARCHAR(50)
  )
  
    CREATE TABLE posts(
    `id` INT(32) PRIMARY KEY AUTO_INCREMENT,
    `profileId` INT(32),
    `title` VARCHAR(20),
    `text` VARCHAR(20),
    `date` TIMESTAMP
  )
  ...
  
  //Create the profilemodel in app/model/profile.php
   class Model_profile extends Model{
  	public function __construct(){
  		$this->tablename = "profile";
  		//load all posts
  		$this->hasMany("posts");
  	}
  }
  
  //Create the postsmodel in app/model/posts.php
   class Model_posts extends Model{
  	public function __construct(){
  		$this->tablename = "profile";
  	}
  }
  
  //Change the usermodel
  class Model_user extends Model{
    public function __construct(){
    	$this->table = "user";
    	//loads the profile
    	$this->hasOne("profile");
    }
  }
  
  ...
  $user = new Model_user;
  $user->where_id_is(13)->exec();
  echo $user->profile->firstname."<br /><br />";
  for($i = 0; $i < $user->profile->posts->resultCount; $i++){
  	echo $user->profile->posts->title."<br />";
  }
```




So far. If you have questions, don't be afraid: ask!

Plans for the future 
--------------------
- [ ] Support for more templateengines
- [ ] A more flexible session-class

We need help. Help us to make Vimerito Demille better.







