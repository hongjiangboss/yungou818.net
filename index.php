<?php
define('G_BANBEN_TYPE',"1");
session_start();
$controller_path = 'controller';
$include_path = 'include';
$common_path = 'common';
$data_path = 'data';
$statics_path = 'templates';
define('G_APP_PATH',dirname(__FILE__).DIRECTORY_SEPARATOR);
if ( !file_exists(G_APP_PATH.'./data/config/install.lock') ) {
	header("Location:install/");
}
include  G_APP_PATH.$data_path.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'global.php';
define('G_TEMPLATES_CSS', ((G_TEMPLATES_PATH . '/') . G_STYLE) . '/css');
define('G_TEMPLATES_JS', ((G_TEMPLATES_PATH . '/') . G_STYLE) . '/js');
error_reporting(0);
System::CreateApp();

?>
