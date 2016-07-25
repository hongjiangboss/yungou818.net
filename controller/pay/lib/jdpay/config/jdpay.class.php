<?php 
include dirname(__FILE__).DIRECTORY_SEPARATOR."jdpay".DIRECTORY_SEPARATOR."common".DIRECTORY_SEPARATOR."SignUtil.php";
include dirname(__FILE__).DIRECTORY_SEPARATOR."jdpay".DIRECTORY_SEPARATOR."common".DIRECTORY_SEPARATOR."DesUtils.php";
include dirname(__FILE__).DIRECTORY_SEPARATOR."jdpay".DIRECTORY_SEPARATOR."common".DIRECTORY_SEPARATOR."ConfigUtil.php";

class jdpay {
 
	private $config;
	private $create_str;
	/**
	*	支付入口
	**/
	
	public function config($config=null){

			$this->config = $config;

	}


	
	public function send_pay(){
			$config = $this->config;

// 商户编号
$merchantaccount = $config['pay_type_data']['id']['val'];


// 商户md5
$merchantPrivateKey = $config['pay_type_data']['key']['val'];


// 商户des
$desKey= $config['pay_type_data']['key2']['val'];


	/***************************************************************************/
			$this->db=System::load_sys_class('model');	
			$this->db->Autocommit_start();
			
			$dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$config[code]' and `status` = '未付款' for update");
			$member = $this->db->GetOne("select * from `@#_member` where `uid` = '$dingdaninfo[uid]' for update");
			


		$param = array();
		$param["currency"] = "CNY";
		$param["ip"] = $_SERVER["REMOTE_ADDR"]; 
		$param["failCallbackUrl"] = ConfigUtil::get_val_by_key('failCallbackUrl');
		$param["merchantNum"] = $merchantaccount; //商户号 
		$param["merchantRemark"] = "";
		$param["notifyUrl"] = $config['NotifyUrl'];
		$param["successCallbackUrl"] = $config['ReturnUrl'];
		$param["tradeAmount"] = $config['money']*100; //充值金额
		$param["tradeDescription"] = "";
		$param["tradeName"] = "充值";
		$param["tradeNum"] = $config['code'];
		$param["tradeTime"] = date('Y-m-d H:i:s', time());
		$param["version"] = "1.1";
		$param["token"] = $member["tokenjd"];

		$sign = SignUtil::signWithoutToHex($param);
		$param["merchantSign"] = $sign;
	$_SESSION['tradeAmount'] = $_POST["tradeAmount"];
	$_SESSION['tradeName'] = $_POST["tradeName"];
	$_SESSION['tradeInfo'] = $param;
	$serverUrl = ConfigUtil::get_val_by_key("serverPayUrl");
	$url=$serverUrl."?version=".(urlencode($param["version"]))
		."&token=".(urlencode($param["token"]))
		."&merchantNum=".(urlencode($param["merchantNum"]))
		."&merchantRemark=".(urlencode($param["merchantRemark"]))
		."&tradeNum=".(urlencode($param["tradeNum"]))
		."&tradeName=".(urlencode($param["tradeName"]))
		."&tradeDescription=".(urlencode($param["tradeDescription"]))
		."&tradeTime=".(urlencode($param["tradeTime"]))
		."&tradeAmount=".(urlencode($param["tradeAmount"]))
		."&currency=".(urlencode($param["currency"]))
		."&notifyUrl=".(urlencode($param["notifyUrl"]))
		."&successCallbackUrl=".(urlencode($param["successCallbackUrl"]))
		."&forPayLayerUrl=".(urlencode( $_POST["forPayLayerUrl"]))
		."&ip=".(urlencode($param["ip"]))
		."&merchantSign=".(urlencode($sign));
print_r($param);
//exit;
		header(("location:".$url));
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>模拟商户--订单支付页面</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0, user-scalable=no"/>
    <link rel="stylesheet" href="/statics/static/css/base.css"/>
</head>
<body onLoad="document.yeepay.submit()">


<div class="grid">

    <form method="post" name="yeepay" action="<?php echo ConfigUtil::get_val_by_key('serverPayUrl');?>" id="payForm">
        <!--交易信息 start-->
        <?php if($_SESSION ['tradeInfo']!=null){?>
        <input type="hidden" name="version" value="<?php echo $_SESSION ['tradeInfo']['version'];?>"/>
        <input type="hidden" name="token" value="<?php echo $_SESSION ['tradeInfo']['token'];?>"/>
        <input type="hidden" name="merchantSign" value="<?php echo $_SESSION ['tradeInfo']['merchantSign'];?>"/>
        <input type="hidden" name="merchantNum" value="<?php echo $_SESSION ['tradeInfo']['merchantNum'];?>"/>
        <input type="hidden" name="merchantRemark" value="<?php echo $_SESSION ['tradeInfo']['merchantRemark'];?>"/>
        <input type="hidden" name="tradeNum" value="<?php echo $_SESSION ['tradeInfo']['tradeNum'];?>"/>
        <input type="hidden" name="tradeName" value="<?php echo $_SESSION ['tradeInfo']['tradeName'];?>"/>
        <input type="hidden" name="tradeDescription" value="<?php echo $_SESSION ['tradeInfo']['tradeDescription'];?>"/>
        <input type="hidden" name="tradeTime" value="<?php echo $_SESSION ['tradeInfo']['tradeTime'];?>"/>
        <input type="hidden" name="tradeAmount" value="<?php echo $_SESSION ['tradeInfo']['tradeAmount'];?>"/>
        <input type="hidden" name="currency" value="<?php echo $_SESSION ['tradeInfo']['currency'];?>"/>
        <input type="hidden" name="notifyUrl" value="<?php echo $_SESSION ['tradeInfo']['notifyUrl'];?>"/>
        <input type="hidden" name="successCallbackUrl" value="<?php echo $_SESSION ['tradeInfo']['successCallbackUrl'];?>"/>
        <input type="hidden" name="failCallbackUrl" value="<?php echo $_SESSION ['tradeInfo']['failCallbackUrl'];?>"/>
        <?php }?>
        <!--交易信息 end-->

      

    </form>

</div>


</body>
</html>



<?php
	
	exit;
	/***************************************************************************/	
	}

 }

?>