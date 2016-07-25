<?php 
defined('G_IN_SYSTEM')or exit('No permission resources.');
ini_set("display_errors","OFF");

include dirname(__FILE__).DIRECTORY_SEPARATOR."lib/jubaopay".DIRECTORY_SEPARATOR."jubaopay3.php";
class jubaopay_url extends SystemAction {

	public function __construct(){			
		$this->db=System::load_sys_class('model');		
	} 	
	
	public function qiantai(){	
		sleep(2);
		
		$jubaopay= new jubao(dirname(__FILE__).DIRECTORY_SEPARATOR."lib/jubaopay".DIRECTORY_SEPARATOR."/jubaopay.ini");

		$message=$_GET["message"];
		$signature=$_GET["signature"];

		$jubaopay->decrypt($message);
		// 校验签名，然后进行业务处理
		$result=$jubaopay->verify($signature);

		if($result == 1){
			
			$payid = $jubaopay->getEncrypt("payid");
			$mobile = $jubaopay->getEncrypt("mobile");
			$amount = $jubaopay->getEncrypt("amount");
			$remark = $jubaopay->getEncrypt("remark");
			$orderNo = $jubaopay->getEncrypt("orderNo");
			$state = $jubaopay->getEncrypt("state");
			$modifyTime = $jubaopay->getEncrypt("modifyTime");
			$partnerid = $jubaopay->getEncrypt("partnerid");
			$realReceive = $jubaopay->getEncrypt("realReceive");

			$out_trade_no = $payid;	//商户订单号
			$dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no'");
			if(!$dingdaninfo || $dingdaninfo['status'] == '未付款'){
				_message("支付失败");			
			}else{
				if(empty($dingdaninfo['scookies'])){
					_message("充值成功!",WEB_PATH."/member/home/userbalance");
				}else{
					if($dingdaninfo['scookies'] == '1'){
						_message("支付成功!",WEB_PATH."/member/cart/paysuccess");
					}else{
						_message("商品还未购买,请重新购买商品!",WEB_PATH."/member/cart/cartlist");
					}
						
				}
			}
		
		}else{
			_message("支付失败");	
		}
		
	}
	function file_writeTxt($filepath,$source){
if($fp=fopen($filepath,'w')){
$filesource=fwrite($fp,$source);
fclose($fp);
return $filesource;
}
else
return false;
} 
	public function houtai(){
		// $pay_type =$this->db->GetOne("SELECT * from `@#_pay` where `pay_class` = 'jubaopay' and `pay_start` = '1'");
		// $pay_type_key = unserialize($pay_type['pay_key']);
		// $MD5key = $pay_type_key['key']['val'];
	
		$message=$_POST["message"];
		$signature=$_POST["signature"];
	
		$jubaopay=new jubao(dirname(__FILE__).DIRECTORY_SEPARATOR."lib/jubaopay".DIRECTORY_SEPARATOR."/jubaopay.ini");
		
		$jubaopay->decrypt($message);
		// 校验签名，然后进行业务处理
		$result=$jubaopay->verify($signature);
		if($result==1){
			
		   // 得到解密的结果后，进行业务处理
		   // echo "payid=".$jubaopay->getEncrypt("payid")."<br />";
		   // echo "mobile=".$jubaopay->getEncrypt("mobile")."<br />";
		   // echo "amount=".$jubaopay->getEncrypt("amount")."<br />";
		   // echo "remark=".$jubaopay->getEncrypt("remark")."<br />";
		   // echo "orderNo=".$jubaopay->getEncrypt("orderNo")."<br />";
		   // echo "state=".$jubaopay->getEncrypt("state")."<br />";
		   // echo "partnerid=".$jubaopay->getEncrypt("partnerid")."<br />";
		   // echo "modifyTime=".$jubaopay->getEncrypt("modifyTime")."<br />";
		   
		   $out_trade_no=$jubaopay->getEncrypt("payid");
		   
		   $dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no' and `status` = '未付款'");
					if(!$dingdaninfo){	echo "fail";exit;}	//没有该订单,失败
					$c_money = intval($dingdaninfo['money']);			
					$uid = $dingdaninfo['uid'];
					$time = time();
					$this->db->Autocommit_start();
					$up_q1 = $this->db->Query("UPDATE `@#_member_addmoney_record` SET `pay_type` = '汇潮支付', `status` = '已付款' where `id` = '$dingdaninfo[id]' and `code` = '$dingdaninfo[code]'");
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
		   
		   
			echo "success"; // 像服务返回 "success"
		}else{
			echo "验证失败";
		}
		
	}
	
	
	
	
	
}//

?>