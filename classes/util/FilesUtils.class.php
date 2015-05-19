<?php

namespace util {

	use util\HttpGetRequest;
	
	class FilesUtils {

		/**
		 * this method for recursive delete folder
		 * and if empty flag is true will delete all files and fodlers in dir
		 *
		 * @param String $dir
		 * @param Boolean $empty|false
		 *
		 * @return true|false
		 */
		public static function deleteFoldersTree($dir, $empty = false) {
			if (!is_dir($dir)) {
				return false;
			}
			$files = array_diff(scandir($dir), array('.', '..', '.svn', '.git'));
			foreach ($files as $file) {
				(is_dir("$dir/$file")) ? self::deleteFoldersTree("$dir/$file") : unlink("$dir/$file");
			}
			if ($empty == false) {
				return rmdir($dir);
			}
			return true;
		}
		
		/**
		 * this method is for recursive move of folder contents to destintion folder
		 *
		 * @param String $folderPath
		 * @param String $destinationFolderPath
		 *
		 * @return true|false
		 */
		public static function moveFolderContent($folderPath, $destinationFolderPath) {
			if (!is_dir($folderPath)) {
				return false;
			}
			
			if(!is_dir($destinationFolderPath)){
				if(!mkdir($destinationFolderPath)){
					return false;
				}
			}
			$files = array_diff(scandir($folderPath), array('.', '..', '.svn', '.git'));
			foreach ($files as $file) {
				if(is_dir("$folderPath/$file")){
					 self::moveFolderContent("$folderPath/$file", "$destinationFolderPath/$file");
				}else{
					rename("$folderPath/$file", "$destinationFolderPath/$file");
				}
			}
			rmdir($folderPath);
			return true;
		}
		

		/**
		 * helper function for getting file mimetype
		 *
		 * @param String $file
		 *
		 * @return String
		 */
		public static function getFileMimeType($file) {
			if (function_exists("finfo_open")) {
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$fileMimeType = finfo_file($finfo, $file);
				finfo_close($finfo);
			} else {
				$fileMimeType = mime_content_type($file);
			}
			return $fileMimeType;
		}

	}

}
