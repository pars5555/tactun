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
namespace util {
	class ImTemplater extends \framework\templater\NgsTemplater {

		/**
		 * constructor
		 * reading Smarty config and setting up smarty environment accordingly
		 */
		public $smarty = null;
		private $params = array();
		public function __construct() {
			parent::__construct();
			$this->assign("STATIC_URL", NGS()->getConfig()->static_path);
			$this->assign("STATIC_PATH", "//".NGS()->getConfig()->static_path);
			$this->assign("userId", NGS()->getSessionManager()->getUserId());
		}
		
		public function getCustomJsParams(){
			return array("FB_APP_KEY"=>NGS()->getConfig()->fb_api_key);
		}

	}

}
