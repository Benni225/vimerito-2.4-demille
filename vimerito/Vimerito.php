<?php
defined("__BASEDIR") or define("__BASEDIR", dirname($_SERVER['DOCUMENT_ROOT'].$_SERVER['PHP_SELF'])."/");
defined("__SCRIPTDIR") or define("__SCRIPTDIR", __BASEDIR.'vimerito/');
defined("DS") OR define("DS", "/");
require_once __SCRIPTDIR.'Autoload.php';
class Marvel{

}
spl_autoload_register(array('Autoload', 'load'));
error_reporting(E_ALL);