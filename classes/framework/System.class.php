<?php
/**
 * Base ngs class
 * for static function that will
 * vissible from any classes
 *
 * @author Levon Naghashyan
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2014
 * @package framwork
 * @version 6.0
 *
 */

use framework\exception\DebugException;
class System {

	private static $instance = null;
	private $config = null;
	private $args = array();
	private $loadMapper = null;
	private $sessionManager = null;
	private $tplEngine = null;
	private $fileUtils = null;
	private $jsBuilder = null;
	private $cssBuilder = null;

	//! A constructor.
	public function __construct() {
	}

	/**
	 * Returns an singleton instance of this class
	 *
	 * @return object NGS
	 */
	public static function getInstance() {
		if (self::$instance == null) {
			self::$instance = new System();
		}
		return self::$instance;
	}

	/**
	 * static function that return ngs
	 * fileutils if defined by user it return it if not
	 * return ngs default fileutils
	 *
	 * @return Object fileUtils
	 */
	public function getJsBuilder() {
		if ($this->jsBuilder != null) {
			return $this->jsBuilder;
		}
		if (defined("JS_BUILDER")) {
			if (!file_exists(JS_BUILDER)) {
				throw new DebugException("JS UTILS NOT FOUND, please check in constants.php JS_BUILDER variable");
			}
			require_once (JS_BUILDER);
			$fileName = basename(JS_BUILDER);
			$className = substr($fileName, 0, strrpos($fileName, ".class"));
			$ns = str_replace(CLASSES_PATH."/", "", JS_BUILDER);
			$ns = substr($ns, 0, strrpos($ns, "/"));
			$fullLoadName = $ns."\\".$className;
			$this->jsBuilder = new $fullLoadName();
			return $this->jsBuilder;
		}
		require_once (CLASSES_PATH."/framework/util/JsBuilder.class.php");
		$this->jsBuilder = new framework\util\JsBuilder();
		return $this->jsBuilder;
	}

	/**
	 * static function that return ngs
	 * fileutils if defined by user it return it if not
	 * return ngs default fileutils
	 *
	 * @return Object fileUtils
	 */
	public function getCssBuilder() {
		if ($this->cssBuilder != null) {
			return $this->cssBuilder;
		}
		if (defined("CSS_BUILDER")) {
			if (!file_exists(JS_BUILDER)) {
				throw new DebugException("CSS UTILS NOT FOUND, please check in constants.php CSS_BUILDER variable");
			}
			require_once (CSS_BUILDER);
			$fileName = basename(CSS_BUILDER);
			$className = substr($fileName, 0, strrpos($fileName, ".class"));
			$ns = str_replace(CLASSES_PATH."/", "", CSS_BUILDER);
			$ns = substr($ns, 0, strrpos($ns, "/"));
			$fullLoadName = $ns."\\".$className;
			$this->cssBuilder = new $fullLoadName();
			return $this->cssBuilder;
		}
		require_once (CLASSES_PATH."/framework/util/CssBuilder.class.php");
		$this->cssBuilder = new framework\util\CssBuilder();
		return $this->cssBuilder;
	}

	/**
	 * this method return ngs loads dir
	 *
	 * @return String $loadsPackage
	 */

	public function getLoadsPackage() {
		$loadsPackage = "";
		if (defined("LOADS_DIR")) {
			$loadsPackage = CLASSES_PATH."/".LOADS_DIR;
		}
		return $loadsPackage;
	}

	/**
	 * this method return ngs action dir
	 *
	 * @return String $loadsPackage
	 */
	public function getActionPackage() {
		$actionPackage = "";
		if (defined("ACTIONS_DIR")) {
			$actionPackage = CLASSES_PATH."/".ACTIONS_DIR;
		}
		return $actionPackage;
	}

	/**
	 * this method returd file path and namsepace form action
	 * @static
	 * @access
	 * @return String $namespace
	 */
	public function getLoadORActionByAction($action) {
		if (!isset($action)) {
			return false;
		}
		$ngsPrefix = $this->getNamespace();
		if (isset($ns)) {
			$ngsPrefix = "/".$ngsPrefix;
		}
		preg_match_all("/[a-zA-Z_]+$/", $action, $matches);
		$command = $matches[0][0];
		$package = str_replace(".".$command, "", $action);
		$isAction = false;
		$pathPrefix = $this->getLoadsPackage();
		$ns = LOADS_DIR."\\";
		$classPrefix = "Load";
		if (strrpos($command, "do_") !== false) {
			$isAction = true;
			$command = str_replace("do_", "", $command);
			$pathPrefix = $this->getActionPackage();
			$ns = ACTIONS_DIR."\\";
			$classPrefix = "Action";
		}
		$command = preg_replace_callback("/_(\w)/", function($m) {
			return strtoupper($m[1]);
		}, ucfirst($command)).$classPrefix;
		$filePath = $pathPrefix."/".$ngsPrefix."/".str_replace(".", "/", $package)."/".$command.".class.php";
		$ns .= NGS()->getNamespace()."\\".str_replace(".", "\\", $package);
		return array("path" => $filePath, "action" => $ns."\\".$command, "isAction" => $isAction);

	}

	/**
	 * detect if request call from ajax or not
	 * @static
	 * @access
	 * @return bool|true|false
	 */
	public function isAjaxRequest() {
		if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"] == "XMLHttpRequest") {
			return true;
		}
		return false;
	}

}

function System() {
	return System::getInstance();
}
