<?php 
include dirname(__FILE__).DIRECTORY_SEPARATOR."shengpay".DIRECTORY_SEPARATOR."core.php";
class shengpay {
	
	private $config;
	private $url;
	//主入口
	public function config($config=null){
		$this->config = $config;	
		
	}	
	//发送
	public function send_pay(){
	$v_mid = $this->config['id'];    								    

	$v_url = $this->config['ReturnUrl'];		
	$key   = $this->config['key'];								    

	$v_notify=$this->config['NotifyUrl']; 



	   $v_oid = trim($this->config['code']); 

	 
	$v_amount = trim($this->config['money']);                   //支付金额 


$shengpay=new shengpayl();
$array=array(
	'Name'=>'B2CPayment',
	'Version'=>'V4.1.1.1.1',
	'Charset'=>'UTF-8',
	//'MsgSender'=>'100894',
	'MsgSender'=>$v_mid,
	'SendTime'=>date('YmdHis'),
	'OrderTime'=>date('YmdHis'),
	'PayType'=>'',
	'InstCode'=>'',
	'PageUrl'=>$v_url,
	'NotifyUrl'=>$v_notify,
	'ProductName'=>$this->config['title'],
	'BuyerContact'=>'',
	'BuyerIp'=>'',
	'Ext1'=>'',
	'Ext2'=>'',
	'SignType'=>'MD5',
);
$shengpay->init($array);
//$shengpay->setKey('shengfutongSHENGFUTONGtest');
//$shengpay->setKey('BHeG6%75gfeKH09^&84bBS*');
//$shengpay->setKey('BHeG6%75gfeKH09^&84bBS*');
$shengpay->setKey($key);

/*其他参数设置*/
/*
	PayType或者InstCode等其他参数
	$shengpay->setParam('PayType','PT002');
/*
/*
	商家自行检测传入的价格与数据库订单需支付金额是否相同
*/
		$this->url=$shengpay->takeOrder($v_oid,$v_amount);
		 echo  $this->url;
		 exit;
		//header("Location: $url");	
	}
}

?>
