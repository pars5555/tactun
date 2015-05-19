<?php
/**
 * FormValidator class contains utility functions for working with html form value validation.
 *
 * @author Levon Naghashyan
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2013-2015
 */
namespace util {
	class FormValidator {

		/**
		 * Validate email adress
		 *
		 * @param string $str
		 * @param bool $retMsg
		 * @return string or bool
		 */
		public static function validateEmail($str, $retMsg = true) {
			$email = FormValidator::secure($str);
			if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				return true;
			}
			return "Please enter valid email";
		}

		/**
		 * Validate string
		 *
		 * @param string $str
		 * @param bool $retMsg
		 * @return string or bool
		 */
		public static function validateString($str, $len = 4, $allowChars = "/^[A-Za-z0-9\_\-\.]*$/", $retMsg = true) {
			$str = FormValidator::secure($str);
			if (empty($str)) {
				return "You can't leave this empty.";
			}
			if ($len) {
				if (strlen($str) < $len || strlen($str) > 30) {
					return "Please use between ".$len." and 30 characters.";
				}
			}

			if ($allowChars) {
				if (!preg_match($allowChars, $str)) {
					return "Please use only letters (a-z), numbers, and periods.";
				}
			}
			return true;
		}

		/**
		 * Validate string
		 *
		 * @param string $str
		 * @param bool $retMsg
		 * @return string or bool
		 */
		public static function validatePasswords($pass, $rePass) {
			$pass = FormValidator::secure($pass);
			$rePass = FormValidator::secure($rePass);
			if ($pass !== $rePass) {
				return "These passwords don't match. Try again?";
			}
			return true;
		}

		public static function secure($var) {
			return trim(htmlspecialchars(strip_tags($var)));
		}

	}

}
