<?php

namespace wepay\join\demo\api;
use wepay\join\demo\common\RSAUtils;
use wepay\join\demo\common\SignUtil;
include '../common/SignUtil.php';
/**
 * 同步支付结果--验签
 *
 *        
 */
class WebSuccess {


	public function checkSign() {
		$param = array();
		$param["token"] = $_GET["token"];
		$param["tradeAmount"] = $_GET["tradeAmount"];
		$param["tradeCurrency"] = $_GET["tradeCurrency"];
		$param["tradeDate"] = $_GET["tradeDate"];
		$param["tradeNote"] = $_GET["tradeNote"];
		$param["tradeNum"] = $_GET["tradeNum"];
		$param["tradeStatus"] = $_GET["tradeStatus"];
		$param["tradeTime"] = $_GET["tradeTime"];		
		
		
		$data = SignUtil::signString ( $param, SignUtil::$unSignKeyList );
		error_log($data, 0);
		//1.解密签名内容
		$decryptStr = RSAUtils::decryptByPublicKey($_GET["sign"]);
		
		//2.对data进行sha256摘要加密
		$sha256SourceSignString = hash ( "sha256",$data);
		error_log($decryptStr, 0);
		error_log($sha256SourceSignString, 0);
		
		//3.比对结果
		if ($decryptStr == $sha256SourceSignString) {
			$_SESSION ['errorMsg'] = ($_GET["tradeNum"]).":验签成功";
		}else{
			$_SESSION ['errorMsg'] ="验证签名失败!";
		}
		
		
		header ( "location:../tpl/payResult.php" );
	}
}
$webSuccess= new WebSuccess ();
$webSuccess->checkSign ();

?>