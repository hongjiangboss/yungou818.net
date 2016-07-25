<?php
ini_set("display_errors","1");
error_reporting(E_ALL);
include dirname(__FILE__).DIRECTORY_SEPARATOR."jdpay".DIRECTORY_SEPARATOR."common".DIRECTORY_SEPARATOR."SignUtil.php";
include dirname(__FILE__).DIRECTORY_SEPARATOR."jdpay".DIRECTORY_SEPARATOR."common".DIRECTORY_SEPARATOR."DesUtils.php";
include dirname(__FILE__).DIRECTORY_SEPARATOR."jdpay".DIRECTORY_SEPARATOR."common".DIRECTORY_SEPARATOR."ConfigUtil.php";
include dirname(__FILE__).DIRECTORY_SEPARATOR."jdpay".DIRECTORY_SEPARATOR."common".DIRECTORY_SEPARATOR."RSAUtils.php";
include dirname(__FILE__).DIRECTORY_SEPARATOR."jdpay".DIRECTORY_SEPARATOR."common".DIRECTORY_SEPARATOR."BytesUtils.php";
class jdpay {
	private $config;
	private $url;

	public function config($config=null){
		$this->config = $config;
		$param = array();
		$param["currency"] = 'CNY';
		$param["ip"] = $_SERVER["REMOTE_ADDR"];;
		$param["merchantNum"] = $config["id"];
		$param["merchantRemark"] = '';
		$param["notifyUrl"] = $config['NotifyUrl'];
		$param["successCallbackUrl"] = $config['ReturnUrl'];
		$param["tradeAmount"] = $config['money']*100;
		$param["tradeDescription"] = $config['title'];
		$param["tradeName"] = "818云购夺宝";
		$param["tradeNum"] = $config['code'];
		$param["tradeTime"] = date("Y-m-d H:i:s");
		$param["version"] = '1.1.5';
		$param["token"] = '';

		$sign = SignUtil::signWithoutToHex($param);
		$param["merchantSign"] = $sign;

		echo '<!DOCTYPE html>
				<html>
				<head>
				    <meta charset="utf-8"/>
				    <title>订单支付页面</title>
				    <meta name="viewport" content="width=device-width,initial-scale=1.0, user-scalable=no"/>
				    <link rel="stylesheet" href="/system/modules/pay/lib/jdpay/base.css"/>
				</head>
				<body onload="autosubmit()">
				    <form method="post" action="https://plus.jdpay.com/nPay.htm" id="payForm">
				        <input type="hidden" name="version" value="'.$param["version"].'"/>
				        <input type="hidden" name="token" value="'.$param["token"].'"/>
				        <input type="hidden" name="merchantSign" value="'.$sign.'"/>
				        <input type="hidden" name="merchantNum" value="'.$param["merchantNum"].'"/>
				        <input type="hidden" name="merchantRemark" value="'.$param["merchantRemark"].'"/>
				        <input type="hidden" name="tradeNum" value="'.$param["tradeNum"].'"/>
				        <input type="hidden" name="tradeName" value="'.$param["tradeName"].'"/>
				        <input type="hidden" name="tradeDescription" value="'.$param["tradeDescription"].'"/>
				        <input type="hidden" name="tradeTime" value="'.$param["tradeTime"].'"/>
				        <input type="hidden" name="tradeAmount" value="'.$param["tradeAmount"].'"/>
				        <input type="hidden" name="currency" value="'.$param["currency"].'"/>
				        <input type="hidden" name="notifyUrl" value="'.$param["notifyUrl"].'"/>
				        <input type="hidden" name="successCallbackUrl" value="'.$param["successCallbackUrl"].'"/>
				        <input type="hidden" name="ip" value="'.$_SERVER["REMOTE_ADDR"].'"/>
				    </form>
				<script src="/system/modules/pay/lib/jdpay/zepto.js"></script>
				<script>
				    function autosubmit(){
						document.getElementById("payForm").submit();
					}
				</script>
				</body>
				</html>';
		die;
	}

	//发送
	public function send_pay(){
		
	}
}

?>
