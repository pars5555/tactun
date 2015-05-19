<?php
/**
 * Helper class for getting js files
 * have 3 general options connected with site mode (production/development)
 * 1. compress js files
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
	use ngs\framework\exceptions\NotFoundException;
	class JsBuilder {

		public function streamFile($file) {
			$filePath = realpath(NGS()->getRootDirByModule()."/".PUBLIC_DIR."/".$file);
			$builderJsonFile = NGS_SITE_PATH."/".NGS()->getModuleName()."/js/builder.json";
			//	var_dump(json_decode(file_get_contents($builderXml), true));exit;
			//	$outDir = IMUSIC_PUBLIC_ROOT."/".NGS()->getModuleName()."/js/out";
			if (NGS()->getEnvironment() == "production") {
				if (strpos($file, "out/") === false) {
					NGS()->getFileUtils()->sendFile($filePath, array("mimeType" => "text/javascript", "cache" => true));
				} else if (fileatime($filePath) == fileatime($builderJsonFile) && file_exists($filePath)) {
					NGS()->getFileUtils()->sendFile($filePath, array("mimeType" => "text/javascript", "cache" => true));
				} else {
					$this->doBuildJs($builderJsonFile, $file);
					NGS()->getFileUtils()->sendFile($filePath, array("mimeType" => "text/javascript", "cache" => true));
				}
			} elseif (NGS()->getEnvironment() == "development") {
				if (strpos($file, "out/") === false) {
					
					NGS()->getFileUtils()->sendFile($filePath, array("mimeType" => "text/javascript", "cache" => false));
					return;
				}
				$files = $this->getBuilderArr(json_decode(file_get_contents($builderJsonFile)), $file);
				if (count($files) == 0) {
					throw new NotFoundException( array("type" => "json", "msg" => $file." not found"));
				}
				$this->doDevOutput($files);
				return;
			}
		}

		private function doBuildJs($builderJsonFile, $file) {
			$files = $this->getBuilderArr(json_decode(file_get_contents($builderJsonFile)), $file);
			if (!$files) {
				return;
			}
			$outDir = IMUSIC_PUBLIC_ROOT."/".NGS()->getModuleName()."/js/out";
			$buf = "";
			foreach ($files["files"] as $inputFile) {
				$filePath = (IMUSIC_PUBLIC_ROOT."/".NGS()->getModuleName()."/js/".$inputFile);
				$realFilePath = realpath(IMUSIC_PUBLIC_ROOT."/".NGS()->getModuleName()."/js/".$inputFile);
				if (!$realFilePath) {
					throw new NotFoundException( array("type" => "json", "msg" => $filePath." not found"));
				}
				$buf .= file_get_contents($realFilePath).";\n\r";
			}
			if($files["compress"] == false){
				$buf = $this->doCompress($buf);
			}
			file_put_contents($outDir."/".$files["output_file"], $buf);
		}

		private function doCompress($buf) {
			$ch = curl_init('http://closure-compiler.appspot.com/compile');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, 'output_info=compiled_code&output_format=text&language ECMASCRIPT5&compilation_level=SIMPLE_OPTIMIZATIONS&js_code='.urlencode($buf));
			$out = curl_exec($ch);
			var_dump($out);exit;
			curl_close($ch);
			return $out;
		}

		private function doDevOutput($files) {
			header("Content-type: text/javascript");
			foreach ($files["files"] as $inputFile) {
				$inputFile = SITE_PATH."/js/".trim($inputFile);
				echo("document.write('<script type=\"text/javascript\" src=\"".$inputFile."\"></script>');\n\r");
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
						if($tempArr){
							return $tempArr;
						}else{
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
