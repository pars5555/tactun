<?php
namespace util {
	class CssBuilder {

		private $config = null;

		public function __construct($config) {
			$this->config = $config;
		}

		public function initStyles($styleArr, $outDir, $outFileName) {
			if (ENVIRONMENT == "development") {
			//	$this->doDevOutput($styleArr);
			}

			if ($_SERVER["ENVIRONMENT"] == "development") {
				//	doDevOutput($styleArr);
				//return true;
			}
			$outFilePath = $outDir."/out/".$outFileName.$config["VERSION"].".css";
			$outUrl = '//'.$this->config["static_path"]."/css/".$outDir;
			if (is_file($outFilePath)) {
				doCacheOutput($outUrl);
			} else {
				$copyright = "/**\n* @author Avetis Sahakyan, Levon Naghashyan\n* @copyright Naghashyan Solutions LLC\n* @site http://naghashyan.com\n* @mail levon@naghashyan.com\n* @year 2010-".date('Y')."\n* @build date ".date('F j Y')."\n*/"."\n\n";

				file_put_contents($outFilePath, $copyright, FILE_APPEND);
				foreach ($styleArr as $style) {
					$str = preg_replace('/(\.\.\/)+/', '//'.$config["static_path"].'/', $this->compress(file_get_contents($outDir."/".$style)), -1);
					file_put_contents($outFilePath, $str, FILE_APPEND);
				}
				$this->doCacheOutput($outFilePathName);
			}

		}

		private function compress($buffer) {
			/* remove comments */
			$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
			/* remove tabs, spaces, newlines, etc. */
			$buffer = str_replace(array("\r\n", "\r", "\n", "\t"), '', $buffer);
			return $buffer;
		}

		private function doDevOutput($styleArr) {
			foreach ($styleArr as $style) {
				echo '@import url("'.$style.'");';
			}
		}

		private function doCacheOutput($outFilePath) {
			header('Content-type: text/css');
			echo '@import url("'.$outFilePath.'");';
		}

	}

}
?>
