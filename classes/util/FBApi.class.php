<?php
/**
 * FaceBook Api Class
 *
 * @author Levon Naghashyan
 * @site 	 http://naghashyan.com
 * @email  levon@naghashyan.com
 * @year   2009-2014
 **/
namespace util {
	use Facebook\FacebookSession;
	use Facebook\FacebookRequest;
	use Facebook\GraphUser;
	use Facebook\FacebookRequestException;

	class FBApi {

		private $to;
		private $config;
		private $apiKey;
		private $apiSecret;
		private $getBaseFbUrl;
		private $session_token;
		private $defaultAvatar;
		public $facebook;
		public $fbUserId;

		function __construct($config) {
			FacebookSession::setDefaultApplication(NGS()->config()->fb_api_key, NGS()->config()->fb_api_secret);
			$this->config = $config;
			$this->getBaseFbUrl = $this->config["fb_base_fb_url"];
			$this->facebook = new Facebook( array('appId' => $this->apiKey, 'secret' => $this->apiSecret, ));

		}

		//return fb api key
		public function get_api_key() {
			return $this->apiKey;
		}

		//return fb api secret key
		public function get_api_secret() {
			return $this->apiSecret;
		}

		//return base fb url
		public function get_base_fb_url() {
			return $this->getBaseFbUrl;
		}

		//return fb static root
		public function get_static_root() {
			return 'http://static.ak.'.$this->get_base_fb_url();
		}

		//return fb user id
		public function get_fb_user_id() {
			return $this->facebook->require_login();
		}

		//return fb default avatar
		public function get_fb_default_avatar() {
			return $this->defaultAvatar;
		}

		//return fb user info
		public function getFbUser($uid = null) {
			if (!$uid) {
				$uid = $this->facebook->getUser();
			}
			/* GET FB USER ARRAY
			 * RETURNS ARRAY IF SUCCEEDED, false WHEN NOT
			 */
			try {
				$fql = 'SELECT uid, name, email, username, last_name, first_name, pic_square, status from user where uid = '.$uid;
				$fbUserArr = $this->facebook->api(array('method' => 'fql.query', 'query' => $fql));
				return $fbUserArr[0];
			} catch (Exception $e) {
				// PROBABLY AN EXPIRED SESSION
				return FALSE;
			}
			return $user;
		}

	}

}
