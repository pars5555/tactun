<?php
/**
 * Smarty util class extends from main smarty class
 * provides extra features for ngs 
 *
 * @author Levon Naghashyan
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @package uril.db
 * @version 6.0
 * @year 2009-2014
 */
namespace uril\db; 
require_once("DBMS.class.php");
require_once("ImprovedDBMS.class.php");

/**
 * Returns an instance of DBMS class.
 */
class DBMSFactory {

	/**
	 * Indicates which instance of DBMS classes to use.
	 */
	public static $IMPROVED = true;

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
		$config = parse_ini_file(CONF_PATH."/config.ini");
		self::$config = $config;
	}

	/**
	 * Returns corresponding instance of DBMS class.
	 *
	 * @return
	 */
	public static function getDBMS($config){
		if(is_null(self::$dbmsInstance)) {
			if($config){
					self::$config = $config;
				}
			if(self::$IMPROVED){
				ImprovedDBMS::init(self::$config);
				self::$dbmsInstance = ImprovedDBMS::getInstance();
			}else{
				DBMS::init(self::$config);
				self::$dbmsInstance = DBMS::getInstance();
			}
		}
		return self::$dbmsInstance;
	}
}
?>