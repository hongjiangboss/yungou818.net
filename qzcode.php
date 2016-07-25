<?php
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
error_reporting(0);
require("phpqrcode/qrlib.php");
$pcode = $_GET['c'] ? trim($_GET['c']) : 1;
$data = 'http://'.$_SERVER['HTTP_HOST']."/?/mobile/user/register/$pcode/1/";
$errorCorrectionLevel  = 'L';//array('L','M','Q','H')
$matrixPointSize       = 4;//1-10
$margin                = 2;
$PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'phpqrcode'.DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
$filename = $PNG_TEMP_DIR.md5($pcode).'.png';
if(!file_exists($filename)){
	QRcode::png($data,$filename);
}

$PNG_WEB_DIR = '/phpqrcode/temp/';
if($_GET['share']){
	echo $data = 'http://'.$_SERVER['HTTP_HOST'].$PNG_WEB_DIR.basename($filename);die;
}
echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" />';
?>