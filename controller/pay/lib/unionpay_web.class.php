<?php
ini_set ( "display_errors", "OFF" );
class unionpay_web {
	private $config;
	private $url;
	
	public function config($config=null){
		$this->config = $config;
		if($config['type'] == 1){
			$this->config_upop();
		}
		if($config['type'] == 2){
			$this->config_dbjy();
		}
	}
	
	private function config_upop() {
		require_once './system/modules/pay/lib/unionpay_web/func/common.php';
		require_once './system/modules/pay/lib/unionpay_web/func/SDKConfig.php';
		require_once './system/modules/pay/lib/unionpay_web/func/secureUtil.php';
		require_once './system/modules/pay/lib/unionpay_web/func/log.class.php';
		require_once './system/modules/pay/lib/unionpay_web/func/httpClient.php';
		
		$params = array(
				'version' => '5.0.0',						//版本号
				'encoding' => 'UTF-8',						//编码方式
				'certId' => getSignCertId(),				//证书ID
				'txnType' => '01',							//交易类型
				'txnSubType' => '01',						//交易子类
				'bizType' => '000000',						//业务类型
				'frontUrl' =>  $this->config['ReturnUrl'],  //前台通知地址
				'backUrl' => $this->config['NotifyUrl'],	//后台通知地址
				'signMethod' => '01',						//签名方法
				'channelType' => '07',						//渠道类型
				'accessType' => '0',						//接入类型
				'merId' => $this->config['id'],				//商户代码 '898340183980105',
				'orderId' => $this->config['code'],			//商户订单号
				'txnTime' => date('YmdHis'),				//订单发送时间
				'txnAmt' => $this->config['money']*100,		//交易金额
				'currencyCode' => '156',					//交易币种
				'defaultPayType' => '0001',					//默认支付方式
		);
// 		6226388000000095
// 		检查字段是否需要加密
		// 签名
		sign ( $params );
		$front_uri = SDK_FRONT_TRANS_URL;
		
// 		返回结果展示
// 		$result = sendHttpRequest ( $params, SDK_BACK_TRANS_URL );
// 		pr($params);
		$html_form = create_html ( $params, $front_uri );
		echo $html_form;
	}
	
	private function config_dbjy() {
		
	}
	
	//发送
	public function send_pay(){
		echo  $this->url;
		exit;
		//header("Location: $url");
	}
}