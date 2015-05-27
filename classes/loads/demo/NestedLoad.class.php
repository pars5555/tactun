<?php
/**
 * @author Levon Naghashyan
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2014
 * @package loads.site.main
 * @version 6.0
 */
namespace loads\site\main {
	use security\RequestGroups;
	use loads\site\SiteLoad;

	class MainLoad extends SiteLoad {

		public function load() {

		}

		public function isValidLoad($namespace, $load) {
			return true;
		}

		public function getTemplate() {
			return TEMPLATES_DIR."/main/index.tpl";
		}

		public function getRequestGroup() {
			return RequestGroups::$guestRequest;
		}

	}

}
