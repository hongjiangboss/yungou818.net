<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');
System::load_app_class('base','member','no');
System::load_app_fun('my','go');
System::load_app_fun('user','go');
System::load_sys_fun('send');
System::load_sys_fun('user');
System::load_app_fun('member',ROUTE_M);
class home extends base {
	public function __construct(){
		parent::__construct();
		if(ROUTE_A!='userphotoup' and ROUTE_A!='singphotoup'){
			if(!$this->userinfo)_message("请登录",WEB_PATH."/member/user/login",3);
		}

		$this->db = System::load_sys_class('model');

	}
	public function init(){
		$member=$this->userinfo;
		$title="我的用户中心";
		$quanzi=$this->db->GetList("select * from `@#_quanzi_tiezi` order by id DESC LIMIT 5");

		$jingyan = $member['jingyan'];

		$dengji_1 = $this->db->GetOne("select * from `@#_member_group` where `jingyan_start` <= '$jingyan' and `jingyan_end` >= '$jingyan'");
		$max_jingyan_id = $dengji_1['groupid'];
		$dengji_2 = $this->db->GetOne("select * from `@#_member_group` where `groupid` > '$max_jingyan_id' order by `groupid` asc limit 1");
		if($dengji_2){
			$dengji_x = $dengji_2['jingyan_start'] - $jingyan;
 		}else{
			$dengji_x = $dengji_1['jingyan_end'] - $jingyan;
		}


		include templates("member","index");
	}


	//个人设置
	public function userphoto(){
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		$title="修改头像";
		$uid=_getcookie('uid');
		$ushell=_getcookie('ushell');
		include templates("member","photo");
	}

	//头像上传
	public function userphotoup(){

		if(!empty($_FILES)){
			$uid=isset($_POST['uid']) ? $_POST['uid'] : NULL;
			$ushell=isset($_POST['ushell']) ? $_POST['ushell'] : NULL;
			$login=$this->checkuser($uid,$ushell);
			if(!$login){echo "未登陆";exit;}

			System::load_sys_class('upload','sys','no');
			upload::upload_config(array('png','jpg','jpeg'),500000,'touimg');
			upload::go_upload($_FILES['Filedata'],true);
			$files=$_POST['typeCode'];
			if(!upload::$ok){
				echo upload::$error;
			}else{
				$img=upload::$filedir."/".upload::$filename;
				$size=getimagesize(G_UPLOAD."/touimg/".$img);
				$max=300;$w=$size[0];$h=$size[1];
				if($w>300 or $h>300){
					if($w>$h){
						$w2=$max;
						$h2 = intval($h*($max/$w));
						upload::thumbs($w2,$h2,true);
					}else{
						$h2=$max;
						$w2 = intval($w*($max/$h));
						upload::thumbs($w2,$h2,true);
					}
				}
				echo "touimg/".$img;
			}
		}
	}

	//头像裁剪
	public function userphotoinsert(){
		$uid = $this->userinfo['uid'];
		if(isset($_POST["submit"])){
			$tname  = trim(str_ireplace(" ","",$_POST['img']));
			$tname  = _htmtocode($tname);
			if(!file_exists(G_UPLOAD.$tname)){_message("头像修改失败",WEB_PATH."/member/home/userphoto",3);}
			$x = (int)$_POST['x'];
			$y = (int)$_POST['y'];
			$w = (int)$_POST['w'];
			$h = (int)$_POST['h'];
			$point = array("x"=>$x,"y"=>$y,"w"=>$w,"h"=>$h);

			System::load_sys_class('upload','sys','no');
			upload::thumbs(160,160,false,G_UPLOAD.$tname,$point);
			upload::thumbs(80,80,false,G_UPLOAD.$tname,$point);
			upload::thumbs(30,30,false,G_UPLOAD.$tname,$point);

			$this->db->Query("UPDATE `@#_member` SET img='$tname' where uid='$uid'");
			_message("头像修改成功",WEB_PATH."/member/home/userphoto",3);

		}
	}

	//个人资料
	public function modify(){

		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		$title="编辑个人资料";
		$member_qq=$this->db->GetOne("select * from `@#_member_band` where `b_uid`=$member[uid]");
		include templates("member","modify");
	}
	//邮箱验证
	public function mailchecking(){
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		$title="邮箱验证";
		if($member['email'] && $member['emailcode'] == 1){
			_message("您的邮箱已经验证成功,请勿重复验证！");
		}
		include templates("member","mailchecking");

	}
	public function mailchackajax(){
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		$member2=$mysql_model->GetOne("select uid from `@#_member` where `email`='".$_POST['param']."'");
		if(!empty($member2)){
			echo "邮箱已经存在";
		}else{
			echo '{
					"info":"",
					"status":"y"
				}';
		}
	}



	//发送验证邮件
	public function sendsuccess(){
		if(!isset($_POST['submit']))_message("参数错误",WEB_PATH.'/member/home/modify');
		if(!isset($_POST['email']) || empty($_POST['email']))_message("邮箱地址不能为空!",WEB_PATH.'/member/home/modify');
		if(!_checkemail($_POST['email']))_message("邮箱格式错误!",WEB_PATH.'/member/home/modify');

		$config_email = System::load_sys_config("email");
		if(empty($config_email['user']) && empty($config_email['pass'])){
					_message("系统邮箱配置不正确!",WEB_PATH.'/member/home/modify');
		}

		$member=$this->userinfo;
		$title="发送成功";
		$email = $_POST['email'];

		$member2=$this->db->GetOne("select * from `@#_member` where `email`='$email' and `uid` != '$member[uid]'");
		if(!empty($member2) && $member2['emailcode'] == 1){
			_message("该邮箱已经存在，请选择另外的邮箱验证！",WEB_PATH.'/member/home/modify');
		}

		$strcode1=$email.",".$member['uid'].",".time();
		$strcode= _encrypt($strcode1);

		$tit=$this->_cfg['web_name_two']."激活注册邮箱";
		$content='<span>请在24小时内绑定邮箱</span>，点击链接：<a href="'.WEB_PATH.'/member/home/emailcheckingok/'.$strcode.'">';
		$content.=WEB_PATH.'/member/home/emailcheckingok/'.$strcode.'</a>';
		$succ=_sendemail($email,'',$tit,$content,'yes','no');
		if($succ=='no'){
				_message("邮件发送失败!",WEB_PATH.'/member/home/modify',30);
		}else{
				include templates("member","sendsuccess");
		}

	}

	//邮箱认证返回
	public function emailcheckingok(){
		$member=$this->userinfo;
		$key=$this->segment(4);
		if($this->segment(5)){
			$key.='/'.$this->segment(5);
		}

		$emailcode=_encrypt($key,'DECODE');
		if(empty($emailcode)){
			 _message("认证失败,参数不正确！",null,3);
		}
		$memberx=explode(",",$emailcode);
		$email=$memberx[0];
		$timec=(time()-$memberx[2])/(60*60);
		$qmember=$this->db->GetOne("select * from `@#_member` where `email`='$email' and `uid` != '$member[uid]'");
		if($qmember && $qmember['emailcode']==1){
			_message("该邮箱已被认证,请勿重复认证!",WEB_PATH.'/member/home');
		}
		if($timec<24){
			$this->db->Query("UPDATE `@#_member` SET email='".$memberx[0]."',emailcode='1' where uid='$member[uid]'");
			$title="邮箱验证完成";
			include templates("member","sendsuccess2");
		}else{
			_message("认证时间已过期!",null,3);
		}
	}
	public function mobilechecking(){
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		$title="手机验证";
		if($member['mobile'] && $member['mobilecode'] == 1){
			_message("您的手机已经验证成功,请勿重复验证！");
		}
		include templates("member","mobilechecking");
	}

	//手机验证
	public function mobilesuccess(){

		$title="手机验证";
		$member=$this->userinfo;

		if(isset($_POST['submit'])){
			$mobile=isset($_POST['mobile']) ? $_POST['mobile'] : "";
			if(!_checkmobile($mobile) || $mobile==null){
				_message("手机号错误",null,3);
			}
			$member2=$this->db->GetOne("select mobilecode,uid,mobile from `@#_member` where `mobile`='$mobile' and `uid` != '$member[uid]'");
			if($member2 && $member2['mobilecode'] == 1){
				_message("手机号已被注册！");
			}
			if($member['mobilecode']!=1){
				//验证码
				$ok = send_mobile_reg_code($mobile,$member['uid']);
				if($ok[0]!=1){
					_message("发送失败,失败状态:".$ok[1]);
				}else{
					_setcookie("mobilecheck",base64_encode($mobile));
				}
			}
			$time=120;
			include templates("member","mobilesuccess");
		}
	}
	//重发手机验证码
	public function sendmobile(){
		$member=$this->userinfo;
		$mobilecodes=rand(100000,999999).'|'.time();//验证码

		if($member['mobilecode']==1){_message("该账号验证成功",WEB_PATH."/member/home");}

		$checkcode=explode("|",$member['mobilecode']);
		$times=time()-$checkcode[1];
		if($times > 120){
			//重发验证码
				$ok = send_mobile_reg_code($member['mobile'],$member['uid']);
				if($ok[0]!=1){
					_message("发送失败,失败状态:".$ok[1]);
				}

			_message("正在重新发送...",WEB_PATH."/member/user/mobilecheck/"._encrypt($member['mobile']),2);
		}else{
			_message("重发时间间隔不能小于2分钟!",WEB_PATH."/member/user/mobilecheck/"._encrypt($member['mobile']));
		}

	}
	public function mobilecheck(){
		$member=$this->userinfo;
		if(isset($_POST['submit'])){
			$shoujimahao =  base64_decode(_getcookie("mobilecheck"));
			if(!_checkmobile($shoujimahao))_message("手机号码错误!");
			$checkcodes=isset($_POST['mobile']) ? $_POST['mobile'] : _message("参数不正确!");
			if(strlen($checkcodes)!=6)_message("验证码输入不正确!");
			$usercode=explode("|",$member['mobilecode']);

			if($checkcodes!=$usercode[0])_message("验证码输入不正确!");
			$this->db->Query("UPDATE `@#_member` SET `mobilecode`='1',`mobile` = '$shoujimahao' where `uid`='$member[uid]'");
			//福分、经验添加
			$isset_user=$this->db->GetList("select `uid` from `@#_member_account` where `content`='手机认证完善奖励' and `type`='1' and `uid`='$member[uid]' and (`pay`='经验' or `pay`='福分')");
			if(empty($isset_user)){
				$config = System::load_app_config("user_fufen");//福分/经验
				$time=time();
				$this->db->Query("insert into `@#_member_account` (`uid`,`type`,`pay`,`content`,`money`,`time`) values ('$member[uid]','1','福分','手机认证完善奖励','$config[f_phonecode]','$time')");
				$this->db->Query("insert into `@#_member_account` (`uid`,`type`,`pay`,`content`,`money`,`time`) values ('$member[uid]','1','经验','手机认证完善奖励','$config[z_phonecode]','$time')");
				$this->db->Query("UPDATE `@#_member` SET `score`=`score`+'$config[f_phonecode]',`jingyan`=`jingyan`+'$config[z_phonecode]' where uid='".$member['uid']."'");
			}
			_setcookie("uid",_encrypt($member['uid']));
			_setcookie("ushell",_encrypt(md5($member['uid'].$member['password'].$member['mobile'].$member['email'])));

			//福分、经验添加
			$isset_user=$this->db->GetOne("select `uid` from `@#_member_account` where (`pay`='手机认证完善奖励' or `pay`='经验') and `type`='1' and `uid`='$member[uid]'");
			if(empty($isset_user)){
				$config = System::load_app_config("user_fufen");//福分/经验
				$time=time();

				$this->db->Query("insert into `@#_member_account` (`uid`,`type`,`pay`,`content`,`money`,`time`) values ('$member[uid]','1','福分','手机认证完善奖励','$config[f_phonecode]','$time')");
				$this->db->Query("insert into `@#_member_account` (`uid`,`type`,`pay`,`content`,`money`,`time`) values ('$member[uid]','1','经验','手机认证完善奖励','$config[z_phonecode]','$time')");
				$this->db->Query("UPDATE `@#_member` SET `score`=`score`+'$config[f_phonecode]',`jingyan`=`jingyan`+'$config[z_phonecode]' where uid='".$member['uid']."'");
			}
			_message("验证成功",WEB_PATH."/member/home/modify");
		}else{
			_message("页面错误",null,3);
		}
	}
	public function usermodify(){
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		if(isset($_POST['submit'])){
			$username=_htmtocode(trim($_POST['username']));
			$username = str_ireplace("'","",$username);
			$qianming=_htmtocode(trim($_POST['qianming']));
			$reg_user_str = $this->db->GetOne("select value from `@#_caches` where `key` = 'member_name_key' limit 1");
			$reg_user_str = explode(",",$reg_user_str['value']);
			if(is_array($reg_user_str) && !empty($username)){
				foreach($reg_user_str as $rv){
					if($rv == $username){
						_message("此昵称禁止使用!");
					}
				}

			}
				//福分、经验添加

			$isset_user=$this->db->GetOne("select `uid` from `@#_member_account` where `content`='完善昵称奖励' and `type`='1' and `uid`='$member[uid]' and (`pay`='经验' or `pay`='福分')");

			if(!$isset_user){

				$config = System::load_app_config("user_fufen");//福分/经验

				$time=time();



				$this->db->Query("insert into `@#_member_account` (`uid`,`type`,`pay`,`content`,`money`,`time`) values ('$member[uid]','1','福分','完善昵称奖励','$config[f_overziliao]','$time')");

				$this->db->Query("insert into `@#_member_account` (`uid`,`type`,`pay`,`content`,`money`,`time`) values ('$member[uid]','1','经验','完善昵称奖励','$config[z_overziliao]','$time')");

				$mysql_model->Query("UPDATE `@#_member` SET username='".$username."',qianming='".$qianming."',`score`=`score`+'$config[f_overziliao]',`jingyan`=`jingyan`+'$config[z_overziliao]' where uid='".$member['uid']."'");

			}

			$mysql_model->Query("UPDATE `@#_member` SET username='".$username."',qianming='".$qianming."' where uid='".$member['uid']."'");

			_message("修改成功",WEB_PATH."/member/home/modify",3);



		}

	}
	public function address(){
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		$title="收货地址";
		$member_dizhi=$mysql_model->Getlist("select * from `@#_member_dizhi` where uid='".$member['uid']."' limit 5");
		foreach($member_dizhi as $k=>$v){
			$member_dizhi[$k] = _htmtocode($v);
		}
		$count=count($member_dizhi);
		include templates("member","address");
	}
	public function morenaddress(){

		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		$member_dizhi=$mysql_model->Getlist("select * from `@#_member_dizhi` where uid='".$member['uid']."'");
		foreach($member_dizhi as $dizhi){
			if($dizhi['default']=='Y'){
				$mysql_model->Query("UPDATE `@#_member_dizhi` SET `default`='N' where uid='".$member['uid']."'");
			}
		}
		$id = $this->segment(4);
		$id = abs(intval($id));
		if(isset($id)){
		$mysql_model->Query("UPDATE `@#_member_dizhi` SET `default`='Y' where id='".$id."'");
		echo _message("修改成功",WEB_PATH."/member/home/address",3);
		}
	}

	public function deladdress(){
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		$id=$this->segment(4);
		$id = abs(intval($id));
		$dizhi=$mysql_model->Getone("select * from `@#_member_dizhi` where `uid`='$member[uid]' and `id`='$id'");
		if(!empty($dizhi)){
			$mysql_model->Query("DELETE FROM `@#_member_dizhi` WHERE `uid`='$member[uid]' and `id`='$id'");
			header("location:".WEB_PATH."/member/home/address");
		}else{
			echo _message("删除失败",WEB_PATH."/member/home/address",0);
		}
	}
	public function updateddress(){
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		$uid=$member['uid'];
		$id = $this->segment(4);
		$id = abs(intval($id));
		if(isset($_POST['submit'])){
			$sheng=isset($_POST['sheng']) ? $_POST['sheng'] : "";
			$shi=isset($_POST['shi']) ? $_POST['shi'] : "";
			$xian=isset($_POST['xian']) ? $_POST['xian'] : "";
			$jiedao=isset($_POST['jiedao']) ? $_POST['jiedao'] : "";
			$youbian=isset($_POST['youbian']) ? $_POST['youbian'] : "";
			$shouhuoren=isset($_POST['shouhuoren']) ? $_POST['shouhuoren'] : "";
				$zhifubao=isset($_POST['zhifubao']) ? $_POST['zhifubao'] : "";
			$tell=isset($_POST['tell']) ? $_POST['tell'] : "";
			$mobile=isset($_POST['mobile']) ? $_POST['mobile'] : "";
			$aname=isset($_POST['aname']) ? $_POST['aname'] : "";
			$time=time();
			if($sheng==null or $jiedao==null or $shouhuoren==null or $mobile==null){
				echo "带星号不能为空;";
				exit;
			}
			if(!_checkmobile($mobile)){
				echo "手机号错误;";
				exit;
			}
		$mysql_model->Query("UPDATE `@#_member_dizhi` SET
		`uid`='".$uid."',
		`sheng`='".$sheng."',
		`shi`='".$shi."',
		`xian`='".$xian."',
		`jiedao`='".$jiedao."',
		`youbian`='".$youbian."',
		`shouhuoren`='".$shouhuoren."',
		`zhifubao`='".$zhifubao."',
		`tell`='".$tell."',
		`aname`='".$aname."',
		`mobile`='".$mobile."' where `id`='".$id."' and uid=".$uid);
		_message("修改成功",WEB_PATH."/member/home/address",3);
		}
	}
	public function useraddress(){
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		$uid=$member['uid'];
		if(isset($_POST['submit'])){
			foreach($_POST as $k=>$v){
				$_POST[$k] = _htmtocode($v);
			}
			$sheng=isset($_POST['sheng']) ? $_POST['sheng'] : "";
			$shi=isset($_POST['shi']) ? $_POST['shi'] : "";
			$xian=isset($_POST['xian']) ? $_POST['xian'] : "";
			$jiedao=isset($_POST['jiedao']) ? $_POST['jiedao'] : "";
			$youbian=isset($_POST['youbian']) ? $_POST['youbian'] : "";
			$shouhuoren=isset($_POST['shouhuoren']) ? $_POST['shouhuoren'] : "";
				$zhifubao=isset($_POST['zhifubao']) ? $_POST['zhifubao'] : "";
			$tell=isset($_POST['tell']) ? $_POST['tell'] : "";
			$mobile=isset($_POST['mobile']) ? $_POST['mobile'] : "";
			$time=time();
			if($sheng==null or $jiedao==null or $shouhuoren==null or $mobile==null){
				echo "带星号不能为空;";
				exit;
			}
			if(!_checkmobile($mobile)){
				echo "手机号错误;";
				exit;
			}
			$member_dizhi=$mysql_model->GetOne("select * from `@#_member_dizhi` where `uid`='".$member['uid']."'");
			if(!$member_dizhi){
				$default="Y";
			}else{
				$default="N";
			}
			$mysql_model->Query("INSERT INTO `@#_member_dizhi`(`uid`,`sheng`,`shi`,`xian`,`jiedao`,`youbian`,`shouhuoren`,`zhifubao`,`tell`,`mobile`,`default`,`time`)VALUES
			('$uid','$sheng','$shi','$xian','$jiedao','$youbian','$shouhuoren','$zhifubao','$tell','$mobile','$default','$time')");
			_message("收货地址添加成功",WEB_PATH."/member/home/address",3);
		}
	}

	public function password(){
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		$title="密码修改";
		include templates("member","password");
	}
	public function oldpassword(){
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		if($member['password']==md5($_POST['param'])){
			echo '{
					"info":"",
					"status":"y"
				}';
		}else{
			echo "原密码错误";
		}
	}
	public function userpassword(){
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		//$member=$mysql_model->GetOne("select * from `@#_member` where uid='".$member['uid']."'");
		$password=isset($_POST['password']) ? $_POST['password'] : "";
		$userpassword=isset($_POST['userpassword']) ? $_POST['userpassword'] : "";
		$userpassword2=isset($_POST['userpassword2']) ? $_POST['userpassword2'] : "";
		if($password==null or $userpassword==null or $userpassword2==null){
				echo "密码不能为空;";
				exit;
		}

		if(strlen($_POST['password']) < 6 || strlen($_POST['password']) > 20){
			echo "密码不能小于6位或者大于20位";
			exit;
		}
		if($_POST['userpassword']!==$_POST['userpassword2']){
			echo "二次密码不一致";
			exit;
		}
		$password=md5($password);
		$userpassword=md5($userpassword);
		if($member['password']!=$password){
			echo _message("原密码错误",null,3);
		}else{
			$mysql_model->Query("UPDATE `@#_member` SET password='".$userpassword."' where uid='".$member['uid']."'");
			echo _message("密码修改成功",null,3);
		}
	}
	//购买记录
	public function userbuylist(){
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		$uid = $member['uid'];
		$title="购买记录 - "._cfg("web_name");

		$total=$this->db->GetCount("select * from `@#_member_go_record` where `uid`='$uid' order by `id` DESC");
		$page=System::load_sys_class('page');
		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}
		$page->config($total,10,$pagenum,"0");
		$record = $this->db->GetPage("select * from `@#_member_go_record` where `uid`='$uid' order by `id` DESC",array("num"=>10,"page"=>$pagenum,"type"=>1,"cache"=>0));

		include templates("member","userbuylist");
	}
	//购买记录
	public function jf_userbuylist(){
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		$uid = $member['uid'];
		$title="福分购买记录 - "._cfg("web_name");

		$total=$this->db->GetCount("select * from `@#_member_go_jf_record` where `uid`='$uid' order by `id` DESC");
		$page=System::load_sys_class('page');
		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}
		$page->config($total,10,$pagenum,"0");
		$record = $this->db->GetPage("select * from `@#_member_go_jf_record` where `uid`='$uid' order by `id` DESC",array("num"=>10,"page"=>$pagenum,"type"=>1,"cache"=>0));

		include templates("member","jf_userbuylist");
	}

	//购买记录详细
	public function userbuydetail(){
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		$title="购买详情";
		$crodid=intval($this->segment(4));
		$record=$mysql_model->GetOne("select * from `@#_member_go_record` where `id`='$crodid' and `uid`='$member[uid]' LIMIT 1");
		if(!$record){
			_message("页面错误",WEB_PATH."/member/home/userbuylist",3);
		}
		$shopinfo=$mysql_model->GetOne("select thumb from `@#_shoplist` where `id`='$record[shopid]' LIMIT 1");
		$record['thumb'] = $shopinfo['thumb'];
		if($crodid>0){
			include templates("member","userbuydetail");
		}else{
			_message("页面错误",WEB_PATH."/member/home/userbuylist",3);
		}
	}
	//购买记录详细
	public function jf_userbuydetail(){
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		$title="购买详情";
		$crodid=intval($this->segment(4));
		$record=$mysql_model->GetOne("select * from `@#_member_go_jf_record` where `id`='$crodid' and `uid`='$member[uid]' LIMIT 1");
		if(!$record){
			_message("页面错误",WEB_PATH."/member/home/jf_userbuylist",3);
		}
		$shopinfo=$mysql_model->GetOne("select thumb from `@#_jf_shoplist` where `id`='$record[shopid]' LIMIT 1");
		$record['thumb'] = $shopinfo['thumb'];
		if($crodid>0){
			include templates("member","jf_userbuydetail");
		}else{
			_message("页面错误",WEB_PATH."/member/home/jf_userbuylist",3);
		}
	}

	//获得的商品
	public function orderlist(){
		$member=$this->userinfo;
		$uid = $member['uid'];
		$title="获得的商品 - "._cfg("web_name");

		$total=$this->db->GetCount("select * from `@#_member_go_record` where `uid`='$uid' and `huode`>'10000000'");
		$page=System::load_sys_class('page');
		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}
		$page->config($total,10,$pagenum,"0");
		$record = $this->db->GetPage("select * from `@#_member_go_record` where `uid`='$uid' and `huode`>'10000000' ORDER BY id DESC",array("num"=>10,"page"=>$pagenum,"type"=>1,"cache"=>0));

		foreach($record as $ckey=>$cord){
			$jiexiao = get_shop_if_jiexiao($cord['shopid']);
			if(!$jiexiao){
				unset($record[$ckey]);
			}
		}
		$address = $this->db->GetList("select * from `@#_member_dizhi` where uid='$uid'");
		include templates("member","orderlist");
	}

	//获得的商品
	public function jf_orderlist(){
		$member=$this->userinfo;
		$uid = $member['uid'];
		$title="获得的商品 - "._cfg("web_name");

		$total=$this->db->GetCount("select * from `@#_member_go_jf_record` where `uid`='$uid'");
		$page=System::load_sys_class('page');
		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}
		$page->config($total,10,$pagenum,"0");
		$record = $this->db->GetPage("select * from `@#_member_go_jf_record` where `uid`='$uid' ORDER BY id DESC",array("num"=>10,"page"=>$pagenum,"type"=>1,"cache"=>0));

		$address = $this->db->GetList("select * from `@#_member_dizhi` where uid='$uid'");

		include templates("member","jf_orderlist");
	}
	//账户管理
	public function userbalance(){
		$member=$this->userinfo;
		$uid = $member['uid'];
		$title="账户记录 - "._cfg("web_name");

		$total=$this->db->GetCount("select * from `@#_member_account` where `uid`='$uid' and `pay` = '账户'");
		$page=System::load_sys_class('page');
		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}
		$page->config($total,20,$pagenum,"0");
		$account = $this->db->GetPage("select * from `@#_member_account` where `uid`='$uid' and `pay` = '账户' ORDER BY time DESC",array("num"=>20,"page"=>$pagenum,"type"=>1,"cache"=>0));

		include templates("member","userbalance");
	}

	//账户福分
	public function userfufen(){
		$member=$this->userinfo;
		$uid = $member['uid'];
		$title="账户福分 - "._cfg("web_name");

		$total=$this->db->GetCount("select * from `@#_member_account` where `uid`='$uid' and `pay` = '福分'");
		$page=System::load_sys_class('page');
		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}
		$page->config($total,20,$pagenum,"0");
		$account = $this->db->GetPage("select * from `@#_member_account` where `uid`='$uid' and `pay` = '福分' ORDER BY time DESC",array("num"=>20,"page"=>$pagenum,"type"=>1,"cache"=>0));

		include templates("member","userfufen");
	}


	public function userrecharge(){
		$member=$this->userinfo;
	    $title="账户充值";
		$paylist = $this->db->GetList("SELECT * FROM `@#_pay` where `pay_start` = '1'and `pay_class`!='wapalipay'and `pay_class`!='chinabankwap'and `pay_class`!='wxpay_web'and `pay_class`!='unionpay_web'and `pay_class`!='wapjdpay'and `pay_class`!='jubaopaywap'  order by `pay_id` asc ");	
		
		include templates("member","userrecharge");
	}
public function usertransfer(){
		$member=$this->userinfo;
		$title="转帐";
	
		if(isset($_POST['submit1'])){
			
			$tmoney=$_POST[money];
			$tuser=$_POST[txtBankName];
			if($member[score]<1000)
				_message("帐户福分不得小于1000",null,3);
		if($_POST[money]<1000)
				_message("转帐福分不得小于1000",null,3);
			if(empty($tmoney)||empty($tuser))
				_message("转入用户和金额不得为空",null,3);
			if($tmoney>$member[score])
				_message("转入福分不得大于帐户福分",null,3);
			$user= $this->db->GetOne("SELECT * FROM `@#_member` where `email` = '$tuser' limit 1");	
			if(empty($user))
				$user= $this->db->GetOne("SELECT * FROM `@#_member` where `mobile` = '$tuser' limit 1");	
			if(empty($user))
					_message("转入用户不存在",null,3);
			$uid=$member[uid];
			$tuid=$user[uid];
		if($uid==$tuid)
					_message("不能给自己转帐",null,3);
			$time=time();
			$cmoney=$member[score]-$tmoney;
			$ctmoney=$user[score]+$tmoney;
			$name=get_user_name($uid,'username','all');
			$tname=get_user_name($tuid,'username','all');
			

$this->db->Query("update `@#_member` SET `score`='$cmoney' WHERE `uid`='$uid'");
$this->db->Query("update `@#_member` SET `score`='$ctmoney' WHERE `uid`='$tuid'");

$this->db->Query("insert into `@#_member_op_record` (`uid`,`username`,`deltamoney`,`premoney`,`money`,`time`) values ('$uid','$name','-$tmoney','$member[money]','$cmoney','$time')");
$this->db->Query("insert into `@#_member_op_record` (`uid`,`username`,`deltamoney`,`premoney`,`money`,`time`) values ('$tuid','$tname','$tmoney','$user[money]','$ctmoney','$time')");

 $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '福分', '转出到<$tname>', '$tmoney', '$time')");
  $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$tuid', '1', '福分', '由<$name>转入', '$tmoney', '$time')");
				
				
		_message("给".$tname."的".$tmoney."福分冲值成功
!",null,3);		
		}
		
		include templates("member","usertransfer");
	}	
	//圈子管理
	public function joingroup(){
		$member=$this->userinfo;
		$title="加入的圈子";
		$addgroup=rtrim($member['addgroup'],",");
		if($addgroup){
			$group=$this->db->GetList("select * from `@#_quanzi` where `id` in ($addgroup)");
		}else{
			$group=null;
		}
		include templates("member","joingroup");
	}
	public function topic(){
		$member=$this->userinfo;
		$title="圈子话题";
		$tiezi=$this->db->GetList("select * from `@#_quanzi_tiezi` where `hueiyuan`='$member[uid]'");
		$hueifu=$this->db->GetList("select * from `@#_quanzi_hueifu` where `hueiyuan`='$member[uid]'");
		include templates("member","topic");
	}
	public function tiezidel(){
		$member=$this->userinfo;
		$id = $this->segment(4);
		$id = abs(intval($id));
		$tiezi=$this->db->Getone("select * from `@#_quanzi_tiezi` where `hueiyuan`='$member[uid]' and  `id`='$id'");
		if($tiezi){
			$this->db->Query("DELETE FROM `@#_quanzi_tiezi` WHERE `hueiyuan`='$member[uid]' and  `id`='$id'");
			_message("删除成功",WEB_PATH."/member/home/topic");
		}else{
			_message("删除失败",WEB_PATH."/member/home/topic");
		}
	}
	public function hueifudel(){
		$member=$this->userinfo;
		$id = $this->segment(4);
		$id = abs(intval($id));
		$hueifu=$this->db->Getone("select * from `@#_quanzi_hueifu` where `id`='$id'");
		if($hueifu){
			$this->db->Query("DELETE FROM `@#_quanzi_hueifu` WHERE `id`='$id'");
			_message("删除成功",WEB_PATH."/member/home/topic");
		}else{
			_message("删除失败",WEB_PATH."/member/home/topic");
		}
	}


	//晒单
	public function singlelist(){
		$member=$this->userinfo;
		$title="我的晒单";
		$cord=$this->db->Getlist("select a.* from `@#_member_go_record` as a left join `@#_shoplist` as b on a.shopid=b.id where a.`uid`='$member[uid]' and a.`huode` > '10000000' and b.q_showtime='N'");

		$shaidan=$this->db->Getlist("select * from `@#_shaidan` where `sd_userid`='$member[uid]' order by `sd_id`");

		$sd_id = $r_id = array();
		foreach($shaidan as $sd){
			if(!empty($sd['sd_shopid'])){
				$sd_id[]=$sd['sd_shopid'];
			}
		}

		foreach($cord as $rd){
			if(!in_array($rd['shopid'],$sd_id)){
				$r_id[]=$rd['shopid'];
			}
		}
		if(!empty($r_id)){
			$rd_id=implode(",",$r_id);
			$rd_id = trim($rd_id,',');
		}else{
			$rd_id="0";
		}

		$record = $this->db->Getlist("select a.shopid,a.id from `@#_member_go_record` as a left join `@#_shoplist` as b on a.shopid=b.id where a.shopid in ($rd_id) and a.`uid`='$member[uid]' and a.`huode`>'10000000' and b.q_showtime='N'");
		$record_jf = $this->db->Getlist("select a.shopid,a.id from `@#_member_go_jf_record` as a left join `@#_jf_shoplist` as b on a.shopid=b.id where a.share=0 and a.`uid`='$member[uid]'");

		$total=$this->db->GetCount("select * from `@#_shaidan` where `sd_userid`='$member[uid]' order by `sd_id`");
		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}
		$page=System::load_sys_class('page');
		$page->config($total,10,$pagenum,"0");

		$shaidan=$this->db->GetPage("select * from `@#_shaidan` where `sd_userid`='$member[uid]' order by `sd_id`",array("num"=>10,"page"=>$pagenum,"type"=>1,"cache"=>0));

		include templates("member","singlelist");
	}

	/*添加晒单*/
	public function singleinsert(){
		$member=$this->userinfo;
		$uid=_getcookie('uid');
		$ushell=_getcookie('ushell');
		$title="添加晒单";

		$recordid=intval($this->segment(4));
		$shopid = $recordid;
		$shaidan=$this->db->GetOne("select * from `@#_member_go_record` where `id`='$recordid' and `uid` = '$member[uid]'");
		if(!$shaidan){
			_message("该商品您不可晒单!");
		}
		$shaidanyn=$this->db->GetOne("select sd_id from `@#_shaidan` where `sd_shopid`='$recordid' and `sd_userid` = '$member[uid]'");
		if($shaidanyn){
			_message("不可重复晒单!");
		}
		$ginfo=$this->db->GetOne("select id,sid,qishu,is_share from `@#_shoplist` where `id`='$shaidan[shopid]' LIMIT 1");
		if(!$ginfo){
			_message("该商品已不存在!");
		}
		if($ginfo['is_share']!='1'){
			_message("对不起，该商品不能晒单!");
		}

		if(isset($_POST['submit'])){


			if($_POST['title']==null)_message("标题不能为空");
			if($_POST['content']==null)_message("内容不能为空");
			if(!isset($_POST['fileurl_tmp'])){
				_message("图片不能为空");
			}
			System::load_sys_class('upload','sys','no');
			$img=$_POST['fileurl_tmp'];
			$num=count($img);
			$pic="";
			for($i=0;$i<$num;$i++){
				$pic.=trim($img[$i]).";";
			}

			$src=trim($img[0]);

			if(!file_exists(G_UPLOAD.$src)){
					_message("晒单图片不正确");
			}
			$size=getimagesize(G_UPLOAD.$src);
			$width=220;
			$height=$size[1]*($width/$size[0]);

			$src_houzhui = upload::thumbs($width,$height,false,G_UPLOAD.'/'.$src);
			$thumbs=$src."_".intval($width).intval($height).".".$src_houzhui;


			$sd_userid = $this->userinfo['uid'];
			$sd_shopid = $ginfo['id'];
			$sd_shopsid = $ginfo['sid'];
			$sd_qishu = $ginfo['qishu'];
			$sd_title = _htmtocode($_POST['title']);
			$sd_thumbs = $thumbs;
			$sd_content = str_str_replace($_POST['content']);
			$sd_photolist= $pic;
			$sd_time=time();
			$sd_ip = _get_ip_dizhi();
			$this->db->Query("INSERT INTO `@#_shaidan`(`sd_userid`,`sd_shopid`,`sd_shopsid`,`sd_qishu`,`sd_ip`,`sd_title`,`sd_thumbs`,`sd_content`,`sd_photolist`,`sd_time`)VALUES
			('$sd_userid','$sd_shopid','$sd_shopsid','$sd_qishu','$sd_ip','$sd_title','$sd_thumbs','$sd_content','$sd_photolist','$sd_time')");
			_message("晒单分享成功",WEB_PATH."/member/home/singlelist");
		}

		include templates("member","singleinsert");
	}

	/*添加晒单*/
	public function jf_singleinsert(){
		$member=$this->userinfo;
		$uid=_getcookie('uid');
		$ushell=_getcookie('ushell');
		$title="添加晒单";

		$recordid=intval($this->segment(4));
		$shopid = $recordid;
		$shaidan=$this->db->GetOne("select * from `@#_member_go_jf_record` where `id`='$recordid' and `uid` = '$member[uid]'");
		if(!$shaidan){
			_message("该商品您不可晒单!");
		}
		$shaidanyn=$this->db->GetOne("select sd_id from `@#_shaidan` where `sd_jfshopid`='$recordid' and `sd_userid` = '$member[uid]'");
		if($shaidanyn){
			_message("不可重复晒单!");
		}
		$ginfo=$this->db->GetOne("select id,sid,qishu,is_share from `@#_jf_shoplist` where `id`='$shaidan[shopid]' LIMIT 1");
		if(!$ginfo){
			_message("该商品已不存在!");
		}
		if($ginfo['is_share']!='1'){
			_message("对不起，该商品不能晒单!");
		}

		if(isset($_POST['submit'])){
			if($_POST['title']==null)_message("标题不能为空");
			if($_POST['content']==null)_message("内容不能为空");
			if(!isset($_POST['fileurl_tmp'])){
				_message("图片不能为空");
			}
			System::load_sys_class('upload','sys','no');
			$img=$_POST['fileurl_tmp'];
			$num=count($img);
			$pic="";
			for($i=0;$i<$num;$i++){
				$pic.=trim($img[$i]).";";
			}

			$src=trim($img[0]);

			if(!file_exists(G_UPLOAD.$src)){
					_message("晒单图片不正确");
			}
			$size=getimagesize(G_UPLOAD.$src);
			$width=220;
			$height=$size[1]*($width/$size[0]);

			$src_houzhui = upload::thumbs($width,$height,false,G_UPLOAD.'/'.$src);
			$thumbs=$src."_".intval($width).intval($height).".".$src_houzhui;


			$sd_userid = $this->userinfo['uid'];
			$sd_shopid = $ginfo['id'];
			$sd_shopsid = $ginfo['sid'];
			$sd_qishu = $ginfo['qishu'];
			$sd_title = _htmtocode($_POST['title']);
			$sd_thumbs = $thumbs;
			$xss = new XssHtml($_POST['content']);
			$sd_content = $xss->getHtml();
			$sd_photolist= $pic;
			$sd_time=time();
			$sd_ip = _get_ip_dizhi();
			$this->db->Query("INSERT INTO `@#_shaidan`(`sd_userid`,`sd_jfshopid`,`sd_shopsid`,`sd_qishu`,`sd_ip`,`sd_title`,`sd_thumbs`,`sd_content`,`sd_photolist`,`sd_time`)VALUES
			('$sd_userid','$sd_shopid','$sd_shopsid','$sd_qishu','$sd_ip','$sd_title','$sd_thumbs','$sd_content','$sd_photolist','$sd_time')");
			$this->db->Query("update `@#_member_go_jf_record` set share=1 where id=$recordid");
			_message("晒单分享成功",WEB_PATH."/member/home/singlelist");
		}

		include templates("member","jf_singleinsert");
	}

	//编辑
	public function singleupdate(){
		_message("不可编辑!");
		if(isset($_POST['submit'])){
			System::load_sys_class('upload','sys','no');
			if($_POST['title']==null)_message("标题不能为空");
			if($_POST['content']==null)_message("内容不能为空");
			$sd_id=$_POST['sd_id'];
			$shaidan=$this->db->GetOne("select * from `@#_shaidan` where `sd_id`='$sd_id'");
			$pic=null;$thumbs=null;
			if(isset($_POST['fileurl_tmp'])){
				if($shaidan['sd_photolist']==null){
					$img=$_POST['fileurl_tmp'];
					$num=count($img);
					for($i=0;$i<$num;$i++){
						$pic.=trim($img[$i]).";";
					}
					$src=trim($img[0]);
					$size=getimagesize(G_UPLOAD_PATH."/".$src);
					$width=220;
					$height=$size[1]*($width/$size[0]);
					$thumbs=tubimg($src,$width,$height);
				}else{
					$img=$_POST['fileurl_tmp'];
					$num=count($img);
					for($i=0;$i<$num;$i++){
						$pic.=$img[$i].";";
					}
				}
			}
			if($thumbs!=null){
				$sd_thumbs=$thumbs;
			}else{
				$sd_thumbs=$shaidan['sd_thumbs'];
			}
			$uid=$this->userinfo;
			$sd_userid=$uid['uid'];
			$sd_shopid=$shaidan['sd_shopid'];
			$sd_title=$_POST['title'];
			$sd_content=$_POST['content'];
			$sd_photolist=$pic.$shaidan['sd_photolist'];
			$sd_time=time();
			$this->db->Query("UPDATE `@#_shaidan` SET
			`sd_userid`='$sd_userid',
			`sd_shopid`='$sd_shopid',
			`sd_title`='$sd_title',
			`sd_thumbs`='$sd_thumbs',
			`sd_content`='$sd_content',
			`sd_photolist`='$sd_photolist',
			`sd_time`='$sd_time' where sd_id='$sd_id'");
			_message("晒单修改成功",WEB_PATH."/member/home/singlelist");
		}
		$member=$this->userinfo;
		$title="修改晒单";
		$uid=_getcookie('uid');
		$ushell=_getcookie('ushell');
		$sd_id=intval($this->segment(4));
		if($sd_id>0){
			$shaidan=$this->db->GetOne("select * from `@#_shaidan` where `sd_id`='$sd_id'");
			include templates("member","singleupdate");
		}else{
			_message("页面错误");
		}
	}
	public function singoldimg(){
		if($_POST['action']=='del'){
			$sd_id=$_POST['sd_id'];
			$sd_id = abs(intval($sd_id));
			$oldimg=$_POST['oldimg'];
			$shaidan=$this->db->GetOne("select * from `@#_shaidan` where `sd_id`='$sd_id'");
			$sd_photolist=str_replace($oldimg.";","",$shaidan['sd_photolist']);
			$this->db->Query("UPDATE `@#_shaidan` SET sd_photolist='".$sd_photolist."' where sd_id='".$sd_id."'");
		}
	}

	//晒单上传
	public function singphotoup(){

		if(!empty($_FILES)){
			/*
				更新时间：2014-04-28
				xu
			*/
			/*
			$uid=isset($_POST['uid']) ? $_POST['uid'] : NULL;
			$ushell=isset($_POST['ushell']) ? $_POST['ushell'] : NULL;
			$login=$this->checkuser($uid,$ushell);
			if(!$login){echo "上传失败";exit;}

			*/
			System::load_sys_class('upload','sys','no');
			upload::upload_config(array('png','jpg','jpeg','gif'),1000000000000,'shaidan');
			upload::go_upload($_FILES['Filedata']);
			if(!upload::$ok){
				echo _message(upload::$error,null,3);
			}else{
				$img=upload::$filedir."/".upload::$filename;
				$size=getimagesize(G_UPLOAD_PATH."/shaidan/".$img);
				$max=700;$w=$size[0];$h=$size[1];
				if($w>700){
					$w2=$max;
					$h2=$h*($max/$w);
					upload::thumbs($w2,$h2,1);
				}
				echo trim("shaidan/".$img);
			}
		}
	}
	public function singdel(){
		$action=isset($_GET['action']) ? $_GET['action'] : null;
		$filename=isset($_GET['filename']) ? $_GET['filename'] : null;
		if($action=='del' && !empty($filename)){
			$filename=G_UPLOAD_PATH.'shaidan/'.$filename;
			$size=getimagesize($filename);
			$filetype=explode('/',$size['mime']);
			if($filetype[0]!='image'){
				return false;
				exit;
			}
			unlink($filename);
			exit;
		}
	}
	//晒单删除
	public function shaidandel(){
		_message("已添加的晒单不可删除!");
		$member=$this->userinfo;
		//$id=isset($_GET['id']) ? $_GET['id'] : "";
		$id=$this->segment(4);
		$id=intval($id);
		$shaidan=$this->db->Getone("select * from `@#_shaidan` where `sd_userid`='$member[uid]' and `sd_id`='$id'");
		if($shaidan){
			$this->db->Query("DELETE FROM `@#_shaidan` WHERE `sd_userid`='$member[uid]' and `sd_id`='$id'");
			_message("删除成功",WEB_PATH."/member/home/singlelist");
		}else{
			_message("删除失败",WEB_PATH."/member/home/singlelist");
		}
	}
	//邀请好友
	public function invitefriends(){
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		$uid=_getcookie('uid');
		$notinvolvednum=0;  //未参加购买的人数
		$involvednum=0;     //参加预购的人数
		$involvedtotal=0;   //邀请人数


        //查询邀请好友信息
		$total=$involvedtotal=$mysql_model->GetCount("select * from `@#_member` where `yaoqing`='$member[uid]' ORDER BY `time` DESC");
		$page=System::load_sys_class('page');
		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}
		$page->config($involvedtotal,10,$pagenum,"0");
		$invifriends = $this->db->GetPage("select * from `@#_member` where `yaoqing`='$member[uid]' ORDER BY `time` DESC",array("num"=>10,"page"=>$pagenum,"type"=>1,"cache"=>0));
		
		for($i=0;$i<count($invifriends);$i++){
		   $sqluid=$invifriends[$i]['uid'];
		   $sqname=get_user_name($invifriends[$i]);
		   $invifriends[$i]['sqlname']=	 $sqname;

		   //查询邀请好友的消费明细
		   $accounts[$sqluid]=$mysql_model->GetList("select * from `@#_member_account` where `uid`='$sqluid'  ORDER BY `time` DESC");

		//判断哪个好友有消费
		 if(empty($accounts[$sqluid])){
		    $notinvolvednum +=1;
		    $records[$sqluid]='未参与购买';
		 }else{
		    $involvednum +=1;
		    $records[$sqluid]='已参与购买';
		 }
		}

		include templates("member","invitefriends");
	}

			//邀请好友
	public function invitefriends1(){
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		$uid=_getcookie('uid');
		$notinvolvednum=0;  //未参加购买的人数
		$involvednum=0;     //参加预购的人数
		$involvedtotal=0;   //邀请人数


        //查询邀请好友信息
		$invifriends=$mysql_model->GetList("select * from `@#_member` where `yaoqing`='$member[uid]' ORDER BY `time` DESC");
		$involvedtotal=count($invifriends);

		for($i=0;$i<count($invifriends);$i++){
		   $sqluid=$invifriends[$i]['uid'];
		   $sqname=get_user_name($invifriends[$i]);
		   $invifriends[$i]['sqlname']=	 $sqname;

		   //查询邀请好友的消费明细
		   $accounts[$sqluid]=$mysql_model->GetList("select * from `@#_member_account` where `uid`='$sqluid'  ORDER BY `time` DESC");


		//判断哪个好友有消费
		 if(empty($accounts[$sqluid])){
		    $notinvolvednum +=1;
		    $records[$sqluid]='未参与购买';
		 }else{
		    $involvednum +=1;
		    $records[$sqluid]='已参与购买';
		 }


		}

		include templates("member","invitefriends");
	}

		//佣金明细
	public function commissions(){
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		$uid = $member['uid'];
		$total=$mysql_model->GetCount("select * from `@#_member_account` where `uid`='$uid' and pay='佣金'");
		$page=System::load_sys_class('page');
		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}
		$page->config($total,10,$pagenum,"0");
		$recodearr = $this->db->GetPage("select * from `@#_member_account` where `uid`='$uid' and pay='佣金' ORDER BY `time` DESC",array("num"=>10,"page"=>$pagenum,"type"=>1,"cache"=>0));
		include templates("member","commissions");
	}

	//申请提现
	public function cashout1(){
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		$uid = $member['uid'];
		$total=0;
		$shourutotal=0;
		$zhichutotal=0;
		$cashoutdjtotal=0;
		$cashouthdtotal=0;
        //查询邀请好友id
    	$invifriends=$mysql_model->GetList("select * from `@#_member` where `yaoqing`='$member[uid]' ORDER BY `time` DESC");

		//查询佣金收入
		for($i=0;$i<count($invifriends);$i++){
			   $sqluid=$invifriends[$i]['uid'];
			   //查询邀请好友给我反馈的佣金
			   $recodes[$sqluid]=$mysql_model->GetList("select * from `@#_member_recodes` where `uid`='$sqluid' and `type`=1 ORDER BY `time` DESC");
		}

		//查询佣金消费(提现,充值)
		$zhichu=$mysql_model->GetList("select * from `@#_member_recodes` where `uid`='$uid' and `type`!=1 ORDER BY `time` DESC");

		//查询被冻结金额
		$cashoutdj=$mysql_model->GetOne("select SUM(money) as summoney  from `@#_member_cashout` where `uid`='$uid' and `auditstatus`!='1' ORDER BY `time` DESC");

		 if(!empty($recodes)){
			 foreach($recodes as $key=>$val){
			    foreach($val as $key2=>$val2){
					$shourutotal+=$val2['money'];	 //总佣金收入
				}
			 }
		 }
		 if(!empty($zhichu)){
			foreach($zhichu as $key=>$val3){
				$zhichutotal+=$val3['money'];	//总支出的佣金
			}
		 }


		$total=$shourutotal-$zhichutotal;  //计算佣金余额
		$cashoutdjtotal= $cashoutdj['summoney'];  //冻结佣金余额
		$cashouthdtotal= $total-$cashoutdj['summoney'];  //活动佣金余额


       if(isset($_POST['submit1'])){ //提现
		 $money      = abs(intval($_POST['money']));
		 $username   =htmlspecialchars($_POST['txtUserName']);
		 $bankname   =htmlspecialchars($_POST['txtBankName']);
		 $branch     =htmlspecialchars($_POST['txtSubBank']);
		 $banknumber =htmlspecialchars($_POST['txtBankNo']);
		 $linkphone  =htmlspecialchars($_POST['txtPhone']);
		 $time       =time();
		 $type       = -3;  //收取1/消费-1/充值-2/提现-3

		 if($total<100){
		     _message("佣金金额大于100元才能提现！");exit;
		 }elseif($cashouthdtotal<$money){
		    _message("输入额超出活动佣金金额！");exit;
		 }elseif($total<$money ){
		     _message("输入额超出总佣金金额！");exit;
		 }else{

		 //插入提现申请表  这里不用在佣金表中插入记录 等后台审核才插入
		 $this->db->Query("INSERT INTO `@#_member_cashout`(`uid`,`money`,`username`,`bankname`,`branch`,`banknumber`,`linkphone`,`time`)VALUES
			('$uid','$money','$username','$bankname','$branch','$banknumber','$linkphone','$time')");
			_message("申请成功！请等待审核！");
		 }
	   }

	   if(isset($_POST['submit2'])){//充值
		  $money      = abs(intval($_POST['txtCZMoney']));
		  $type       = 1;
		  $pay        ="佣金";
		  $time       =time();
		  $content    ="使用佣金充值到购买账户";

		 if($money <= 0 || $money > $total){
			  _message("佣金金额输入不正确！");exit;
		 }
		 if($cashouthdtotal<$money){
		    _message("输入额超出活动佣金金额！");exit;
         }

		  //插入记录
		 $account=$this->db->Query("INSERT INTO `@#_member_account`(`uid`,`type`,`pay`,`content`,`money`,`time`)VALUES
			('$uid','$type','$pay','$content','$money','$time')");

		 // 查询是否有该记录
		 if($account){
			 //修改剩余金额
			 $leavemoney=$member['money']+$money;
			 $mrecode=$this->db->Query("UPDATE `@#_member` SET `money`='$leavemoney' WHERE `uid`='$uid' ");
			 //在佣金表中插入记录
		     $recode=$this->db->Query("INSERT INTO `@#_member_recodes`(`uid`,`type`,`content`,`money`,`time`)VALUES
			('$uid','-2','$content','$money','$time')");
			_message("充值成功！");
		 }else{
		     _message("充值失败！");
		 }
	   }
		include templates("member","cashout");
	}

	public function cashout(){
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		$uid = $member['uid'];
		$member = $mysql_model->getOne("select * from `@#_member` where uid=$uid");
		$total=$member['yongjin'];
		
        if(isset($_POST['submit1'])){ //提现
		 $money      = abs(intval($_POST['money']));
		 $username   =htmlspecialchars($_POST['txtUserName']);
		 $bankname   =htmlspecialchars($_POST['txtBankName']);
		 $branch     =htmlspecialchars($_POST['txtSubBank']);
		 $banknumber =htmlspecialchars($_POST['txtBankNo']);
		 $linkphone  =htmlspecialchars($_POST['txtPhone']);
		 $time       =time();
		 $type       = -3;  //收取1/消费-1/充值-2/提现-3

		 if($total<100){
		     _message("金额大于100元才能提现！");exit;
		 }elseif($total<$money ){
		     _message("输入额超出余额！");exit;
		 }else{
			$this->db->Query("UPDATE `@#_member` SET `yongjin`=yongjin-'$money' WHERE (`uid`='{$uid}')");
			$this->db->Query("INSERT INTO `@#_member_cashout`(`uid`,`money`,`username`,`bankname`,`branch`,`banknumber`,`linkphone`,`time`)VALUES
			('$uid','$money','$username','$bankname','$branch','$banknumber','$linkphone','$time')");
			_message("申请成功！请等待审核！");
		 }
	   }
	   if(isset($_POST['submit2'])){//充值
		  $money      = abs(intval($_POST['txtCZMoney']));
		  $type       = -1;
		  $pay        ="佣金";
		  $time       =time();
		  $content    ="使用佣金充值到购买账户";

		 if($money <= 0 || $money > $total){
			  _message("佣金金额输入不正确！");exit;
		 }
			
		  //插入记录
		 $account=$this->db->Query("INSERT INTO `@#_member_account`(`uid`,`type`,`pay`,`content`,`money`,`time`)VALUES
			('$uid','$type','$pay','$content','$money','$time')");

		 // 查询是否有该记录
		 if($account){
			 //修改剩余金额
			 $this->db->Query("UPDATE `@#_member` SET `yongjin`=yongjin-'$money',`money`=`money`+$money WHERE (`uid`='{$uid}')");
			 
			 //在佣金表中插入记录
		    $account=$this->db->Query("INSERT INTO `@#_member_account`(`uid`,`type`,`pay`,`content`,`money`,`time`)VALUES
			('$uid','1','账户','$content','$money','$time')");
			_message("充值成功！");
		 }else{
		     _message("充值失败！");
		 }
	   }

		include templates("member","cashout");
	}

	//提现记录
	public function record(){
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		$uid = $member['uid'];
		$recount=0;
		$fufen = System::load_app_config("user_fufen",'','member');
		//查询提现记录
		//$recordarr=$mysql_model->GetList("select * from `@#_member_recodes` a left join `@#_member_cashout` b on a.cashoutid=b.id where a.`uid`='$uid' and a.`type`='-3' ORDER BY a.`time` DESC");		$recordarr=

		$recordarr=$mysql_model->GetList("select * from  `@#_member_cashout`  where `uid`='$uid' ORDER BY `time` DESC limit 0,30");

		if(!empty($recordarr)){
		  $recount=1;
		}
		include templates("member","record");
	}
	//qq绑定
	public function qqclock(){
		$member=$this->userinfo;
		include templates("member","qqclock");
	}

	public function jf_change_address(){
		if(!isset($_POST['orderid']) || !isset($_POST['address'])){exit;}
		$orderid = abs(intval($_POST['orderid']));
		$address = abs(intval($_POST['address']));
		if(!$address || !$orderid){
			echo "参数错误";
			exit;
		}
		$member=$this->userinfo;
		$uid = $member['uid'];
		$info = $this->db->GetOne("SELECT uid,status FROM `@#_member_go_jf_record` WHERE `id` = '$orderid' and `uid` = '$uid' limit 1");
		if(!$info){
			echo "参数错误";
			exit;
		}
		$q = $this->db->Query("UPDATE `@#_member_go_jf_record` SET `address` = '$address' WHERE `id` = '$orderid' and `uid` = '$uid' ");

		if($q){
			echo '1';
		}else{
			echo "修改失败";
		}
	}

	public function change_address(){
		if(!isset($_POST['orderid']) || !isset($_POST['address'])){exit;}
		$orderid = abs(intval($_POST['orderid']));
		$address = abs(intval($_POST['address']));
		if(!$address || !$orderid){
			echo "参数错误";
			exit;
		}
		$member=$this->userinfo;
		$uid = $member['uid'];
		$info = $this->db->GetOne("SELECT uid,status FROM `@#_member_go_record` WHERE `id` = '$orderid' and `uid` = '$uid' limit 1");
		if(!$info){
			echo "参数错误";
			exit;
		}
		$q = $this->db->Query("UPDATE `@#_member_go_record` SET `address` = '$address' WHERE `id` = '$orderid' and `uid` = '$uid' ");

		if($q){
			echo '1';
		}else{
			echo "修改失败";
		}
	}


	# 我的修改 福分签到
	public function qiandao() {
		# 签到时间限制（不能夸天哦。。）
		$time_start = '00:01';
		$time_stop= '23:59';
		
		$fufen_config = System::load_app_config("user_fufen",'','member');
		# 每日签到增加福分
		$time_score = $fufen_config['fufen_qiandao'] ? $fufen_config['fufen_qiandao'] : 50;

		# 连续签到的天数
		$time_day = $fufen_config['fufen_qiandaoloop'] ? $fufen_config['fufen_qiandaoloop'] : 30;
		# 连续签到增加的福分
		$s_start  = $fufen_config['fufen_qiandaostart'] ? $fufen_config['fufen_qiandaostart'] : 1000;
		$s_end  = $fufen_config['fufen_qiandaoend'] ? $fufen_config['fufen_qiandaoend'] : 1000;
		$time_day_score = rand($s_start,$s_end);

		if ( $this->segment(4) == 'mobile' ) {
			function x__message($a,$b=null,$c=2) {
				_messagemobile($a,$b,$c);
			}
		} else {
			function x__message($a,$b=null,$c=2) {
				_message($a,$b,$c);
			}
		}



		$member=$this->userinfo;
		if ( isset($_REQUEST['submit']) || $this->segment(5)=='submit' ) {
			if ( !$member['mobile'] || $member['mobilecode']!='1' ) {
				x__message("非常抱歉只有手机验证会员才能签到喔",WEB_PATH."/member/home/qiandao/".$this->segment(4));

			}else if ( $member['sign_in_date'] == date('Y-m-d') ) {
				x__message("您今天已经过签到了。",WEB_PATH."/member/home/qiandao/".$this->segment(4));

			}else if ( strtotime(date('Y-m-d').$time_start ) > time() || strtotime(date('Y-m-d').$time_stop ) < time() ) {
				x__message("现在不是签到时间！签到时间为{$time_start}点到{$time_stop}点",WEB_PATH."/member/home/qiandao/".$this->segment(4));

			} else {
				$mysql_model = System::load_sys_class('model');
				if ( $member['sign_in_date'] == date('Y-m-d',strtotime('-1 day')) ){
					# 连续签到

					if ( $member['sign_in_time'] >= $time_day ) {
						$member['sign_in_time'] = 0;
					}

					$sign_in_time = $member['sign_in_time'] + 1;
					$sign_in_time_all = $member['sign_in_time_all'] + 1;
					$sign_in_date = date('Y-m-d');
					$score = $member['score'] + $time_score;

					if ( $sign_in_time >= $time_day ) {
						# 领取大礼包了
						$score += $time_day_score;
						$big = true;
					} else {
						$big = false;
					}

					$mysql_model->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('".$member['uid']."', '1', '福分', '每日签到', '$time_score', '".time()."')");
					$mysql_model->Query("UPDATE `@#_member` SET score='".$score."',sign_in_time='".$sign_in_time."', sign_in_time_all='".$sign_in_time_all."', sign_in_date='".$sign_in_date."' where uid='".$member['uid']."'");
					if ( $big ) {
						$mysql_model->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('".$member['uid']."', '1', '福分', '签到大礼包', '$time_day_score', '".time()."')");
						x__message("签到成功，成功领取{$time_score}福分。<br />恭喜您获得{$time_day_score}福分的大礼包。<br />您的当前福分为{$score}",WEB_PATH."/member/home/qiandao/".$this->segment(4),10);
					} else {
						x__message("签到成功，成功领取{$time_score}福分。<br />您的当前福分为{$score}。<br />再连续签到".($time_day-$sign_in_time)."天就能领取大礼包啦，加油！！！",WEB_PATH."/member/home/qiandao/".$this->segment(4));
					}

				} else {
					$sign_in_time = 1;
					$sign_in_time_all = $member['sign_in_time_all'] + 1;
					$sign_in_date = date('Y-m-d');
					$score = $member['score'] + $time_score;
					$mysql_model->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('".$member['uid']."', '1', '福分', '每日签到', '$time_score', '".time()."')");
					$mysql_model->Query("UPDATE `@#_member` SET score='".$score."',sign_in_time='".$sign_in_time."', sign_in_time_all='".$sign_in_time_all."', sign_in_date='".$sign_in_date."' where uid='".$member['uid']."'");
					x__message("签到成功，成功领取{$time_score}福分。<br />您的当前福分为{$score}",WEB_PATH."/member/home/qiandao/".$this->segment(4));
				}
			}
			die;
		}

		if ( !$member['sign_in_date'] ) {
			$member['sign_in_date'] = '-';

		}else if ( $member['sign_in_date'] != date('Y-m-d') && $member['sign_in_date'] != date('Y-m-d',strtotime('-1 day')) ) {

			$member['sign_in_time'] = 0;
		}
		if ( $this->segment(4) == 'mobile' ) {
			include templates("mobile/user","qiandao");
		} else {
			include templates("member","qiandao");
		}
	}
	
	public function orderDetail(){
		$huiyuan=$this->userinfo;
		$crodid=intval($this->segment(4));
		$iii=$this->segment(4);
		$records=$this->db->GetOne("select * from `@#_member_go_record` where `id`='$crodid' and `uid`='$huiyuan[uid]' LIMIT 1");
		if($records['status']=='已付款,未发货,未完成,未提交地址'){
			$fhid = 0;
		}
		elseif($records['status']=='已付款,未发货,未完成,已提交地址'){
			$fhid = 1;
		}
		elseif($records['status']=='已付款,已发货,待收货'){
			$fhid = 2;
		}
		elseif($records['status']=='已付款,已发货,已完成'){
			$fhid = 3;
		}
		elseif($records['status']=='已付款,已发货,已作废'){
			$fhid = 4;
		}
		$status=@explode(",",$records['status']);
		$ii=$this->segment(4);
		$uid = $huiyuan['uid'];
		$biaoti="获得的商品 - "._cfg("web_name");
		$huiyuan=$this->userinfo;
		$biaoti="收货地址";
		$huiyuan_dizhi=$this->db->GetList("select * from `@#_member_dizhi` where uid='".$huiyuan['uid']."'  limit 1");
		$dizhi_sta=$this->db->GetOne("select * from `@#_member_go_record` where id='$iii' limit 1");
		foreach($huiyuan_dizhi as $k=>$v){
			$huiyuan_dizhi[$k] = $v;
		}
		$count=count($huiyuan_dizhi);
		$zongji=$this->db->GetCount("select * from `@#_member_go_record` where `uid`='$uid' and `huode`>'10000000'");
		$fenye=System::load_sys_class('page');
		if(isset($_GET['p'])){
			$fenyenum=$_GET['p'];
		}else{
			$fenyenum=1;
		}
		$fenye->config($zongji,10,$fenyenum,"0");
		$record = $this->db->GetPage("select * from `@#_member_go_record` where `uid`='$uid' and `huode`>'10000000' ORDER BY id DESC",array("num"=>10,"page"=>$fenyenum,"type"=>1,"cache"=>0));
		foreach($record as $ckey=>$cord){
			// $jiexiao = huode_shop_if_jiexiao($cord['shopid']);
			if(!$jiexiao){
				unset($record[$ckey]);
			}
		}
		include templates("member","orderDetail");
	}
	
	public function orderDetailsb(){
		$mysql_model=System::load_sys_class('model');
		$huiyuan=$this->userinfo;
		$uid=$huiyuan['uid'];
		$iii=$this->segment(4);
		$jj=isset($_POST['selectAddrID']) ? $_POST['selectAddrID'] : "";
		$huiyuan_dizhisss=$mysql_model->GetOne("select * from `@#_member_dizhi` where `id`='$jj'");
		$recordmmm=$mysql_model->GetOne("select * from `@#_member_go_record` where `id`='$iii' and `uid`='$huiyuan[uid]' LIMIT 1");
		$recordmmmqq=$mysql_model->GetOne("select * from `@#_shoplist` where `id`='$recordmmm[shopid]' LIMIT 1");
		$rsrs1=$mysql_model->GetOne("select * from `@#_config` where `name`='web_key1'");
		$rsrs2=$mysql_model->GetOne("select * from `@#_config` where `name`='web_key2'");
		if(isset($_POST['btnSubmitCart']))
		{
			foreach($_POST as $k=>$v)
			{
				$_POST[$k] = ($v);
			}
			$sheng111=isset($_POST['sheng']) ? $_POST['sheng'] : "";
			if(!empty($sheng111))
			{
				$sheng=isset($_POST['sheng']) ? $_POST['sheng'] : "";
				$shi=isset($_POST['shi']) ? $_POST['shi'] : "";
				$xian=isset($_POST['xian']) ? $_POST['xian'] : "";
				$jiedao=isset($_POST['jiedao']) ? $_POST['jiedao'] : "";
				$youbian=isset($_POST['youbian']) ? $_POST['youbian'] : "";
				$shouhuoren=isset($_POST['shouhuoren']) ? $_POST['shouhuoren'] : "";
				$tell=isset($_POST['tell']) ? $_POST['tell'] : "";
				$mobile=isset($_POST['mobile']) ? $_POST['mobile'] : "";
				$email=isset($_POST['email']) ? $_POST['email'] : "";
			}
			else
			{
				$sheng=$huiyuan_dizhisss['sheng'];
				$shi=$huiyuan_dizhisss['shi'];
				$xian=$huiyuan_dizhisss['xian'];
				$jiedao=$huiyuan_dizhisss['jiedao'];
				$youbian=$huiyuan_dizhisss['youbian'];
				$shouhuoren=$huiyuan_dizhisss['shouhuoren'];
				$tell=$huiyuan_dizhisss['tell'];
				$mobile=$huiyuan_dizhisss['mobile'];
				$email=$huiyuan_dizhisss['email'];
			}
			$qq=isset($_POST['qq']) ? $_POST['qq'] : "";
			$shipTime=isset($_POST['shipTime']) ? $_POST['shipTime'] : "";
			$shipRemark=isset($_POST['shipRemark']) ? $_POST['shipRemark'] : "";
			$kaka=isset($_POST['kaka']) ? $_POST['kaka'] : "";
			$time=time();
			if($kaka==1)
			{
				if(empty($qq))
				{
					_message("直冲类商品QQ不能为空",WEB_PATH."/member/home/orderlist",3);
					exit;
				}
				elseif (strlen($qq)<5||!is_numeric($qq))
				{
					_message("QQ号码无效",WEB_PATH."/member/home/orderDetail/$recordmmm[id]",3);
					exit;
				}
				$weerid = $rsrs1['value'];
				$weerpws = strtolower(md5($rsrs2['value']));
				$cardid = "220612";
				$cardnum = $recordmmmqq['yuanjia'];
				$sporder_id = $time;
				$sporder_time = $time;
				$game_userid = $qq;
				$game_userpsw = "";
				$game_area = "";
				$game_srv = "";
				$ret_url = "http://xxxx";
				$version = "6.0";
				$keystr = "OFCARD";
				$md5_str_param = $weerid.$weerpws.$cardid.$cardnum.$sporder_id.$sporder_time.$game_userid.$game_area.$game_srv.$keystr;
				$md5_str = strtoupper(md5($md5_str_param));
				if(!empty($game_area) or !empty($game_srv)) 
				{
					$game_area = urlencode($game_area);
					$game_srv = urlencode($game_srv);
				}
				$url = "http://api2.ofpay.com/onlineorder.do?userid=".$weerid."&userpws=".$weerpws."&cardid=".$cardid."&cardnum=".$cardnum."&game_area=".$game_area."&game_srv=".$game_srv ."&sporder_id=".$sporder_id."&sporder_time=".$sporder_time."&game_userid=".$game_userid."&md5_str=".$md5_str."&version=".$version."&ret_url=".$ret_url;
				$ch = curl_init();
				curl_setopt ($ch, CURLOPT_URL, $url);
				curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT,10);
				$contents = curl_exec($ch);
				$res = simplexml_load_string($contents);
				$retcode = $res->retcode;
				$err_msg = $res->err_msg;
				if($retcode=="1")
				{
					$orderid = $res->orderid;
					$cardid = $res->cardid;
					$cardnum = $res->cardnum;
					$ordercash = $res->ordercash;
					$cardname = $res->cardname;
					$sporder_id = $res->sporder_id;
					$game_area = $res->game_area;
					$game_srv = $res->game_srv;
					$game_userid = $res->game_userid;
					$game_state = $res->game_state;
				}
			}
			$huiyuan_dizhi=$mysql_model->GetOne("select * from `@#_member_dizhi` where `uid`='".$huiyuan['uid']."'");
			if(!$huiyuan_dizhi)
			{
				$default="Y";
			}
			else
			{
				$default="N";
			}
			$shopinfoss=$mysql_model->GetOne("select * from `@#_shoplist` where `id`='$recordmmm[shopid]' LIMIT 1");
			if($recordmmm['leixing']==0) 
			{
				$status='已付款,未发货,未完成,已提交地址';
			}
			else
			{
				$status='已付款,已发货,已完成';
			}
			$mysql_model->Query("UPDATE `@#_member_go_record` SET shouhuo='1',status='$status',qq='$qq',youbian='$youbian',shipRemark='$shipRemark',shipTime='$shipTime',email='$email',tell='$tell',shouhuoren='$shouhuoren',mobile='$mobile',sheng='$sheng',shi='$shi',xian='$xian',jiedao='$jiedao',fhtime='$time',wei='0' where id='".$iii."'");
			$mysql_model->Query("INSERT INTO `@#_member_dizhi`(`uid`,`sheng`,`shi`,`xian`,`jiedao`,`youbian`,`shouhuoren`,`tell`,`mobile`,`qq`,`default`,`time`)VALUES
			('$uid','$sheng','$shi','$xian','$jiedao','$youbian','$shouhuoren','$tell','$mobile','$qq','$default','$time')");
			_message("添加成功",WEB_PATH."/member/home/orderDetail/$recordmmm[id]",3);
		}
	}
	public function excorderdetail(){
		$huiyuan=$this->userinfo;
		$crodid=intval($this->segment(4));
		$iii=$this->segment(4);
		$records=$this->db->GetOne("select * from `@#_member_go_record` where `id`='$crodid' and `uid`='$huiyuan[uid]' LIMIT 1");
		if($records['status']=='已付款,未发货,未完成,未提交地址')
		{
			$fhid = 0;
		}
		elseif($records['status']=='已付款,未发货,未完成,已提交地址')
		{
			$fhid = 1;
		}
		elseif($records['status']=='已付款,已发货,待收货')
		{
			$fhid = 2;
		}
		elseif($records['status']=='已付款,已发货,已完成')
		{
			$fhid = 3;
		}
		elseif($records['status']=='已付款,已发货,已作废')
		{
			$fhid = 4;
		}
		$status=@explode(",",$records['status']);
		$ii=$this->segment(4);
		include templates("member","excorderdetail");
	}
	public function excorderdetailsb(){
		$mysql_model=System::load_sys_class('model');
		$huiyuan=$this->userinfo;
		$uid=$huiyuan['uid'];
		$iii=$this->segment(4);
		$jj=isset($_POST['selectAddrID']) ? $_POST['selectAddrID'] : "";
		$huiyuan_dizhisss=$mysql_model->GetOne("select * from `@#_member_dizhi` where `id`='$jj'");
		$recordmmm=$mysql_model->GetOne("select * from `@#_member_go_record` where `id`='$iii' and `uid`='$huiyuan[uid]' LIMIT 1");
		if(isset($_POST['btnSubmitCart']))
		{
			foreach($_POST as $k=>$v)
			{
				$_POST[$k] = ($v);
			}
			$kaka=isset($_POST['kaka']) ? $_POST['kaka'] : "";
			$time=time();
			$shopinfoss=$mysql_model->GetOne("select * from `@#_shoplist` where `id`='$recordmmm[shopid]' LIMIT 1");
			if(empty($shopinfoss['yuanjia']))
			{
				_message("未设置转换原价",WEB_PATH."/member/home/excorderdetail/$recordmmm[id]",3);
				exit;
			}
			$bili = System::load_app_config("user_fufen",'','member');
			$recordmmm['leixing']=0;
			if($recordmmm['leixing']==0)
			{
				$jia=$shopinfoss['yuanjia']*$bili["fufen_yongjinqd0"];
			}
			else if ($recordmmm['leixing']==1)
			{
				$jia=$shopinfoss['yuanjia']*$bili["fufen_yongjinqd1"];
			}
			else
			{
				$jia=$shopinfoss['yuanjia']*$bili["fufen_yongjinqd2"];
			}
			$pay_zhifu_name = '积分';
			if($recordmmm['wei']==1)
			{
				_message("您已经转换过哦",WEB_PATH."/member/home/excorderdetail/$recordmmm[id]",3);
				exit;
			}
			$mysql_model->Query("UPDATE `@#_member` SET money1=money1+'".$jia."' where uid='".$huiyuan['uid']."'");
			$mysql_model->Query("UPDATE `@#_member_go_record` SET status='已付款,已发货,已完成',wei='1' where `id`='$iii' and `uid`='$huiyuan[uid]'");
			$mysql_model->Query("INSERT INTO `@#_member_account1` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '$pay_zhifu_name', '众购商品id(".$shopinfoss['id'].")转换获得积分', '".$jia."', '$time')");
			_message("兑换成功",WEB_PATH."/member/home/integralmsg",3);
		}
	}
	
	public function integralmsg()
	{
		$huiyuan=$this->userinfo;
		$uid = $huiyuan['uid'];
		$biaoti="账户记录 - "._cfg("web_name");
		$zongji=$this->db->GetCount("select * from `@#_member_account1` where `uid`='$uid' and `pay` = '积分'");
		$fenye=System::load_sys_class('page');
		if(isset($_GET['p']))
		{
			$fenyenum=$_GET['p'];
		}
		else
		{
			$fenyenum=1;
		}
		$fenye->config($zongji,20,$fenyenum,"0");
		$account = $this->db->GetPage("select * from `@#_member_account1` where `uid`='$uid' and `pay` = '积分' ORDER BY time DESC",array("num"=>20,"page"=>$fenyenum,"type"=>1,"cache"=>0));
		include templates("member","integralmsg");
	}
	public function cashierSignDeposit()
	{
		$mysql_model=System::load_sys_class('model');
		$huiyuan=$this->userinfo;
		$uid = $huiyuan['uid'];
		$time=time();
		$biaoti="充值到余额";
		$amount=isset($_POST['amount']) ? $_POST['amount'] : "";
		$pay_zhifu_name = '积分';
		if(isset($_POST['amount']))
		{
			if (floor($amount)!=$amount) 
			{
				_message("不能是小数",WEB_PATH."/member/home/cashierSignDeposit",3);
				exit;
			}
			if($amount>$huiyuan['money1']||$huiyuan['money1']<=0) 
			{
				_message("积分不足",WEB_PATH."/member/home/cashierSignDeposit",3);
				exit;
			}
			$mysql_model->Query("UPDATE `@#_member` SET money1=money1-'".$amount."',money=money+'".$amount."' where uid='".$huiyuan['uid']."'");
			$mysql_model->Query("INSERT INTO `@#_member_account1` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '-1', '$pay_zhifu_name', '积分转账到余额', '".$amount."', '$time')");
			$mysql_model->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '帐户', '积分转账到余额', '".$amount."', '$time')");
			_message("充值到余额成功",WEB_PATH."/member/home/cashierSignDeposit",3);
		}
		include templates("member","cashierSignDeposit");
	}
	public function withdrawRecord()
	{
		$mysql_model=System::load_sys_class('model');
		$huiyuan=$this->userinfo;
		$uid = $huiyuan['uid'];
		$recount=0;
		$fufen = System::load_app_config("user_fufen",'','member');
		$jfwitem=$mysql_model->GetList("select * from  `@#_member_cashout1`  where `uid`='$uid' ORDER BY `time` DESC limit 0,30");
		if(!empty($recordarr))
		{
			$recount=1;
		}
		include templates("member","withdrawRecord");
	}
	
	public function mentionnow(){
		$mysql_model = System::load_sys_class('model');
		$huiyuan = $this->userinfo;
		$uid = $huiyuan['uid'];
		$iii = $this->segment(4);
		$jj = isset($_POST['selectAddrID']) ? $_POST['selectAddrID'] : "";
		$recordmmm = $mysql_model->GetOne("select * from `@#_member_go_record` where `id`='$iii' and `uid`='$huiyuan[uid]' LIMIT 1");
		if (isset($_POST['J_submit'])) {
			foreach ($_POST as $k => $v) {
				$_POST[$k] = ($v);
				
			}
			$amount = isset($_POST['amount']) ? $_POST['amount'] : "";
			$alipayname = isset($_POST['alipayname']) ? $_POST['alipayname'] : "";
			$alipayusername = isset($_POST['alipayusername']) ? $_POST['alipayusername'] : "";
			$time = time();
			$shopinfoss = $mysql_model->GetOne("select * from `@#_shoplist` where `id`='$recordmmm[shopid]' LIMIT 1");
			if ($amount < 100) {
				_message("积分提现不能小于100", WEB_PATH . "/member/home/cashierSignDeposit", 3);
				exit;
			}
			if ($amount > $huiyuan['money1'] || !is_numeric($amount)) {
				_message("积分无效,不能提现", WEB_PATH . "/member/home/cashierSignDeposit", 3);
				exit;
			}
			$amountsss = $amount * 0.01;
			if ($amountsss < 10) {
				$shouxufei = $amount * 0.01;
			} else {
				$shouxufei = '10';
			}
			$hhhh = $amount + $shouxufei;
			if ($hhhh > $huiyuan['money1']) {
				_message("积分不足,不能提现", WEB_PATH . "/member/home/cashierSignDeposit", 3);
				exit;
			}
			$mysql_model->Query("UPDATE `@#_member` SET money1=money1-'" . $amount . "'-'" . $shouxufei . "' where uid='" . $huiyuan['uid'] . "'");
			$mysql_model->Query("INSERT INTO `@#_member_account1` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '-1', '积分', '积分提现到支付宝', '".$amount."', '$time')");
			$this->db->Query("INSERT INTO `@#_member_cashout1`(`uid`,`money`,`username`,`bankname`,`branch`,`banknumber`,`linkphone`,`time`,`shenhe`)VALUES
			('$uid','$amount','$alipayname','$alipayusername','$branch','$banknumber','$linkphone','$time','0')");
			_message("提现申请成功,请等待审核", WEB_PATH . "/member/home/cashierSignDeposit", 3);
		}
	}
	
	public function promlist(){
		$mysql_model=System::load_sys_class('model');
		$uid = $this->segment(4);
		$member=$this->db->Getone("select * from `@#_member` where `uid`='$uid' limit 1");
		if (!$member) {
			_message("会员不存在");
			exit;
		}
		$username = get_user_name($member);
		$notinvolvednum=0;  //未参加购买的人数
		$involvednum=0;     //参加预购的人数
		$involvedtotal=0;   //邀请人数

        //查询邀请好友信息
		$invifriends=$mysql_model->GetList("select * from `@#_member` where `yaoqing`='$member[uid]' ORDER BY `time` DESC");
		$involvedtotal=count($invifriends);

		for($i=0;$i<count($invifriends);$i++){
		   $sqluid=$invifriends[$i]['uid'];
		   $sqname=get_user_name($invifriends[$i]);
		   $invifriends[$i]['sqlname']=	 $sqname;

		   //查询邀请好友的消费明细
		   $accounts[$sqluid]=$mysql_model->GetList("select * from `@#_member_account` where `uid`='$sqluid'  ORDER BY `time` DESC");


		//判断哪个好友有消费
		 if(empty($accounts[$sqluid])){
		    $notinvolvednum +=1;
		    $records[$sqluid]='未参与购买';
		 }else{
		    $involvednum +=1;
		    $records[$sqluid]='已参与购买';
		 }

		}

		include templates("member","inviteprom");
	}
}

?>