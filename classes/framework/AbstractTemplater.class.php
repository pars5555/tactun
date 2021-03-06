<?php
/**
 * Sample File 2, phpDocumentor Quickstart
 *
 * This file demonstrates the rich information that can be included in
 * in-code documentation through DocBlocks and tags.
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @version 2.0
 * @package framework
 * @copyright Naghashyan Solutions LLC
 */

namespace ngs\framework {

	abstract class AbstractTemplater {
		
		/**
		 * this method should handle params store
		 * 
		 * @abstract
		 * @access
		 * @param String $key
		 * @param mixed $value
		 * @return void
		 */
		public abstract function assign($key, $value);
		
		/**
		 * this method should echo stored data (html, json, xml, etc ..)
		 * 
		 * @abstract
		 * @access
		 * @param String $template|null
		 * @return void
		 */
		public abstract function display();
	}

}
