<?php 

include dirname(__FILE__).DIRECTORY_SEPARATOR."chinabankwap".DIRECTORY_SEPARATOR."DesUtils.php";
//include dirname(__FILE__).DIRECTORY_SEPARATOR."chinabankwap".DIRECTORY_SEPARATOR."TDESUtil.php";
include dirname(__FILE__).DIRECTORY_SEPARATOR."chinabankwap".DIRECTORY_SEPARATOR."SignUtil.php";
include dirname(__FILE__).DIRECTORY_SEPARATOR."chinabankwap".DIRECTORY_SEPARATOR."ConfigUtil.php";


class chinabankwap {
	//配置
	private $config;
	public function config($config=null){	
			$this->config=$config;		

			$v_mid = $this->config['id']; 
			$v_url = $this->config['ReturnUrl'];		
			$key   = $this->config['key'];
			
			$param = array();
			$param["currency"] = 'CNY';
			$param["failCallbackUrl"] = $this->config['ReturnUrl'];
			$merchantNum=$param["merchantNum"] = $v_mid;
			$param["merchantRemark"] ='mobile';
			$param["notifyUrl"] = $this->config['NotifyUrl'];
			$param["successCallbackUrl"] = $this->config['ReturnUrl'];
			$param["tradeAmount"] = $this->config['money']*100;
			$param["tradeDescription"] = 'wap';
			$param["tradeName"] = $this->config['title'];
			$param["tradeNum"] = $this->config['code'];
			$param["tradeTime"] = date('Y-m-d h:i:s',time());
			$param["version"] = '2.0';
			$param["token"] = '';
			//	var_dump($param);
			$sign = SignUtil::sign($param);
			$merchantSign=$param["merchantSign"] = $sign;
			$token='';
			$version='2.0';
			$desUtils  = new DesUtils();
			$key = ConfigUtil::get_val_by_key("desKey");
			$merchantRemark=$desUtils->encrypt($param["merchantRemark"],$key);
			$tradeNum=$desUtils->encrypt($param["tradeNum"],$key);
			$tradeName=$desUtils->encrypt($param["tradeName"],$key);
			$tradeDescription=$desUtils->encrypt($param["tradeDescription"],$key);
			$tradeTime=$desUtils->encrypt($param["tradeTime"],$key);
			$tradeAmount=$desUtils->encrypt($param["tradeAmount"],$key);
			$currency=$desUtils->encrypt($param["currency"],$key);
			$notifyUrl=$desUtils->encrypt($param["notifyUrl"],$key);	
			$successCallbackUrl=$desUtils->encrypt($param["successCallbackUrl"],$key);
			$failCallbackUrl=$desUtils->encrypt($param["failCallbackUrl"],$key);

$url='<html><head></head><body>';
$url.='<form method="post" name="E_FORM" action="https://m.wangyin.com/wepay/web/pay">';
$url.="<input type='hidden' name='version'   value='$version'>";
$url.="<input type='hidden' name='token'   value='$token'>";
$url.="<input type='hidden' name='merchantSign'   value='$merchantSign'>";
$url.="<input type='hidden' name='merchantNum' value='$merchantNum'>";
$url.="<input type='hidden' name='merchantRemark'    value='$merchantRemark'>";
$url.="<input type='hidden' name='tradeNum' value='$tradeNum'>";
$url.="<input type='hidden' name='tradeName' value='$tradeName'>";	
$url.="<input type='hidden' name='tradeDescription'  value='$tradeDescription'>";
$url.="<input type='hidden' name='tradeTime'   value='$tradeTime'>";

$url.="<input type='hidden' name='tradeAmount' value='$tradeAmount'>";
$url.="<input type='hidden' name='currency' value='$currency'>";	
$url.="<input type='hidden' name='notifyUrl' value='$notifyUrl'>";
$url.="<input type='hidden' name='successCallbackUrl'   value='$successCallbackUrl'>"; 
 $url.="<input type='hidden' name='failCallbackUrl'   value='$failCallbackUrl'>"; 
//$url.= '</form>';
$url.= '</form><script type="text/javascript">document.E_FORM.submit();</script>';
		$url.= '</body></html>';
		$this->url=$url;			
	}

	//支付页面
	public function send_pay(){
		$url = $this->url;
		echo $url;
		exit;
		//header("Location: $url");exit;
	}

}

?>
