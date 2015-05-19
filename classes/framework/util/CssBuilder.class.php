<?php
/**
 * Helper class for getting js files
 * have 3 general options connected with site mode (production/development)
 * 1. compress css files
 * 2. merge in one
 * 3. stream seperatly
 *
 * @author Levon Naghashyan
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2014
 * @package util
 * @version 6.0
 * @copyright Naghashyan Solutions LLC
 *
 */
namespace ngs\framework\util {
	use ngs\framework\exception\NotFoundException;
	class CssBuilder {

		public function streamFile($file) {
			$filePath = realpath(IMUSIC_PUBLIC_ROOT."/".NGS()->getNamespace()."".$file);
			$builderJsonFile = IMUSIC_PUBLIC_ROOT."/".NGS()->getNamespace()."/css/builder.json";
			if (NGS()->getEnvironment() == "production") {
				if (strpos($file, "out/") === false) {
					NGS()->getFileUtils()->sendFile($filePath, array("mimeType" => "text/css", "cache" => true));
				} else if (fileatime($filePath) == fileatime($builderJsonFile) && file_exists($filePath)) {
					NGS()->getFileUtils()->sendFile($filePath, array("mimeType" => "text/css", "cache" => true));
				} else {
					$this->doBuildCss($builderJsonFile, $file);
					NGS()->getFileUtils()->sendFile($filePath, array("mimeType" => "text/css", "cache" => true));
				}
			} elseif (NGS()->getEnvironment() == "development") {
				if (strpos($file, "out/") === false) {
					NGS()->getFileUtils()->sendFile($filePath, array("mimeType" => "text/css", "cache" => false));
					return;
				}
				$files = $this->getBuilderArr(json_decode(file_get_contents($builderJsonFile)), $file);
				if (count($files) == 0) {
					throw new \ngs\framework\exception\NotFoundException( array("type" => "json", "msg" => $file." not found"));
				}
				$this->doDevOutput($files);
				return;
			}
		}

		private function doBuildCss($builderJsonFile, $file) {
			$files = $this->getBuilderArr(json_decode(file_get_contents($builderJsonFile)), $file);
			if (!$files) {
				return;
			}
			$outDir = IMUSIC_PUBLIC_ROOT."/".NGS()->getNamespace()."/css/out";
			$buf = "";
			foreach ($files["files"] as $inputFile) {
				$filePath = (IMUSIC_PUBLIC_ROOT."/".NGS()->getNamespace()."/css/".$inputFile);
				$realFilePath = realpath(IMUSIC_PUBLIC_ROOT."/".NGS()->getNamespace()."/css/".$inputFile);
				if (!$realFilePath) {
					throw new \ngs\framework\exception\NotFoundException( array("type" => "json", "msg" => $filePath." not found"));
				}
				$buf .= file_get_contents($realFilePath).";\n\r";
			}
			if($files["compress"] == true){
				$buf = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buf);
				$buf = str_replace(array("\r\n", "\r", "\n", "\t"), '', $buf);
			}
			file_put_contents($outDir."/".$files["output_file"], $buf);
		}
		
		private function doDevOutput($files) {
			header('Content-type: text/css');
			foreach ($files["files"] as $inputFile) {
				$inputFile = SITE_PATH."/css/".trim($inputFile);
				echo '@import url("'.$inputFile.'");';
			}
		}

		/**
		 * get js files from builders json array by filename
		 *
		 *
		 * @param array $bulders
		 * @param string $file - request file name
		 *
		 * @return array builder
		 */
		private function getBuilderArr($bulders, $file = null) {
			$tmpArr = array();
			foreach ($bulders as $key => $value) {
				if (strpos($file, $value->output_file) === false) {
					$builders = null;
					if (isset($value->builders)) {
						$builders = (array)$value->builders;
						$tempArr = $this->getBuilderArr($builders, $file);
						if ($tempArr) {
							return $tempArr;
						} else {
							continue;
						}
					} else {
						continue;
					}
					$tmpArr = array();
					$tmpArr["output_file"] = (string)$value->output_file;
					$tmpArr["debug"] = false;
					$tmpArr["compress"] = $value->compress;
					$tmpArr["files"] = (array)$value->files;
				} else {
					$tmpArr = array();
					$tmpArr["output_file"] = (string)$value->output_file;
					$tmpArr["debug"] = false;
					$tmpArr["compress"] = $value->compress;
					$tmpArr["files"] = array();
					if (isset($value->builders) && is_array($value->builders)) {
						foreach ($value->builders as $builder) {
							if (!is_array($builder)) {
								$builder = array($builder);
							}
							$tempArr = $this->getBuilderArr($builder, $builder[0]->output_file);
							if (isset($tempArr["files"])) {
								$tmpArr["files"] = array_merge($tmpArr["files"], $tempArr["files"]);
							}
						}
					} else {
						$tmpArr["files"] = (array)$value->files;
					}
				}
			}
			return $tmpArr;
		}

	}

}
