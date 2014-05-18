<?php
/**
 * Configuration file
 *
 * @author    B Wieczorkowski
*/

// Application main path 

define('_PATH', $_SERVER['DOCUMENT_ROOT'].'/');

// Public path
define('_WEB', 'https://'.$_SERVER['HTTP_HOST']);
		
define('_DB', 'cdsk');
define('_HOST', 'localhost');
define('_USER', 'cdsk');
define('_PASS', 'cdsk');

define('_PSIZE',10);

define('_DATE_FORMAT','Y-m-d');
define('_TIME_FORMAT','H:i');

define('_TEMPLATE_PATH','templates/');
define('_TEMPLATE_EXT','.html');

session_start();

$arrEmails=array(
);

?>
