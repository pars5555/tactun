<?php
namespace loads {
	require_once (CLASSES_PATH . "/framework/routes/NgsLoadMapper.class.php");

	class LoadMapper extends \framework\routes\NgsLoadMapper {

		private $urls = null;
		public $isStatic = false;
		private $SITE_PATH;

		public function getParam($name) {

			if ($this->isStatic) {
				return false;

			}
			return parent::getParam($name);
		}

		public function getContentLoadName() {
			$page = "home";
			if ($_REQUEST["p"]) {
				$page = $_REQUEST["p"];
			}
			return $page;
		}

	}

}
