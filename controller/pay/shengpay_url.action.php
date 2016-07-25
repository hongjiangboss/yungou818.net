<?php 

//defined('G_IN_SYSTEM')or exit('No permission resources.');
ini_set("display_errors","ON");
include dirname(__FILE__).'/lib/shengpay/core.php';
class shengpay_url extends SystemAction {
	private $out_trade_no;
	public function __construct(){			
		$this->db=System::load_sys_class('model');		
	} 	
	
	
	public function qiantai(){	
		if(_is_mobile()){
			$message = '_messagemobile';
		}else{
			$message = '_message';
		}
		$out_trade_no = $this->out_trade_no;		
		
		$dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no'");
		if(!$dingdaninfo || $dingdaninfo['status'] == '未付款'){
			_message("支付成功，请进入用户中心!",WEB_PATH."/mobile/home/userbalance");
		}else{
			if(empty($dingdaninfo['scookies'])){
				_message("充值成功!",WEB_PATH."/member/home/userbalance");
			}else{
				if($dingdaninfo['scookies'] == '1'){
					_message("支付成功!",WEB_PATH."/member/home/userbalance");
				}else{
					_message("商品还未购买,请重新购买商品!",WEB_PATH."/member/cart/cartlist");
				}					
			}
		}
	}
	
	
	
	public function houtai(){

		$pay_type =$this->db->GetOne("SELECT * from `@#_pay` where `pay_class` = 'shengpay' and `pay_start` = '1'");
		$pay_type_key = unserialize($pay_type['pay_key']);
		$key =  $pay_type_key['key']['val'];		//支付KEY
		$id =  $pay_type_key['id']['val'];		//支付商号ID	
		
		$shengpay=new shengpayl();
		$shengpay->setKey($key);
		if($shengpay->returnSign()){

	$oid=$_POST['OrderNo'];
	$fee=$_POST['TransAmount'];

	$this->out_trade_no=$oid;
	$ret = $this->shengpay_chuli();	
	/*
		商家自行检测商家订单状态，避免重复处理，而且请检查fee的值与订单需支付金额是否相同
	*/
	echo 'OK';


		}else
		{
		
		echo 'error';
		}






	}
	
	
	
	private function shengpay_chuli(){
		$pay_type =$this->db->GetOne("SELECT * from `@#_pay` where `pay_class` = 'shengpay' and `pay_start` = '1'");
		$out_trade_no = $this->out_trade_no;
		$dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no'");
		if(!$dingdaninfo){ return false;}	//没有该订单,失败
		if($dingdaninfo['status'] == '已付款'){
			return '已付款';
		}
		$c_money = intval($dingdaninfo['money']);
		$uid = $dingdaninfo['uid'];
		$time = time();
		
		$this->db->Autocommit_start();
		$up_q1 = $this->db->Query("UPDATE `@#_member_addmoney_record` SET `pay_type` = '盛付通', `status` = '已付款' where `id` = '$dingdaninfo[id]' and `code` = '$dingdaninfo[code]'");
		$up_q2 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + $c_money where (`uid` = '$uid')");			
		$up_q3 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '充值', '$c_money', '$time')");
		
		if($up_q1 && $up_q2 && $up_q3){			
			$this->db->Autocommit_commit();
			ActivityLotteryPayMoney($out_trade_no, $uid, $c_money);
		}else{
			$this->db->Autocommit_rollback();
			return '充值失败';
		}			
		if(empty($dingdaninfo['scookies'])){					
			return "充值完成";	//充值完成	
		}
		
		$scookies = unserialize($dingdaninfo['scookies']);			
		$pay = System::load_app_class('pay','pay');		
		$pay->scookie = $scookies;
		$ok = $pay->init($uid,$pay_type['pay_id'],'go_record');	//购买商品	
		if($ok != 'ok'){
			$_COOKIE['Cartlist'] = '';_setcookie('Cartlist',NULL);			
			return '商品购买失败';	//商品购买失败			
		}		

		$check = $pay->go_pay(1);
		if($check){
			$this->db->Query("UPDATE `@#_member_addmoney_record` SET `scookies` = '1' where `code` = '$out_trade_no' and `status` = '已付款'");
			$_COOKIE['Cartlist'] = '';_setcookie('Cartlist',NULL);
			return "商品购买成功";
		}else{
			return '商品购买失败';
		}			

	}
	
}//

?>