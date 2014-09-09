<?php
	class Lang{
		private static $langs = array();
		private static $lang;
		/**
		Adds a new language
		@param string Is the a short id for the language, like: "de", "en_GB", "en_USA".
		@param array Is an array including the language-strings 
		@return nothing
		@example
		<?php
		Lang::add("de", array(
			"welcome"	=>	"Willkommen",
			"welcomeText"	=>	"Willkommen auf meiner Seite!"
		));
		?>
		*/
		public static function add((string)$id, $lang){
			self::$langs[$id] = $lang;
		}
		/**
		Sets the actual language.
		@param string Is the a short id for the language, like: "de", "en_GB", "en_USA".
		@return nothing
		*/
		public static function setLang((string)$id){
			self::$lang = $id
		}
		/**
		Returns a text of the actual language.
		@param string Is the id of the string.
		@return string
		@example
		<?php
		Lang::add("de", array(
			"welcome"	=>	"Willkommen",
			"welcomeText"	=>	"Willkommen auf meiner Seite!"
		));
		echo '<b>'.Lang::get("welcome").'</b><p>'.Lang::get("welcomeText").'</p>';
		?>
		*/
		public static function get($stringId){
			if(array_key_exists(self::$lang, self::$langs)){
				if(array_key_exists($stringId, self::$langs[self::$lang]){
					return self::$langs[self::$lang][$stringId];
				}else{
					trigger_error('This string '.$stringId.' does not exist in the language '.self::$lang.'.', E_USER_Notice);
				}
			}else{
				trigger_error('The language '.self::$lang.' was not found.', E_USER_WARNING);
			}
		}
	}