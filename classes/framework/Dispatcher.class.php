<?php
/**
 *
 * @author Levon Naghashyan
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2009-2014
 * @package framwork
 * @version 6.0
 * @copyright Naghashyan Solutions LLC
 *
 */
namespace ngs\framework {
	use \ngs\framework\exception\ClientException;
	use \ngs\framework\exception\RedirectException;
	use \ngs\framework\exception\NoAccessException;
	use \ngs\framework\exception\NotFoundException;

	class Dispatcher {

		protected $toCache = false;
		/**
		 * Return a thingie based on $paramie
		 * @abstract
		 * @access
		 * @param boolean $paramie
		 * @return integer|babyclass
		 */
		public function __construct() {

			$uri = isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : "";
			if (strpos($uri, "?") !== false) {
				$uri = substr($uri, 0, strpos($uri, "?"));
			}
			if (defined("MODULES_DIR")) {
				$moduleArr = NGS()->getModulesRoutesEngine()->getModule(null, $uri);
				NGS()->setModuleName($moduleArr["module"]);
				$uri = $moduleArr["uri"];
				//include module constant if exsist
				$moduleConstatPath = NGS()->getRootDirByModule($moduleArr["module"])."/".CONF_DIR."/constants.php";
				if (file_exists($moduleConstatPath)) {
					require_once $moduleConstatPath;
				}
			}
			$this->dispatch(NGS()->getRoutesEngine()->getDynamicLoad($uri));
		}

		/**
		 * Return a thingie based on $paramie
		 * @abstract
		 * @access
		 * @param boolean $paramie
		 * @return integer|babyclass
		 */
		private function dispatch($loadArr) {
			try {
				if ($loadArr["matched"] === false) {
					throw NGS()->getNotFoundException();
				}
				NGS()->getTemplateEngine();
				if (isset($loadArr["args"])) {
					NGS()->setArgs($loadArr["args"]);
				}
				switch ($loadArr["type"]) {
					case 'load' :
						$this->loadPage($loadArr["action"]);
						break;
					case 'action' :
						$this->doAction($loadArr["action"]);
						break;
					case 'file' :
						$this->streamStaticFile($loadArr);
						break;
				}
			} catch(exception\ClientException $ex) {
				$errorArr = $ex->getErrorParams();
				header('Content-Type: application/json; charset=utf-8');
				header("HTTP/1.0 403 Forbidden");
				echo json_encode($errorArr);
				exit();

			} catch(JsonException $ex) {
				$this->diplayJSONResuls($ex->getMsg());
			} catch(exception\RedirectException $ex) {
				$this->redirect($ex->getRedirectTo());
			} catch(Exception $ex) {
				throw NGS()->getNotFoundException();
			}
		}

		/**
		 * Return a thingie based on $paramie
		 * @abstract
		 * @access
		 * @param boolean $paramie
		 * @return integer|babyclass
		 */
		public function loadPage($action) {

			try {
				if (class_exists($action) == false) {
					throw NGS()->getNotFoundException();
				}
				$loadObj = new $action;
				$loadObj->initialize();
				if ($this->validateRequest($loadObj)) {
					$this->toCache = $loadObj->toCache();
					$loadObj->setLoadName(NGS()->getRoutesEngine()->getContentLoad());
					$loadObj->service();
					NGS()->getTemplateEngine()->setTemplate($loadObj->getTemplate());
					if ($loadObj->getLoadType() == "smarty") {
						//passing arguments
						NGS()->getTemplateEngine()->assign("ns", $loadObj->getParams());
						NGS()->getTemplateEngine()->assignJson("params", $loadObj->getJsonParams());
					} else if ($loadObj->getLoadType() == "json") {
						NGS()->getTemplateEngine()->assignJson("_params_", $loadObj->getParams());
					}
					$this->displayResult();
					return;
				}

				if ($loadObj->onNoAccess()) {
					return;
				}

			} catch(exception\NoAccessException $ex) {
				$loadObj->onNoAccess();
			}
			throw NGS()->getNotFoundException();
		}

		/**
		 * Return a thingie based on $paramie
		 * @abstract
		 * @access
		 * @param boolean $paramie
		 * @return integer|babyclass
		 */
		private function doAction($action) {

			try {
				if (class_exists($action) == false) {
					throw NGS()->getNotFoundException();
				}
				$actionObj = new $action;
				$actionObj->initialize();
				if ($this->validateRequest($actionObj)) {
					$actionObj->service();
					//passing arguments
					NGS()->getTemplateEngine()->assignJson("params", $actionObj->getParams());
					$this->displayResult();
					return;
				}

				if ($loadObj->onNoAccess()) {
					return;
				}

			} catch(exception\NoAccessException $ex) {
				$loadObj->onNoAccess();
			}
			throw NGS()->getNotFoundException();

		}

		private function streamStaticFile($fileArr) {
			$stramer = NGS()->getFileStreamerByType($fileArr["file_type"]);
			if ($stramer == false) {
				return false;
			}
			$stramer->streamFile($fileArr["file_url"]);
		}

		/**
		 * Return a thingie based on $paramie
		 * @abstract
		 * @access
		 * @param boolean $paramie
		 * @return integer|babyclass
		 */
		private function validateRequest($request) {
			$user = NGS()->getSessionManager()->getUser();
			if ($user->validate()) {
				if (NGS()->getSessionManager()->validateRequest($request, $user)) {
					return true;
				}
			}
			return false;
		}

		/**
		 * Return a thingie based on $paramie
		 * @abstract
		 * @access
		 * @param boolean $paramie
		 * @return integer|babyclass
		 */
		private function displayResult() {
			NGS()->getTemplateEngine()->display();

		}

	}

}
