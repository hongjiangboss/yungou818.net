<?php
defined ( 'G_IN_SYSTEM' ) or exit ( 'No permission resources.' );
ini_set ( "display_errors", "OFF" );
class unionpay_web_url extends SystemAction {
	public function __construct() {
		$this->db = System::load_sys_class ( 'model' );
	}
	public function qiantai() {
		include_once 'lib/unionpay_web/func/common.php';
		include_once 'lib/unionpay_web/func/secureUtil.php';
		sleep ( 2 );
		if (isset ( $_POST ['signature'] )) {
			if (verify ( $_POST ) && $_POST ['respMsg'] == 'success') {
				$out_trade_no = $_POST ['orderId']; // 商户订单号
				
				$pay_type = $this->db->GetOne ( "SELECT * from `@#_pay` where `pay_class` = 'unionpay_web' and `pay_start` = '1'" );
				$pay_type_key = unserialize ( $pay_type ['pay_key'] );
				$key = $pay_type_key ['key'] ['val']; // 支付KEY
				$partner = $pay_type_key ['id'] ['val']; // 支付商号ID
				
				$this->db->Autocommit_start ();
				$dingdaninfo = $this->db->GetOne ( "select * from `@#_member_addmoney_record` where `code` = '$out_trade_no' and `status` = '未付款' for update" );
				if (! $dingdaninfo) {
					_messagemobile ( "充值成功!", WEB_PATH . "/member/home/userbalance" );
				} else {
					$dingdaninfo1 = $this->db->GetOne ( "select * from `@#_member_addmoney_record` where `code` = '$out_trade_no' and `status` = '已付款' for update" );				
					if ($dingdaninfo1 && is_array(unserialize($dingdaninfo1['scookies']))) {
						header ( "location: " . WEB_PATH . "/mobile/cart/paysubmit/money/nobank/{$dingdaninfo1 ['money']}/{$dingdaninfo1 ['score']}" );
						exit ();
					}
				}
				 // 没有查到该订单,说明后台调用支付成功
				$c_money = intval ( $dingdaninfo ['money'] );
				$uid = $dingdaninfo ['uid'];
				$time = time ();
				$up_q1 = $this->db->Query ( "UPDATE `@#_member_addmoney_record` SET `pay_type` = '银联手机在线支付', `status` = '已付款' where `id` = '$dingdaninfo[id]' and `code` = '$dingdaninfo[code]'" );
				$up_q2 = $this->db->Query ( "UPDATE `@#_member` SET `money` = `money` + $c_money where (`uid` = '$uid')" );
				$up_q3 = $this->db->Query ( "INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '充值', '$c_money', '$time')" );
				
				if ($up_q1 && $up_q2 && $up_q3) {
					$this->db->Autocommit_commit ();
					ActivityLotteryPayMoney($out_trade_no, $uid, $c_money);	
				} else {
					$this->db->Autocommit_rollback ();
				}
				
				if (empty ( $dingdaninfo ['scookies'] )) {
					_messagemobile ( "充值成功!", WEB_PATH . "/member/home/userbalance" );
				} else {
					header ( "location: " . WEB_PATH . "/mobile/cart/paysubmit/money/nobank/{$dingdaninfo ['money']}/{$dingdaninfo ['score']}" );
					exit ();
				}
				$scookies = unserialize ( $dingdaninfo ['scookies'] );
				$pay = System::load_app_class ( 'pay', 'pay' );
				$pay->scookie = $scookies;
				
				$ok = $pay->init ( $uid, $pay_type ['pay_id'], 'go_record' ); // 购买商品
				if ($ok != 'ok') {
					_setcookie ( 'Cartlist', NULL );
					_messagemobile ( "支付失败" ); // 商品购买失败
				}
				$check = $pay->go_pay ( 1 );
				if ($check) {
					$this->db->Query ( "UPDATE `@#_member_addmoney_record` SET `scookies` = '1' where `code` = '$out_trade_no' and `status` = '已付款'" );
					_setcookie ( 'Cartlist', NULL );
					_messagemobile ( "支付成功!", WEB_PATH . "/member/cart/paysuccess" );
				} else {
					_messagemobile ( "支付失败" );
				}
			} else {
				_messagemobile ( "支付失败" );
			}
		}
	}
	public function houtai() {
		file_get_contents(dirname(__FILE__).'report_unionpay', var_export($_POST,true));
		
		include_once 'lib/unionpay_web/func/common.php';
		include_once 'lib/unionpay_web/func/secureUtil.php';
		
		if (isset ( $_POST ['signature'] )) {
			if (verify ( $_POST )) {
				$out_trade_no = $_POST ['orderId']; // 商户订单号
				
				$pay_type = $this->db->GetOne ( "SELECT * from `@#_pay` where `pay_class` = 'unionpay_web' and `pay_start` = '1'" );
				$pay_type_key = unserialize ( $pay_type ['pay_key'] );
				$key = $pay_type_key ['key'] ['val']; // 支付KEY
				$partner = $pay_type_key ['id'] ['val']; // 支付商号ID
				
				$this->db->Autocommit_start ();
				$dingdaninfo = $this->db->GetOne ( "select * from `@#_member_addmoney_record` where `code` = '$out_trade_no' and `status` = '未付款' for update" );
				if (! $dingdaninfo) {
					exit ();
				} // 没有该订单,失败
				$c_money = intval ( $dingdaninfo ['money'] );
				$uid = $dingdaninfo ['uid'];
				$time = time ();
				$up_q1 = $this->db->Query ( "UPDATE `@#_member_addmoney_record` SET `pay_type` = '银联手机在线支付', `status` = '已付款' where `id` = '$dingdaninfo[id]' and `code` = '$dingdaninfo[code]'" );
				$up_q2 = $this->db->Query ( "UPDATE `@#_member` SET `money` = `money` + $c_money where (`uid` = '$uid')" );
				$up_q3 = $this->db->Query ( "INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '充值', '$c_money', '$time')" );
				
				if ($up_q1 && $up_q2 && $up_q3) {
					$this->db->Autocommit_commit ();
						ActivityLotteryPayMoney($out_trade_no, $uid, $c_money);
				} else {
					$this->db->Autocommit_rollback ();
				}
				
				if (empty ( $dingdaninfo ['scookies'] )) {
					echo "success";
					exit ();
				}
				$scookies = unserialize ( $dingdaninfo ['scookies'] );
				$pay = System::load_app_class ( 'pay', 'pay' );
				$pay->scookie = $scookies;
				
				$ok = $pay->init ( $uid, $pay_type ['pay_id'], 'go_record' ); // 购买商品
				if ($ok != 'ok') {
					_setcookie ( 'Cartlist', NULL );
					echo "fail";
					exit (); // 商品购买失败
				}
				$check = $pay->go_pay ( 1 );
				if ($check) {
					$this->db->Query ( "UPDATE `@#_member_addmoney_record` SET `scookies` = '1' where `code` = '$out_trade_no' and `status` = '已付款'" );
					_setcookie ( 'Cartlist', NULL );
					echo "success";
					exit ();
				} else {
					echo "fail";
					exit ();
				}
			}
		}
	}
}