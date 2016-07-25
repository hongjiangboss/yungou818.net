<?php

class shengpayl{

	private $payHost;
	private $debug=false;
	private $key='shengfutongSHENGFUTONGtest';
	private $params=array(
		'Name'=>'B2CPayment',
		'Version'=>'V4.1.1.1.1',
		'Charset'=>'UTF-8',
		'MsgSender'=>'100894',
		'SendTime'=>'',
		'OrderNo'=>'',
		'OrderAmount'=>'',
		'OrderTime'=>'',
		'PayType'=>'',
		'InstCode'=>'',
		'PageUrl'=>'',
		'NotifyUrl'=>'',
		'ProductName'=>'',
		'BuyerContact'=>'',
		'BuyerIp'=>'',
		'Ext1'=>'',
		'Ext2'=>'',
		'SignType'=>'MD5',
		'SignMsg'=>'',
	);

	function init($array=array()){
		if($this->debug)
			$this->payHost='http://mer.mas.sdo.com/web-acquire-channel/cashier.htm';
		else
			$this->payHost='http://mas.sdo.com/web-acquire-channel/cashier.htm';
		foreach($array as $key=>$value){
			$this->params[$key]=$value;
		}
	}

	function setKey($key){
		$this->key=$key;
	}
	function setParam($key,$value){
		$this->params[$key]=$value;
	}

	function takeOrder($oid,$fee){
		$this->params['OrderNo']=$oid;
		$this->params['OrderAmount']=$fee;
		$origin='';
		foreach($this->params as $key=>$value){
			if(!empty($value))
				$origin.=$value;
		}
		$SignMsg=strtoupper(md5($origin.$this->key));
		$this->params['SignMsg']=$SignMsg;
		$html = '<html><head></head><body>';
		$html .= '<form method="post" name="E_FORM" action="'.$this->payHost.'">';
		foreach ($this->params as $key => $val){
			$html .= "<input type='hidden' name='$key' value='$val' />";
		}
		$html .= '</form><script type="text/javascript">document.E_FORM.submit();</script>';
		$html .= '</body></html>';
		return $html;



	}

	function returnSign(){
		$params=array(
			'Name'=>'',
			'Version'=>'',
			'Charset'=>'',
			'TraceNo'=>'',
			'MsgSender'=>'',
			'SendTime'=>'',
			'InstCode'=>'',
			'OrderNo'=>'',
			'OrderAmount'=>'',
			'TransNo'=>'',
			'TransAmount'=>'',
			'TransStatus'=>'',
			'TransType'=>'',
			'TransTime'=>'',
			'MerchantNo'=>'',
			'ErrorCode'=>'',
			'ErrorMsg'=>'',
			'Ext1'=>'',
			'Ext2'=>'',
			'SignType'=>'MD5',
		);
		foreach($_POST as $key=>$value){
			if(isset($params[$key])){
				$params[$key]=$value;
			}
		}
		$TransStatus=(int)$_POST['TransStatus'];
		$origin='';
		foreach($params as $key=>$value){
			if(!empty($value))
				$origin.=$value;
		}
		$SignMsg=strtoupper(md5($origin.$this->key));
		if($SignMsg==$_POST['SignMsg'] and $TransStatus==1){
			return true;
		}else{
			return false;
		}
	}

}

