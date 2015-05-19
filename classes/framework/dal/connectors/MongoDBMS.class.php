<?php
/**
 * ImprovedDBMS class uses MySQL Improved Extension to access DB.
 * This class provides full transaction support instead of DBMS class.
 *
 * @author Levon Naghashyan
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @package framework
 * @version 6.0
 * @year 2009-2014
 */
namespace ngs\framework\dal\connectors {
	use ngs\framework\exceptions\DebugException;
	class MongoDBMS extends \MongoClient {

		/**
		 * Singleton instance of class
		 */
		private static $instance = NULL;

		/**
		 * Object which represents the connection to a MySQL Server
		 */
		private $dbName = null;
		private $stmt = null;
		/**
		 * Tries to connect to a MySQL Server
		 */
		public function __construct($db_host, $db_user, $db_pass, $db_name) {
			$this->dbName = $db_name;
			if($db_user != "" || $db_pass != ""){
				$mongoAuth = $uri = $db_user.":".$db_pass."@";
			}
			$uri = "mongodb://".$mongoAuth.$db_host."/".$db_name;
			parent::__construct($uri);
		}

		/**
		 * Returns an singleton instance of class.
		 *
		 * @return
		 */
		public static function getInstance($db_host, $db_user, $db_pass, $db_name) {
			if (is_null(self::$instance)) {
				self::$instance = new MongoDBMS($db_host, $db_user, $db_pass, $db_name);
				self::$instance = self::$instance->$db_name;
			}
			return self::$instance;
		}
		
		public function insert($params){
			
		}

	}

}
