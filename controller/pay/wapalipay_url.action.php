<?php

defined('G_IN_SYSTEM')or exit('No permission resources.');
ini_set("display_errors","OFF");
class wapalipay_url extends SystemAction {
	public function __construct(){
		$this->db=System::load_sys_class('model');
	}

	public function qiantai(){

		sleep(2);

		$out_trade_no = $_GET['out_trade_no'];	//商户订单号

		$dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no'");

		if(!$dingdaninfo || $dingdaninfo['status'] == '未付款'){

			_messagemobile("支付失败");

		}else{

			if(empty($dingdaninfo['scookies'])){

				_messagemobile("充值成功!",WEB_PATH."/mobile/home/userbalance");

			}else{

				if($dingdaninfo['scookies'] == '1'){
					_setcookie('Cartlist',NULL);//null购物车
					_messagemobile("支付成功!",WEB_PATH."/mobile/home/userbalance/1/");

				}else{

					_messagemobile("商品还未购买,请重新购买商品!",WEB_PATH."/mobile/");

				}



			}

		}

	}



	public function houtai(){
		file_put_contents("alipay.txt",json_encode($_POST)."\n\r",FILE_APPEND);
		
		// $_POST = json_decode('{"service":"alipay.wap.trade.create.direct","sign":"b8cd0a416f41a7571817ec676529ed54","sec_id":"MD5","v":"1.0","notify_data":"<notify><payment_type>1<\/payment_type><subject>\u4e00\u5143\u8d2d\u5929\u4e0b<\/subject><trade_no>2015082500001000870060911579<\/trade_no><buyer_email>18505953539<\/buyer_email><gmt_create>2015-08-25 17:16:14<\/gmt_create><notify_type>trade_status_sync<\/notify_type><quantity>1<\/quantity><out_trade_no>C14404941560136712<\/out_trade_no><notify_time>2015-08-25 20:40:43<\/notify_time><seller_id>2088711980604215<\/seller_id><trade_status>TRADE_SUCCESS<\/trade_status><is_total_fee_adjust>N<\/is_total_fee_adjust><total_fee>1.00<\/total_fee><gmt_payment>2015-08-25 17:16:15<\/gmt_payment><seller_email>2459183655@qq.com<\/seller_email><price>1.00<\/price><buyer_id>2088802992613871<\/buyer_id><notify_id>d5cd53e63a516042623baff26e726c786u<\/notify_id><use_coupon>N<\/use_coupon><\/notify>"}',true);

	    include dirname(__FILE__)."/lib/wapalipay/alipay_notify.class.php";

		$pay_type =$this->db->GetOne("SELECT * from `@#_pay` where `pay_class` = 'wapalipay' and `pay_start` = '1'");

		$pay_type_key = unserialize($pay_type['pay_key']);

		$key =  $pay_type_key['key']['val'];		//支付KEY

		$partner =  $pay_type_key['id']['val'];		//支付商号ID

		$alipay_config_sign_type = strtoupper('MD5');		//签名方式 不需修改

		$alipay_config_input_charset = strtolower('utf-8'); //字符编码格式

		$alipay_config_cacert =  dirname(__FILE__)."/lib/wapalipay/cacert.pem";	//ca证书路径地址

		$alipay_config_transport   = 'http';

		$alipay_config=array(

			"partner"      =>$partner,

			"key"          =>$key,

			"sign_type"    =>$alipay_config_sign_type,

			"input_charset"=>$alipay_config_input_charset,

			"cacert"       =>$alipay_config_cacert,

			"transport"    =>$alipay_config_transport

		);


		$alipayNotify = new AlipayNotify($alipay_config);

		$verify_result = $alipayNotify->verifyNotify();



		if(!$verify_result) {echo "fail";exit;} //验证失败



		$doc = new DOMDocument();

		$doc->loadXML($_POST['notify_data']);



		if( ! empty($doc->getElementsByTagName( "notify" )->item(0)->nodeValue) ) {

			//商户订单号

			$out_trade_no = $doc->getElementsByTagName( "out_trade_no" )->item(0)->nodeValue;

			//支付宝交易号

			$trade_no = $doc->getElementsByTagName( "trade_no" )->item(0)->nodeValue;

			//交易状态

			$trade_status = $doc->getElementsByTagName( "trade_status" )->item(0)->nodeValue;



			//开始处理及时到账和担保交易订单

			if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS' || $trade_status == 'WAIT_SELLER_SEND_GOODS') {

				$dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no' and `status` = '未付款'");

				if(!$dingdaninfo){	echo "fail";exit;}	//没有该订单,失败

				$c_money = intval($dingdaninfo['money']);

				$uid = $dingdaninfo['uid'];

				$time = time();

				$this->db->Autocommit_start();

				$up_q1 = $this->db->Query("UPDATE `@#_member_addmoney_record` SET `pay_type` = '支付宝', `status` = '已付款' where `id` = '$dingdaninfo[id]' and `code` = '$dingdaninfo[code]'");

				$up_q2 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + $c_money where (`uid` = '$uid')");

				$up_q3 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '充值', '$c_money', '$time')");



				if($up_q1 && $up_q2 && $up_q3){

					$this->db->Autocommit_commit();
					ActivityLotteryPayMoney($out_trade_no, $uid, $c_money);

				}else{

					$this->db->Autocommit_rollback();

					echo "fail";exit;

				}

				if(empty($dingdaninfo['scookies'])){

						echo "success";exit;	//充值完成

				}

				$scookies = unserialize($dingdaninfo['scookies']);

				$pay = System::load_app_class('pay','pay');

				$pay->scookie = $scookies;



				$ok = $pay->init($uid,$pay_type['pay_id'],'go_record');	//购买商品

				if($ok != 'ok'){

					_setcookie('Cartlist',NULL);

					echo "fail";exit;	//商品购买失败

				}

				$check = $pay->go_pay(1);

				if($check){

					$this->db->Query("UPDATE `@#_member_addmoney_record` SET `scookies` = '1' where `code` = '$out_trade_no' and `status` = '已付款'");

					_setcookie('Cartlist',NULL);

					echo "success";exit;

				}else{

					echo "fail";exit;

				}



			}//开始处理订单结束

		}



	}//function end



}//



?>