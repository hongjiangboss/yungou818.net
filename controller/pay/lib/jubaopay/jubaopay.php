<?php
require_once(dirname(__FILE__) . '/RSA.php');
// include 'jubaopay/RSA.php';

class jubao
{
	private $_rsa;
	
	private $payid;
	private $mobile;
	private $amount;
	private $remark;
	
	private $orderNo;
	private $state;
	private $modifyTime;
	
	public function __construct($conf)
	{	
		$this->_rsa = new RSA($conf);	
	}
	
	public function __destruct()
	{
		
	}
	
	public function __set($name,$value)
	{
		$this->$name = $value ;
	}
	
	public function __get($name)
	{
		return $this->$name;
	}
	
	public function encrypt()
	{
		$digest=urlencode($this->payid)."&".urlencode($this->mobile)."&".urlencode($this->amount)."&".urlencode($this->remark);
		$message=$this->_rsa->pubEncrypt($digest);
		return $message;
	}
	
	public function sign()
	{
		$digest=$this->payid.$this->mobile.$this->amount.$this->remark;
		$signature=$this->_rsa->sign($digest);
		return $signature;
	}
	
	public function decrypt($message)
	{
		$digest=$this->_rsa->privDecrypt($message);
		$items=explode('&', $digest);
		$this->payid = urldecode($items[0]);
		$this->mobile = urldecode($items[1]);
		$this->amount = urldecode($items[2]);
		$this->remark = urldecode($items[3]);
		$this->orderNo = urldecode($items[4]);
		$this->state = urldecode($items[5]);
		$this->modifyTime = urldecode($items[6]);
	}
	
	public function verify($signature)
	{
		$digest=$this->payid.$this->mobile.$this->amount.$this->remark.$this->orderNo.$this->state.$this->modifyTime;
		return $this->_rsa->verify($digest,$signature);
	}
	
	public function encryptComfirm()
	{
		$digest=urlencode($this->payid)."&".urlencode($this->mobile)."&".urlencode($this->amount)."&".urlencode($this->orderNo);
		$message=$this->_rsa->pubEncrypt($digest);
		return $message;
	}
	
	public function signComfirm()
	{
		$digest=$this->payid.$this->mobile.$this->amount.$this->orderNo;
		$signature=$this->_rsa->sign($digest);
		return $signature;
	}
}
