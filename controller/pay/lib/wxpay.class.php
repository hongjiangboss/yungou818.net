<?php
include dirname(__FILE__).DIRECTORY_SEPARATOR."wxpay".DIRECTORY_SEPARATOR."WxPayPubHelper.php";

class wxpay {

	private $config;

	public function config($config=null){

		$this->config = $config;

	}



	public function send_pay(){

		$unifiedOrder = new UnifiedOrder_pub();
		$amount = trim($this->config['money'])*100;
		
		$amount = $amount >= 100 ? $amount : 100;

	    $notify_url=$this->config['NotifyUrl'];   //通知URL

		$unifiedOrder->setParameter("body",$this->config['title']);//商品描述

		$out_trade_no = $this->config['code'];

		$unifiedOrder->setParameter("out_trade_no",$out_trade_no);//商户订单号

		$unifiedOrder->setParameter("total_fee",$amount);//总金额

		$unifiedOrder->setParameter("notify_url",$notify_url);//通知地址

		$unifiedOrder->setParameter("trade_type","NATIVE");//交易类型

		//非必填参数，商户可根据实际情况选填

		//$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号

		//$unifiedOrder->setParameter("device_info","XXXX");//设备号

		// $unifiedOrder->setParameter("attach","111");//附加数据

		//$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间

		//$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间

		//$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记

		//$unifiedOrder->setParameter("openid","XXXX");//用户标识

		//$unifiedOrder->setParameter("product_id","XXXX");//商品ID

		//获取统一支付接口结果

		$unifiedOrderResult = $unifiedOrder->getResult();

		if ($unifiedOrderResult["return_code"] == "FAIL"){

			echo "通信出错：".$unifiedOrderResult['return_msg']."<br>";

		}elseif($unifiedOrderResult["result_code"] == "FAIL"){

			echo iconv("utf-8","gb2312//IGNORE","错误代码：".$unifiedOrderResult['err_code']."<br>");

			echo iconv("utf-8","gb2312//IGNORE","错误代码描述：".$unifiedOrderResult['err_code_des']."<br>");

		}elseif($unifiedOrderResult["code_url"] != NULL){
			$code_url = $unifiedOrderResult["code_url"];
			include('native_dynamic_qrcode.php');

			exit;

		}
	}

}



?>