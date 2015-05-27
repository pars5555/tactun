<?php
/**
 * Smarty util class extends from main smarty class
 * provides extra features for ngs
 *
 * @author Levon Naghashyan
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @package uril
 * @version 6.0
 * @year 2010-2014
 */
namespace ngs\framework\templater {
	use ngs\framework\AbstractTemplater;
	require_once (SMARTY_DIR."/Smarty.class.php");
	class NgsTemplater extends AbstractTemplater {

		/**
		 * constructor
		 * reading Smarty config and setting up smarty environment accordingly
		 */
		private $smarty = null;
		private $template = null;
		private $params = array();
		private $smartyParams = array();
		public function __construct() {
		}

		public function smartyInitialize() {
			$this->smarty = new \Smarty();
			$this->smarty->template_dir = TEMPLATES_DIR;
			$this->smarty->setCompileDir(COMPILE_DIR);
			$this->smarty->config_dir = COMPILE_DIR;
			$this->smarty->cache_dir = COMPILE_DIR;
			$this->smarty->compile_check = true;
			$this->smarty->assign("TEMPLATE_DIR", TEMPLATES_DIR);
			$this->smarty->assign("pm", NGS()->getLoadMapper());

			$protocol = "//";

			// register the outputfilter
			$this->smarty->registerFilter("output", array($this, "add_dyn_header"));
			
			$staticPath = $protocol.$_SERVER["HTTP_HOST"];
			if(NGS()->getConfig()->static_path != null){
				$staticPath = $protocol.NGS()->getConfig()->static_path;
			}
			$version = NGS()->getNGSVersion();
			if(isset(NGS()->getConfig()->version)){
				$version = NGS()->getConfig()->version;
			}

			$this->assign("SITE_URL", $_SERVER["HTTP_HOST"]);
			$this->assign("SITE_PATH", $protocol.$_SERVER["HTTP_HOST"]);
			$this->assign("STATIC_PATH", $staticPath);
			$this->assign("TEMPLATE_DIR", TEMPLATES_DIR);
			$this->assign("ENVIRONMENT", ENVIRONMENT);
			$this->assign("VERSION", $version);
			foreach ($this->smartyParams as $key => $value) {
				$this->smarty->assign($key, $value);
			}
		}

		public function assign($key, $value) {
			$this->smartyParams[$key] = $value;
		}

		public function assignJson($key, $value) {
			$this->params[$key] = $value;
		}

		/**
		 * set template
		 *
		 * @param String $template
		 *
		 */
		public function setTemplate($template) {
			$this->template = $template;
		}

		/**
		 * Return a template
		 *
		 * @return String $template|null
		 */
		public function getTemplate() {
			return $this->template;
		}

		public function display() {
			if ($this->getTemplate() == null) {
				$this->diplayJSONResuls();
				return;
			}
			$this->smartyInitialize();
			if (NGS()->isAjaxRequest() && NGS()->getNGSMode()) {
				$this->assignJson("html", $this->smarty->fetch($this->getTemplate()));
				$this->assignJson("nl", NGS()->getLoadMapper()->getNestedLoads());
				$this->diplayJSONResuls();
				return true;
			} elseif ($this->getTemplate() != null) {
				$this->smarty->display($this->getTemplate());
			}
		}

		/**
		 * Return a thingie based on $paramie
		 * @abstract
		 * @access
		 * @param boolean $paramie
		 * @return integer|babyclass
		 */
		private function createJSON($arr) {
			$jsonArr = array();
			if (!isset($arr["status"])) {
				$arr["status"] = "ok";
			}
			if ($arr["status"] == "error") {
				header("HTTP/1.0 403 Forbidden");
				if (isset($arr["code"])) {
					$jsonArr["code"] = $arr["code"];
				}
				if (isset($arr["msg"])) {
					$jsonArr["msg"] = $arr["msg"];
				}
			} else {
				if (isset($arr["_params_"])) {
					$jsonArr = array_merge($jsonArr, $arr["_params_"]);
					unset($arr["_params_"]);
				}
				if (isset($arr["params"])) {
					$jsonArr = array_merge($jsonArr, $arr["params"]);
				} else {
					$jsonArr = array_merge($jsonArr, $arr);
				}
			}
			return json_encode($jsonArr);
		}

		/**
		 * Return a thingie based on $paramie
		 * @abstract
		 * @access
		 * @param boolean $paramie
		 * @return integer|babyclass
		 */
		private function diplayJSONResuls() {
			try {
				header('Content-Type: application/json; charset=utf-8');
				echo $this->createJSON($this->params);
			} catch (Exception $ex) {
				echo $ex->getMessage();
			}

		}

		public function add_dyn_header($tpl_output, $template) {

			$jsString = "";
			$jsString = '<meta name="generator" content="Naghashyan Framework '.NGS()->getNGSVersion().'" />';
			if (!defined("JS_FRAMEWORK_ENABLE") || JS_FRAMEWORK_ENABLE === false) {
				$tpl_output = str_replace('</head>', $jsString, $tpl_output)."\n";
				return $tpl_output;
			}
			$jsString .= '<script type="text/javascript">';
			$jsString .= "NGS.setInitialLoad('".NGS()->getRoutesEngine()->getContentLoad()."', '".json_encode($this->params)."');";
			$jsString .= 'NGS.setTmst("'.time().'");';
			foreach ($this->getCustomJsParams() as $key => $value) {
				$jsString .= $key." = '".$value."';";
			}
			$jsString .= '</script>';
			$jsString .= '</head>';
			$tpl_output = str_replace('</head>', $jsString, $tpl_output);
			if (ENVIRONMENT == "production") {
				$tpl_output = preg_replace('![\t ]*[\r\n]+[\t ]*!', '', $tpl_output);
			}
			return $tpl_output;
		}

		protected function getCustomJsParams() {
			return array();
		}

	}

}
