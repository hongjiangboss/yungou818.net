<?php
// ini_set("display_errors","1");
error_reporting(0);
include dirname(__FILE__).DIRECTORY_SEPARATOR."wapjdpay".DIRECTORY_SEPARATOR."common".DIRECTORY_SEPARATOR."SignUtil.php";
include dirname(__FILE__).DIRECTORY_SEPARATOR."wapjdpay".DIRECTORY_SEPARATOR."common".DIRECTORY_SEPARATOR."DesUtils.php";
include dirname(__FILE__).DIRECTORY_SEPARATOR."wapjdpay".DIRECTORY_SEPARATOR."common".DIRECTORY_SEPARATOR."ConfigUtil.php";
include dirname(__FILE__).DIRECTORY_SEPARATOR."wapjdpay".DIRECTORY_SEPARATOR."common".DIRECTORY_SEPARATOR."RSAUtils.php";
include dirname(__FILE__).DIRECTORY_SEPARATOR."wapjdpay".DIRECTORY_SEPARATOR."common".DIRECTORY_SEPARATOR."BytesUtils.php";
class wapjdpay {
	private $config;
	private $url;

	public function config($config=null){
		$this->config = $config;
		$param = array();
		$param["currency"] = 'CNY';
		$param["failCallbackUrl"] = $config['ReturnUrl'];
		$param["merchantNum"] = '110200998001';
		$param["merchantRemark"] = '';
		$param["notifyUrl"] = $config['NotifyUrl'];
		$param["successCallbackUrl"] = $config['ReturnUrl'];
		$param["tradeAmount"] = $config['money']*100;
		$param["tradeDescription"] = $config['title'];
		$param["tradeName"] = "818云购夺宝";
		$param["tradeNum"] = $config['code'];
		$param["tradeTime"] = date("Y-m-d H:i:s");
		$param["version"] = '1.0';
		$param["token"] = '';

		$sign = SignUtil::sign($param);
		$param["merchantSign"] = $sign;
		$this->url = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html>
				<head>
				<meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
				<title>京东支付</title>
				</head>
				<body>
				<form method="post" action="https://m.jdpay.com/wepay/web/pay" id="payForm">
			        <input type="hidden" name="version" value="1.0"/>
			        <input type="hidden" name="token" value=""/>
			        <input type="hidden" name="merchantSign" value="'.($sign).'"/>
			        <input type="hidden" name="merchantNum" value="'.($param["merchantNum"]).'"/>
			        <input type="hidden" name="merchantRemark" value="'.($param["merchantRemark"]).'"/>
			        <input type="hidden" name="tradeNum" value="'.($param["tradeNum"]).'"/>
			        <input type="hidden" name="tradeName" value="'.($param["tradeName"]).'"/>
			        <input type="hidden" name="tradeDescription" value="'.($param["tradeDescription"]).'"/>
			        <input type="hidden" name="tradeTime" value="'.($param["tradeTime"]).'"/>
			        <input type="hidden" name="tradeAmount" value="'.($param["tradeAmount"]).'"/>
			        <input type="hidden" name="currency" value="'.($param["currency"]).'"/>
			        <input type="hidden" name="notifyUrl" value="'.($param["notifyUrl"]).'"/>
			        <input type="hidden" name="successCallbackUrl" value="'.($param["successCallbackUrl"]).'"/>
			        <input type="hidden" name="failCallbackUrl" value="'.($param["failCallbackUrl"]).'"/>
			   	</form>
			   	<script type="text/javascript">
				document.getElementById("payForm").submit();
			   	</script>
				</body>
				</html>
				';
	}

	//发送
	public function send_pay(){
		echo $this->url;die;
	}
}

?>
