<?php
/**
 * main site load
 *
 * @author Levon Naghashyan
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2015
 * @package loads
 * @version 6.0
 *
 */
namespace loads\site {
	use \security\RequestGroups, \managers\users\UserManager;
	/**
	 * General parent load for all ngs loads
	 */
	abstract class SiteLoad extends \loads\NgsLoad {

		/**
		 * Initializes translations array for selected language.
		 *
		 * @return
		 */
		public function __construct() {
		}

		//! A constructor.
		public function initialize() {
			parent::initialize();
		}

	}

}
