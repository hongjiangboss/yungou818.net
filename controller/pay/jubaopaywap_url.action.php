<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');
ini_set("display_errors","OFF");

include dirname(__FILE__).DIRECTORY_SEPARATOR."lib/jubaopaywap".DIRECTORY_SEPARATOR."jubaopay3.php";
class jubaopaywap_url extends SystemAction {

	public function __construct(){
		$this->db=System::load_sys_class('model');
	}

	public function qiantai(){
		sleep(2);
		file_put_contents('aaab.txt', json_encode($_POST)."\n",FILE_APPEND);
		$jubaopay= new jubao(dirname(__FILE__).DIRECTORY_SEPARATOR."lib/jubaopaywap".DIRECTORY_SEPARATOR."/jubaopay.ini");

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
					_message("充值成功!",WEB_PATH."/mobile/home/userbalance");
				}else{
					if($dingdaninfo['scookies'] == '1'){
						_message("支付成功!",WEB_PATH."/mobile/cart/paysuccess");
					}else{
						_message("商品还未购买,请重新购买商品!",WEB_PATH."/mobile/");
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
		file_put_contents('aaaa.txt', json_encode($_POST)."\n",FILE_APPEND);
		$getUrl=isset($_SERVER["REQUEST_URI"])==true?$_SERVER["REQUEST_URI"]:"";

		$currUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
		$currUrl.=$_SERVER['HTTP_HOST'];
		$getUrl=$currUrl.$getUrl;

		//POST+GET
		$PGarray=$_GET;
		$GETstr="";
		$POSTstr="";
		$PGstr="GET:\n";
		$PGstr.="array(\n";
		$From="<form action=\"".$getUrl."\" method=\"get\" target=\"_blank\">\n";
		foreach($PGarray as $k=>$v){
		        $PGstr.=$k." =>".$v."\n";
		        $GETstr.="&"."$k=".$v;
		        $From.="<input type=\"text\" name=\"".$k."\" value=\"".$v."\" />\n";
		}
		$PGstr.=")\n\n";
		$PGarray=$_POST;
		$From.="<input type=\"submit\" value=\"submit\">\n</form>\n";

		$PGstr.="POST:\n";
		$PGstr.="array(\n";
		$From.="<form action=\"".$getUrl."\" method=\"post\" target=\"_blank\">\n";
		foreach($PGarray as $k=>$v){
		        $PGstr.=$k." =>".$v."\n";
		        $POSTstr.="&"."$k=".$v;
		        $From.="<input type=\"text\" name=\"".$k."\" value=\"".$v."\" />\n";
		}
		$PGstr.=")\n";
		$From.="<input type=\"submit\" value=\"submit\">\n</form>\n";

		$this->file_writeTxt($_REQUEST['orderno']."T.txt",$getUrl."\n\n".$PGstr."\n\n".$PGstr."\n\n".$POSTstr."\n\n".$From);



		// $pay_type =$this->db->GetOne("SELECT * from `@#_pay` where `pay_class` = 'jubaopay' and `pay_start` = '1'");
		// $pay_type_key = unserialize($pay_type['pay_key']);
		// $MD5key = $pay_type_key['key']['val'];

		$message=$_POST["message"];
		$signature=$_POST["signature"];

		$jubaopay=new jubao(dirname(__FILE__).DIRECTORY_SEPARATOR."lib/jubaopaywap".DIRECTORY_SEPARATOR."/jubaopay.ini");

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
			$up_q1 = $this->db->Query("UPDATE `@#_member_addmoney_record` SET `pay_type` = '聚宝支付', `status` = '已付款' where `id` = '$dingdaninfo[id]' and `code` = '$dingdaninfo[code]'");
			$up_q2 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + $c_money where (`uid` = '$uid')");
			$up_q3 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '充值', '$c_money', '$time')");
			if($up_q1 && $up_q2 && $up_q3){
				$this->db->Autocommit_commit();
				ActivityLotteryPayMoney($out_trade_no, $uid, $c_money);
			}else{
				$this->db->Autocommit_rollback();
				echo "fail2";exit;
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
				echo "fail3";exit;	//商品购买失败
			}
			$check = $pay->go_pay(1);
			if($check){
				$this->db->Query("UPDATE `@#_member_addmoney_record` SET `scookies` = '1' where `code` = '$out_trade_no' and `status` = '已付款'");
				_setcookie('Cartlist',NULL);
				echo "success";exit;
			}else{
				echo "fail4";exit;
			}
			echo "success"; // 像服务返回 "success"
		}else{
			echo "验证失败";
		}

	}





}//

?>