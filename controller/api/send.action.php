<?php 

 ignore_user_abort(TRUE);
 set_time_limit(0); 
 System::load_sys_fun("send");
 System::load_sys_fun("user");
 
 class send extends SystemAction {
 
	public function init(){
	}
	
	/*
		@type  1 邮件
		@type  2 手机
		@type  3 微信
		@type  4 微信，手机，邮件
	*/
	public function send_shop_code(){
	
		if(!isset($_POST['send']) && !isset($_POST['uid']) && !isset($_POST['gid'])){
			exit(0);
		}
		$uid = abs($_POST['uid']);
		$gid = abs($_POST['gid']);
		
		$db = System::load_sys_class("model");
		
		$sendinfo = $db->GetOne("SELECT id,send_type FROM `@#_send` WHERE `gid` = '$gid' and `uid` = '$uid'");		
		if($sendinfo)exit(0);
		$member = $db->GetOne("SELECT * FROM `@#_member` WHERE `uid` = '$uid'");
		if(!$member)exit(0);
		
		$info = $db->GetOne("SELECT id,q_user_code,q_end_time,title,q_user,qishu FROM `@#_shoplist` WHERE `id` = '$gid' and `q_uid` = '$uid'");
		$qishu=$info['qishu'];
		if(!$info)exit(0);
		
		$dizhi=$db->GetOne("SELECT * FROM `@#_member_dizhi` WHERE `uid` = '$uid'");
		if($dizhi>0){ $TM="地址已填写，请核对默认发货地址";}else{$TM="未填写收货地址";}
		
		$username = get_user_name($member,'username','all');		
		$this->send_insert($uid,$gid,$username,$info['title'],'-1');
		
		
		$type = System::load_sys_config("send","type");
		if(!$type)exit(0);
		
		$q_time = abs(substr($info['q_end_time'],0,10));
		
		while(time() < $q_time){
			sleep(5);	
		}		
		
		$ret_send = false;
		
		if($type=='1'){
			if(!empty($member['email'])){
				send_email_code($member['email'],$username,$uid,$info['q_user_code'],$info['title']);
				$ret_send = true;
			}		
		}
		if($type=='2'){
			if(!empty($member['mobile'])){
				send_mobile_shop_code($member['mobile'],$uid,$info['q_user_code']);
				$ret_send = true;
			}
	
		}
		
        	if($type=='3'){
			if(!empty($member['openid'])){
				send_weixin_code($member['openid'],$username,$uid,$info['q_user_code'],$info['title'],$TM,$qishu);
				$ret_send = true;
			}
	
		}


		if($type=='4'){
			if(!empty($member['email'])){
				send_email_code($member['email'],$username,$uid,$info['q_user_code'],$info['title']);
				$ret_send = true;
			}
			if(!empty($member['mobile'])){
				send_mobile_shop_code($member['mobile'],$uid,$info['q_user_code']);
				$ret_send = true;
			}	
			if(!empty($member['openid'])){
				send_weixin_code($member['openid'],$username,$uid,$info['q_user_code'],$info['title'],$TM,$qishu);
				$ret_send = true;
			}

		}
		
		if($ret_send){			
			$this->send_insert($uid,$gid,$username,$info['title'],$type);
		}
		exit(0);
	}
	
	
	public function send_shop_reg(){
		
		
	}
	
	
	private function send_insert($uid,$gid,$username,$shoptitle,$send_type){
		$db = System::load_sys_class("model");
		$time=time();
		if($send_type == '-1'){
			$sql = "INSERT INTO `@#_send` (`uid`,`gid`,`username`,`shoptitle`,`send_type`,`send_time`) VALUES ";
			$sql.= "('$uid','$gid','$username','$shoptitle','$send_type','$time')";
			$db->Query($sql);		
		}else{
			$db->Query("UPDATE `@#_send` SET `send_type` = '$send_type' WHERE `gid` = '$gid' and `uid` = '$uid'");		
		}
	}
	
 }

?>