<?php
/*!
 *  ImprovedDBMS class uses MySQL Improved Extension to access DB.
 *  This class provides full transaction support instead of DBMS class.
 *	Year 2014
 *	@author	Levon Naghashyan
 *	@site	http://naghashyan.com
 *	@mail	levon@naghashyan.com
 */
require_once (CLASSES_PATH . "/util/HttpGetRequest.class.php");
class ImprovedDBMS {

	/**
	 * Singleton instance of class
	 */
	private static $instance = NULL;

	/**
	 * Object which represents the connection to a MySQL Server
	 */
	private $url;

	/**
	 * DB configuration properties
	 */
	private static $solr_host;

	/**
	 * Tries to connect to a MySQL Server
	 */
	private function __construct() {
		$this->url = self::$solr_host;
		$this->httpGetRequest = new HttpGetRequest();
	}

	/**
	 * Initializes MySQL Server connection properties
	 *
	 * @param object $config - associative array, which contains properties
	 * @return
	 */
	public static function init($config) {
		self::$solr_host = $config['solr_path'];
	}

	/**
	 * Returns an singleton instance of class.
	 *
	 * @return
	 */
	public static function getInstance() {
		if (is_null(self::$instance)) {
			self::$instance = new ImprovedDBMS();
		}
		return self::$instance;
	}

	/////////////////////////// common functions /////////////////////////

	/**
	 * Returns TRUE on success or FALSE on failure.
	 * For SELECT, SHOW, DESCRIBE or EXPLAIN will return a result object.
	 */
	public function query($url, $params, $params = false) {
		if (is_array($params)) {
			$this->httpGetRequest->setParams($params);
		}
		$ret = $this->httpGetRequest->request($this->url."?".$url);
		$answerBody = $this->httpGetRequest->getBody();

		if ($answerBody) {
			$result = json_decode($answerBody, true);
		}
		return $result["response"];
	}


}
?>