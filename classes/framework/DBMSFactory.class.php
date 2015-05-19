<?php
/**
 * DBMSFactory class provides db connections
 *
 * @author Levon Naghashyan
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @package framework
 * @version 6.0
 * @year 2009-2014
 */
namespace framework{
	use util\db\ImprovedDBMS;
	require_once (CLASSES_PATH."/util/db/ImprovedDBMS.class.php");

	/**
	 * Returns an instance of DBMS class.
	 */
	class DBMSFactory {

		private static $config;

		private static $dbmsInstance = null;

		/**
		 * Not used.
		 */
		function __construct() {

		}

		/**
		 * Should be called before getDBMS() function to initialize
		 * $config property.
		 *
		 * @param object $config
		 * @return
		 */
		public static function init($config) {
			self::$config = $config;
		}

		/**
		 * Returns corresponding instance of DBMS class.
		 *
		 * @return
		 */
		public static function getDBMS($config) {
			if (is_null(self::$dbmsInstance)) {
				self::$dbmsInstance = ImprovedDBMS::getInstance($config);
			}
			return self::$dbmsInstance;
		}

	}

}
?>