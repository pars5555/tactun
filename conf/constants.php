<?php
/**
 * default constants 
 * in this file should be store all constants
 *
 * @author Levon Naghashyan
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2015
 * @version 6.0
 * @copyright Naghashyan Solutions LLC
 *
 */
/*
|--------------------------------------------------------------------------
| DEFINNING ENVIRONMENT VARIABLES
|--------------------------------------------------------------------------
*/
$environment = "production";
if (isset($_SERVER["ENVIRONMENT"])) {
	$environment = $_SERVER["ENVIRONMENT"];
}
define("ENVIRONMENT", $environment);
//define error show status
if (ENVIRONMENT != "production") {
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
}

//defaining ngs namespace
define("DEFAULT_NS", "demo");

//defaining ngs namespace
define("JS_FRAMEWORK_ENABLE", true);

/*
|--------------------------------------------------------------------------
| DEFINNING DEFAULT DIRS
|--------------------------------------------------------------------------
*/
//---defining document root
if ($_SERVER["DOCUMENT_ROOT"]) {
	$NGS_root = $_SERVER["DOCUMENT_ROOT"];
} else {
	defined('__DIR__') or define('__DIR__', dirname(__FILE__));
	$NGS_root = str_replace("/conf", "", __DIR__);//for linux system
	$NGS_root = str_replace("\conf", "", $s_root);//for windows system
}
$ngsRoot = substr($NGS_root, 0, strrpos($NGS_root, "/htdocs"));


/*
|--------------------------------------------------------------------------
| DEFINNING DEFAULTS PACKAGES DIRS
|--------------------------------------------------------------------------
*/

//---defining ngs root
define("NGS_ROOT", $ngsRoot);
//---defining classes dir
define("CLASSES_DIR", "classes");


//---defining public dir
define("PUBLIC_DIR", "htdocs");

//---defining config  dir
define("CONF_DIR", "conf");

//---defining data dir 
define("DATA_DIR", "data");

//---defining data bin path
define("BIN_DIR", "bin");

//---defining temp folder path
define("TEMP_DIR", "temp");

//defining load and action directories
define("LOADS_DIR", "loads");
define("ACTIONS_DIR", "actions");

//---defining classes paths
define("CLASSES_PATH", "classes");

//defining routs file path
define("NGS_ROUTS", NGS_ROOT."/conf/routes.json");
//defining database connector class path
define("USE_DBMS", "util\db\ImprovedDBMS");
//defining load mapper path
define("USE_LOAD_MAPPER", "loads\LoadMapper");
//defining session manager path
define("USE_SESSION_MANAGER", "demo\managers\SessionManager");
//defining session manager path
define("USE_TEMPLATE_ENGINE", "util\ImTemplater");

/*
|--------------------------------------------------------------------------
| DEFINNING NGS MODULES
|--------------------------------------------------------------------------
*/
//---defining if modules enabled
define("MODULES_ENABLE", false);
//---defining modules dir
define("MODULES_DIR", "modules");
//---defining modules routing file
define("NGS_MODULS_ROUTS", NGS_ROOT."/conf/modules.json");

/*
|--------------------------------------------------------------------------
| DEFINNING SMARTY DIRS
|--------------------------------------------------------------------------
*/
//---defining smarty root
define("SMARTY_DIR", NGS_ROOT."/classes/lib/smarty/");
//---defining smarty paths
define("TEMPLATES_DIR", NGS_ROOT."/templates/");
define("CACHE_DIR", TEMPLATES_DIR."/cache");
define("COMPILE_DIR", TEMPLATES_DIR."/compile");
define("CONFIG_DIR", TEMPLATES_DIR."/config");


/*
|--------------------------------------------------------------------------
| DEFINNING HOST VARIABLES
|--------------------------------------------------------------------------
*/
//defaining default host
define("HTTP_HOST", $_SERVER["HTTP_HOST"]);
define("SITE_PATH", "//".$_SERVER["HTTP_HOST"]);
$array = explode(".", HTTP_HOST);
define("NGS_HTTP_HOST", (array_key_exists(count($array) - 2, $array) ? $array[count($array) - 2] : "").".".$array[count($array) - 1]);
define("NGS_SITE_PATH", "//".NGS_HTTP_HOST);
