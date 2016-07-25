<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');
ini_set("display_errors",0);
error_reporting(0);

class jdpay_url extends SystemAction {
	public function __construct(){
		$this->db=System::load_sys_class('model');
	}

	public function qiantai(){

		$param = array();
		$param["token"] = $_GET["token"];
		$param["tradeAmount"] = $_GET["tradeAmount"];
		$param["tradeCurrency"] = $_GET["tradeCurrency"];
		$param["tradeDate"] = $_GET["tradeDate"];
		$param["tradeNote"] = $_GET["tradeNote"];
		$param["tradeNum"] = $_GET["tradeNum"];
		$param["tradeStatus"] = $_GET["tradeStatus"];
		$param["tradeTime"] = $_GET["tradeTime"];

		$dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '{$param['tradeNum']}'");
		if(!$dingdaninfo ||$dingdaninfo['status'] == '未付款'){
			_message("支付失败");
		}else{
			if(empty($dingdaninfo['scookies'])){
				_message("充值成功!",WEB_PATH."/member/home/userbalance");
			}else{
				if($dingdaninfo['scookies'] == '1'){
					_setcookie('Cartlist',NULL);
					_message("支付成功!",WEB_PATH."/member/cart/paysuccess");
				}else{
					_message("商品还未购买,请重新购买商品!",WEB_PATH."/member/cart/cartlist");
				}
			}
		}
	}

	public function xml_to_array($xml) {
		$array = ( array ) (simplexml_load_string ( $xml ));
		foreach ( $array as $key => $item ) {
			$array [$key] = $this->struct_to_array ( ( array ) $item );
		}
		return $array;
	}
	public function struct_to_array($item) {
		if (! is_string ( $item )) {
			$item = ( array ) $item;
			foreach ( $item as $key => $val ) {
				$item [$key] = $this->struct_to_array ( $val );
			}
		}
		return $item;
	}

	/**
	 * 签名
	 */
	public function generateSign($data, $md5Key) {
		$sb = $data ['VERSION'] [0] . $data ['MERCHANT'] [0] . $data ['TERMINAL'] [0] . $data ['DATA'] [0] . $md5Key;

		return md5 ( $sb );
	}

	public function houtai(){
		file_put_contents("jd_houtai.txt", json_encode($_POST)."\n\r",FILE_APPEND);
		include dirname(__FILE__).DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR."jdpay".DIRECTORY_SEPARATOR."common".DIRECTORY_SEPARATOR."DesUtils.php";
		include dirname(__FILE__).DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR."jdpay".DIRECTORY_SEPARATOR."common".DIRECTORY_SEPARATOR."ConfigUtil.php";
		$md5Key = 'kxkJy6VqCkUmtPobrofCsBfqsGTgfxNU';
		$desKey= 'pNUBYQIWdRChjwe/Rs0vH/TWGdmRmF0O';
 		$resp = $_POST["resp"];

 		// 获取通知原始信息
		if (null == $resp) {
			echo '';die;
		}

		// 解析XML
		$params = $this->xml_to_array ( base64_decode ( $resp ) );
		$ownSign = $this->generateSign ( $params, $md5Key );
		$params_json = json_encode ( $params );

		if ($params ['SIGN'] [0] != $ownSign) {
			// 验签不对
			echo '';die;
		}

		// 验签成功，业务处理
		// 对Data数据进行解密
		$des = new DesUtils (); // （秘钥向量，混淆向量）
		$decryptArr = $des->decrypt ( $params ['DATA'] [0], $desKey ); // 加密字符串
		$params ['data'] = $this->xml_to_array ( $decryptArr );
		if($params ['data']['RETURN']['CODE'] != '0000'){
			echo '';die;
		}

		$out_trade_no = $params ['data']['TRADE']['ID'];
		$this->db->Autocommit_start();
		$dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no' and `status` = '未付款' for update");

		if(!$dingdaninfo){
			echo 'ok';exit;
		}
		$c_money = sprintf("%01.2f",$dingdaninfo['money']);
		$uid = $dingdaninfo['uid'];
		$time = time();
		$up_q1 = $this->db->Query("UPDATE `@#_member_addmoney_record` SET `pay_type` = '京东支付', `status` = '已付款' where `id` = '$dingdaninfo[id]' and `code` = '$dingdaninfo[code]'");
		$up_q2 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + $c_money,`jingyan` = `jingyan` + $c_money where (`uid` = '$uid')");
		$up_q3 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '京东支付充值', '$c_money', '$time')");
		if($up_q1 &&$up_q2 &&$up_q3){
			$this->db->Autocommit_commit();
				ActivityLotteryPayMoney($out_trade_no, $uid, $c_money);
		}else{
			$this->db->Autocommit_rollback();
			echo 'ok';exit;
		}
		if(empty($dingdaninfo['scookies'])){
			echo 'ok';
			exit;
		}
		$scookies = unserialize($dingdaninfo['scookies']);
		$pay = System::load_app_class('pay','pay');
		$pay->scookie = $scookies;
		$ok = $pay->init($uid,$pay_type['pay_id'],'go_record');
		if($ok != 'ok'){
			_setcookie('Cartlist',NULL);
			echo 'ok';exit;
		}
		$check = $pay->go_pay(1);
		if($check){
			$this->db->Query("UPDATE `@#_member_addmoney_record` SET `scookies` = '1' where `code` = '$out_trade_no' and `status` = '已付款'");
			_setcookie('Cartlist',NULL);
			echo 'ok';exit;
		}else{
			echo 'ok';exit;
		}
		echo 'ok';exit;
	}
}

?>