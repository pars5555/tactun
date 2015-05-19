<?php
/**
 * NGS abstract load all loads that response is json should extends from this class
 * this class extends from AbstractRequest class
 * this class class content base functions that will help to
 * initialize loads
 *
 * @author Levon Naghashyan
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2014
 * @package framwork
 * @version 6.0
 *
 */
namespace ngs\framework {
	use \ngs\framework\exception\NoAccessException;
	abstract class AbstractJsonLoad extends AbstractRequest {

		protected $params = array();
		private $load_name = "";

		/**
		 * this method use for initialize
		 * load and AbstractRequest initialize function
		 *
		 * @abstract
		 * @access public
		 *
		 * @return void;
		 */
		public function initialize() {
			parent::initialize();
		}

		/**
		 * Return a thingie based on $paramie
		 * @abstract
		 * @access
		 * @param boolean $paramie
		 * @return integer|babyclass
		 */
		public final function service() {

			$this->load();
			$ns = get_class($this);
			$ns = substr($ns, strpos($ns, NGS()->getNamespace()) + strlen(NGS()->getNamespace()) + 1);
			$ns = str_replace(array("Load", "\\"), array("", "."), $ns);
			$ns = preg_replace_callback('/[A-Z]/', function($m) {
				return "_".strtolower($m[0]);
			}, $ns);
			$ns = str_replace("._", ".", $ns);
			$nestedLoads = NGS()->getRoutesEngine()->getNestedRoutes($ns);
			$defaultLoads = array_merge($nestedLoads, $this->getDefaultLoads());
			//set nested loads for each load
			foreach ($defaultLoads as $key => $value) {
				$this->nest($key, $value);
			}

		}

		/**
		 * in this method implemented
		 * nested load functional
		 *
		 * @abstract
		 * @access public
		 * @param String $namespace
		 * @param array $loadArr
		 *
		 * @return void
		 */
		public function nest($namespace, $loadArr) {
			$actionArr = NGS()->getLoadORActionByAction($loadArr["action"]);
			if (!file_exists($actionArr["path"])) {
				$this->onNoAccess("User hasn't access to the load: ".$actionArr["action"]);
			}
			require_once ($actionArr["path"]);
			$loadObj = new $actionArr["action"]();
			
			if (isset($loadArr["args"])) {
				NGS()->setArgs($loadArr["args"]);
			}
			$loadObj->setLoadName($loadArr["action"]);
			$loadObj->initialize();
			$allowLoad = false;
			if (NGS()->getSessionManager()->validateRequest($loadObj) === false) {
				$loadObj->onNoAccess("User hasn't access to the load: ".$actionArr["action"]);
			}

			$loadObj->service();
			
			if (NGS()->getNGSMode() && NGS()->isAjaxRequest()) {
				NGS()->getLoadMapper()->setNestedLoads($this->getLoadName(), $loadArr["action"], $loadObj->getJsonParams());
			}
			if(!isset($this->params["inc"])){
				$this->params["inc"] = array();
			}
			$this->params["inc"][$namespace] = $loadObj->getParams();
		}

		/**
		 * this method add template varialble
		 *
		 * @abstract
		 * @access public
		 * @param String $name
		 * @param mixed $value
		 *
		 * @return void
		 */
		protected final function addParam($name, $value) {
			$this->params[$name] = $value;

		}
		/**
		 * Return params array
		 * @abstract
		 * @access public
		 *
		 * @return array|params
		 */
		public function getParams() {
			return $this->params;
		}

		/**
		 * this abstract method should be replaced in childs load
		 * for add nest laod
		 * @abstract
		 * @access public
		 *
		 * @return array|nestedlaods
		 */
		public function getDefaultLoads() {
			return array();
		}

		/**
		 * this abstract method should be replaced in childs load
		 * for set load template
		 * @abstract
		 * @access public
		 *
		 * @return string|templatePath
		 */
		public function getTemplate() {
			return null;
		}

		/**
		 * Return a thingie based on $paramie
		 * @abstract
		 * @access
		 * @param boolean $paramie
		 * @return integer|babyclass
		 */
		public function isNestable($namespace, $load) {
			return true;
		}

		
		public final function getLoadType(){
			return "json";
		}

		public function setLoadName($name) {
			$this->load_name = $name;
		}

		public function getLoadName() {
			return $this->load_name;
		}
		
		/**
		 * Return a thingie based on $paramie
		 * @abstract
		 * @access
		 * @param boolean $paramie
		 * @return integer|babyclass
		 */
		public function onNoAccess($msg = "") {
			throw new NoAccessException($msg);
		}

		/**
		 * Return a thingie based on $paramie
		 * @abstract
		 * @access
		 * @param boolean $paramie
		 * @return integer|babyclass
		 */
		public abstract function load();

	}

}
