<?php
/**
 * NGS abstract load all loads should extends from this class
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
	abstract class AbstractLoad extends AbstractRequest {

		protected $params = array();
		private $jsonParam = array();
		private $load_name = "";
		private $isNestedLoad = false;

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
			$ns = substr($ns, strpos($ns, NGS()->getModuleName()) + strlen(NGS()->getModuleName()) + 1);
			$ns = str_replace(array("Load", "\\"), array("", "."), $ns);
			$ns = preg_replace_callback('/[A-Z]/', function($m) {
				return "_".strtolower($m[0]);
			}, $ns);
			$ns = str_replace("._", ".", $ns);
			$nestedLoads = NGS()->getRoutesEngine()->getNestedRoutes($ns);
			$defaultLoads = array_merge($this->getDefaultLoads(),$nestedLoads);
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
		public final function nest($namespace, $loadArr) {
			$actionArr = NGS()->getRoutesEngine()->getLoadORActionByAction($loadArr["action"]);
			$loadObj = new $actionArr["action"];
			//set that this load is nested
			$loadObj->setIsNestedLoad(true);
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
			$this->params["inc"][$namespace]["filename"] = $loadObj->getTemplate();
			$this->params["inc"][$namespace]["params"] = $loadObj->getParams();
			$this->params["inc"][$namespace]["namespace"] = $loadArr["action"];
			$this->params["inc"][$namespace]["jsonParam"] = $loadObj->getJsonParams();
			$this->params["inc"][$namespace]["parent"] = $this->getLoadName();
			$this->params["inc"][$namespace]["permalink"] = $this->getPermalink();
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
		 * this method add json varialble
		 *
		 * @abstract
		 * @access public
		 * @param String $name
		 * @param mixed $value
		 *
		 * @return void
		 */
		public function addJsonParam($name, $value) {
			$this->jsonParam[$name] = $value;
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
		 * Return json params array
		 * @abstract
		 * @access public
		 *
		 * @return array|jsonParam
		 */
		public function getJsonParams() {
			return $this->jsonParam;
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

		/**
		 * set true if load called from parent (if load is nested)
		 * 
		 * @param boolean $isNestedLoad
		 * 
		 * @return void
		 */
		public final function setIsNestedLoad($isNestedLoad) {
			$this->isNestedLoad = $isNestedLoad;
		}
		/**
		 * get true if load is nested
		 * 
		 * @return boolean|$isNestedLoad
		 */
		public final function getIsNestedLoad() {
			return $this->isNestedLoad;
		}

		public final function getLoadType(){
			return "smarty";
		}
		
		public function setLoadName($name) {
			$this->load_name = $name;
		}

		public function getLoadName() {
			return $this->load_name;
		}
		
		public function getPermalink(){
			return null;
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
