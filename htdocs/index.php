<?php
/**
 * ngs index page
 * for handle all http calls
 *
 * @author Levon Naghashyan
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2014
 * @version 6.0
 *
 */
session_start();
require_once ("../conf/constants.php");
require_once (CLASSES_PATH."/framework/NGS.class.php");
require_once (CLASSES_PATH."/framework/Dispatcher.class.php");
$dispatcher = new framework\Dispatcher();