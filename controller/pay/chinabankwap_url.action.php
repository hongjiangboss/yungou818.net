<?php 

//defined('G_IN_SYSTEM')or exit('No permission resources.');


ini_set("display_errors","ON");
include dirname(__FILE__).'/lib/chinabankwap/ConfigUtil.php';
include dirname(__FILE__).'/lib/chinabankwap/DesUtils.php';
class chinabankwap_url extends SystemAction {
	private $out_trade_no;
	public function __construct(){			
		$this->db=System::load_sys_class('model');
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
			$this->logResult("qianming");
		$this->logResult($sb);
		return md5 ( $sb );
	}
	public function execute($md5Key, $desKey,$resp) {
		// 获取通知原始信息
		echo "异步通知原始数据:" . $resp . "\n";
		if (null == $resp) {
			return;
		}

		// 获取配置密钥
		echo "desKey:" . $desKey . "\n";
		echo "md5Key:" . $md5Key . "\n";
		// 解析XML
		$params = $this->xml_to_array ( base64_decode ( $resp ) );
		$ownSign = $this->generateSign ( $params, $md5Key );
		$params_json = json_encode ( $params );
		echo "解析XML得到对象:" . $params_json . '\n';
		echo "根据传输数据生成的签名:" . $ownSign . "\n";
		
		if ($params ['SIGN'] [0] == $ownSign) {
			// 验签不对
			echo "签名验证正确!" . "\n";
		} else {
			echo "签名验证错误!" . "\n";
			return;
		}
		// 验签成功，业务处理
		// 对Data数据进行解密
		$des = new DesUtils (); // （秘钥向量，混淆向量）
		$decryptArr = $des->decrypt ( $params ['DATA'] [0], $desKey ); // 加密字符串
		echo "对<DATA>进行解密得到的数据:" . $decryptArr . "\n";
		$params ['data'] = $decryptArr;
		echo "最终数据:" . json_encode ( $params ) . '\n';
		echo "**********接收异步通知结束。**********";
		
		return;
	}
	public function logResult($word='') {
	/* 
	$fp = fopen("log.txt","a");
	flock($fp, LOCK_EX) ;
	fwrite($fp,"\n".$word."\n");
	flock($fp, LOCK_UN);
	fclose($fp);
	*/
	}
	public function qiantai(){				
	
		if(_is_mobile()){
			$message = '_messagemobile';
		}else{
			$message = '_message';
		}
		$out_trade_no = $this->out_trade_no;
	$this->logResult("qiantai");
		$dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no'");
		if(!$dingdaninfo|| $dingdaninfo['status'] == '未付款'){
			_message("支付成功，请进入用户中心!",WEB_PATH."/mobile/home/userbalance");			
		}else{
			if(empty($dingdaninfo['scookies'])){
				_message("充值成功!",WEB_PATH."/mobile/home/userbalance");
			}else{
				if($dingdaninfo['scookies'] == '1'){
					_message("支付成功!",WEB_PATH."/mobile/cart/paysuccess");
				}else{
					_message("商品还未购买,请重新购买商品!",WEB_PATH."/member/cart/cartlist");
				}
					
			}
		}
		
	}

	public function houtai(){
		$this->logResult("houtai");
		$pay_type =$this->db->GetOne("SELECT * from `@#_pay` where `pay_class` = 'chinabankwap' and `pay_start` = '1'");
		$pay_type_key = unserialize($pay_type['pay_key']);
		$key =  $pay_type_key['key']['val'];		//支付KEY
		$id =  $pay_type_key['id']['val'];		//支付商号ID			
		$resp=$_POST['resp'];
		//$resp='PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4NCjxDSElOQUJBTks+DQogICAgPFZFUlNJT04+MS4yLjA8L1ZFUlNJT04+DQogICAgPE1FUkNIQU5UPjIyMjk0NTMxPC9NRVJDSEFOVD4NCiAgICA8VEVSTUlOQUw+MDAwMDAwMDE8L1RFUk1JTkFMPg0KICAgIDxEQVRBPkMrRFVZQ3d4WUltNUp4SVo5am94ZFI5L0hrVklnSitTMldpUzFhYWkrZDNpQ0w3ZmxCRmI5TXVkcVlYNm8xL2RMc2RlcU1aWmZUOEYKTE14OU1aa2FPNzVvRFZuYUcySURUa2pScTdDL0N4V1U4SjBzb2V4bTVHRXpLQ2toRkFKemJoVDNETWhDaFRaK3preHlJMTJ1VERzSgpibmtTR3J6a1V6MnExRlllcmdUSTJkOVJZL2h5aitZMmEwYzhsWG9UWUdxZHNhY3hVUlRETXpub3pPREplS21sRDczODJKV3pwTWJmClNMa1FCK0lBV1psNHkxUW9vNExoalBiby9hU2pnWWsrN1ZGZlpGMmM1c2tBenBXRFpFcjNnUDRPU3NzUDZjeERkYkRGeDFPWXVPNFcKKzhQa0JoOEdBT1RpUGZFZHVEVlNhbW9YWFNaZlExWUtpZnpDZkYvSC92VkV3WU9CMElXcVJqZCs1SzNWaVBhUndRdkxFWlFRRWs0UApvU2xOUFBzeW5iQ3RZU2wwdWdRR3VrNTlqdU12OXVWTFd1YWVTTGxxUXV1dHVGUUhKMWpCTUdVbGY0MXZpOHM2VEppYTwvREFUQT4NCiAgICA8U0lHTj4yZDIzODdhYzIwZTcyOTQwZjIxZjU0MzRhMzZmMzI5ZDwvU0lHTj4NCjwvQ0hJTkFCQU5LPg0KDQo=';
		$this->logResult($resp);
		$desKey = ConfigUtil::get_val_by_key ( "desKey" );
		$md5Key = ConfigUtil::get_val_by_key ( "md5Key" );
$this->logResult("des_key");
$this->logResult($desKey);
$this->logResult("md5_key");
$this->logResult($md5Key);
$this->logResult(base64_decode ( $resp ));
$this->logResult("resp end");
		if (null == $resp) {
$this->logResult("resp is null");
			return;
		}

		$params = $this->xml_to_array ( base64_decode ( $resp ) );
		$ownSign = $this->generateSign ( $params, $md5Key );
		$params_json = json_encode ( $params );
		$this->logResult($ownSign);
		$this->logResult($params_json);
		if ($params ['SIGN'] [0] == $ownSign) {
			// 验签不对
			$this->logResult("verify success");
			
		} else {
			$this->logResult("verify failed");
			
		}
		// 验签成功，业务处理
		// 对Data数据进行解密
		$des = new DesUtils (); // （秘钥向量，混淆向量）
		$decryptArr = $des->decrypt ( $params ['DATA'] [0], $desKey ); // 加密字符串
		$this->logResult($decryptArr);
		$params ['data'] = $decryptArr;
		$decryptArry = $this->xml_to_array($decryptArr);
	//	var_dump($decryptArry );
//echo $decryptArry['TRADE']['STATUS'];
		if($decryptArry['TRADE']['STATUS']=='0'){
				$this->out_trade_no=$decryptArry['TRADE']['ID'];
			//	echo $this->out_trade_no;
				$ret = $this->chinabankwap_chuli();					
				exit;
		}
		else
			exit;

				


	}
	
	
	
	private function chinabankwap_chuli(){
		$pay_type =$this->db->GetOne("SELECT * from `@#_pay` where `pay_class` = 'chinabankwap' and `pay_start` = '1'");
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
		$up_q1 = $this->db->Query("UPDATE `@#_member_addmoney_record` SET `pay_type` = '网银在线wap', `status` = '已付款' where `id` = '$dingdaninfo[id]' and `code` = '$dingdaninfo[code]'");
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
}


?>