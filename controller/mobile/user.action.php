<?php
defined('G_IN_SYSTEM')or exit('No permission resources.');
System::load_app_class('memberbase',null,'no');
System::load_app_fun('user','go');
System::load_app_fun('my','go');
System::load_sys_fun('send');
class user extends memberbase {
	public function __construct(){
		parent::__construct();
		$this->db = System::load_sys_class("model");
	}
//返回注册页面
	public function findpassword(){
	  	$webname=$this->_cfg['web_name'];
		include templates("mobile/user","findpassword");
	}
	public function cook_end(){
		_setcookie("uid","",time()-3600);
		_setcookie("ushell","",time()-3600);
		//_message("退出成功",WEB_PATH."/mobile/mobile/");
		header("location: ".WEB_PATH."/mobile/mobile/");
	}
	//返回登录页面
	public function login(){
		$webname=$this->_cfg['web_name'];
		$user = $this->userinfo;
		if($user){
			header("Location:".WEB_PATH."/mobile/home/");exit;
		}
		include templates("mobile/user","login");
	}
	//返回注册页面
	public function register(){
		$p_c = $this->segment(4);
		if(!empty($p_c)){
				setcookie("regcode",$p_c,time()+3600*24,'/');
		}
		$webname=$this->_cfg['web_name'];
		include templates("mobile/user","register");
	}
	//返回发送验证码页面
	public function mobilecode(){
	    $webname=$this->_cfg['web_name'];
	    $mobilename=$this->segment(4);
		include templates("mobile/user","mobilecheck");
	}
	public function mobilecheck(){
	    $webname=$this->_cfg['web_name'];
		$title="验证手机";
		$time=3000;
		$name=$this->segment(4);
		$member=$this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$name' LIMIT 1");
		 //var_dump($member);exit;
		if(!$member)_messagemobile("参数不正确!");
		if($member['mobilecode']==1){
			_messagemobile("该账号验证成功",WEB_PATH."/mobile/mobile");
		}
		if($member['mobilecode']==-1){
			$sendok = send_mobile_reg_code($name,$member['uid']);
			if($sendok[0]!=1){
				_messagemobile($sendok[1]);
			}
			header("location:".WEB_PATH."/mobile/user/mobilecheck/".$member['mobile']);
			exit;
		}
		$enname=substr($name,0,3).'****'.substr($name,7,10);
		$time=120;
		include templates("mobile/user","mobilecheck");
	}
	public function buyDetail(){
		$webname=$this->_cfg['web_name'];
		$member=$this->userinfo;
		$itemid=intval($this->segment(4));
		$itemlist=$this->db->GetList("select *,a.time as timego,sum(gonumber) as gonumber from `@#_member_go_record` a left join `@#_shoplist` b on a.shopid=b.id where a.uid='$member[uid]' and b.id='$itemid' group by a.id order by a.time" );
		if(!empty($itemlist)){
			if($itemlist[0]['q_end_time']!='' && $itemlist[0]['q_showtime']=='N'){
				//商品已揭晓
				$itemlist[0]['codeState']='已揭晓...';
				$itemlist[0]['class']='z-ImgbgC02';
			}elseif($itemlist[0]['shenyurenshu']==0){
				//商品购买次数已满
				$itemlist[0]['codeState']='已满员...';
				$itemlist[0]['class']='z-ImgbgC01';
			}else{
				//进行中
				$itemlist[0]['codeState']='进行中...';
				$itemlist[0]['class']='z-ImgbgC01';
			}
			$bl=($itemlist[0]['canyurenshu']/$itemlist[0]['zongrenshu'])*100;
			foreach ($itemlist as $k => $v) {
				$count += $v['gonumber'];
			}
		}
		$count = $count ? $count : 0;
		include templates("mobile/user","userbuyDetail");
	}
function qqlogin() {
        include_once dirname(__FILE__) . '/../api/lib/qq/qqConnectAPI.php';
        $qc = new QC();
        $db = System::load_sys_class("model");
        $qc->qq_login(WEB_PATH . '/mobile/user/qqlogin_callback/');
    }
    function qqlogin_callback() {
        include_once dirname(__FILE__) . '/../api/lib/qq/qqConnectAPI.php';
        $db = System::load_sys_class("model");
        $qc = new QC();
        $qq_asc = $qc->qq_callback();
        $qq_openid = $qc->get_openid();
        $qc = new QC($qq_asc, $qq_openid);
        if (empty($qq_openid)) {
            header("Location: http://" . $_SERVER['HTTP_HOST']);
            exit;
        }
        $data = $db->GetOne("select * from `@#_member_band` where `b_code` = '$qq_openid' and `b_type` = 'qq' LIMIT 1");
        if ($data) {
            $member = $this->db->GetOne("select * from `@#_member` where uid='$data[b_uid]'");
            if ($member) {
                _setcookie("uid", _encrypt($member['uid']), 60 * 60 * 24 * 7);
                _setcookie("ushell", _encrypt(md5($member['uid'] . $member['password'] . $member['mobile'] . $member['email'])), 60 * 60 * 24 * 7);
                header("location:" . WEB_PATH . "/mobile/home");
                die;
            }
        }
        _message("初次使用QQ登陆，需绑定您的帐号，请使用手机号或邮箱进行登录，如果您还没有账户请先进行注册。");
    }
    public function choujiang(){
        $LotteryList = $this->db->Getlist("SELECT * FROM `@#_activity_lottery` ORDER BY id DESC LIMIT 100");
        include templates("mobile/user", "choujiang");
    }
	
		public function profilechange(){
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		if($_POST){			
			$username=_htmtocode(trim($_POST['username']));
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
			$isset_user=$this->db->GetOne("select `uid` from `@#_member_account` where (`content`='手机认证完善奖励' or `content`='完善昵称奖励') and `type`='1' and `uid`='$member[uid]' and (`pay`='经验' or `pay`='福分')");	
			if(!$isset_user){			
				$config = System::load_app_config("user_fufen","","member");//福分/经验
				$time=time();
				$this->db->Query("insert into `@#_member_account` (`uid`,`type`,`pay`,`content`,`money`,`time`) values ('$member[uid]','1','福分','完善昵称奖励','$config[f_overziliao]','$time')");
				$this->db->Query("insert into `@#_member_account` (`uid`,`type`,`pay`,`content`,`money`,`time`) values ('$member[uid]','1','经验','完善昵称奖励','$config[z_overziliao]','$time')");			
				$mysql_model->Query("UPDATE `@#_member` SET username='".$username."',qianming='".$qianming."',`score`=`score`+'$config[f_overziliao]',`jingyan`=`jingyan`+'$config[z_overziliao]' where uid='".$member['uid']."'");
			}	
			$mysql_model->Query("UPDATE `@#_member` SET username='".$username."',qianming='".$qianming."' where uid='".$member['uid']."'");
			echo 1;
			die;
			
		}
		
	}

	public function profile(){
		$member=$this->userinfo;
		$uid = $member['uid'];
		$wxinfo = $this->db->GetOne("SELECT * FROM `@#_member_band` WHERE `b_uid` = '$uid' AND `b_type`='weixin' LIMIT 1");
		$qqinfo = $this->db->GetOne("SELECT * FROM `@#_member_band` WHERE `b_uid` = '$uid' AND `b_type`='qq' LIMIT 1");
		include templates("mobile/user","profile");
	}

	public function password(){
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		$title="密码修改";	
		include templates("mobile/user","password");
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
			$pwd1=isset($_POST['pwd1']) ? $_POST['pwd1'] : "";
			$pwd2=isset($_POST['pwd2']) ? $_POST['pwd2'] : "";
			$pwd3=isset($_POST['pwd3']) ? $_POST['pwd3'] : "";
			if($pwd3==null or $pwd2==null or $pwd1==null){
					echo "密码不能为空;";
					exit;
			}
			
			if(strlen($_POST['pwd2'])<6 || strlen($_POST['pwd2'])>20){
				echo "密码不能小于6位或者大于20位";
				exit;
			}
			if($_POST['pwd3']!==$_POST['pwd2']){
				echo "二次密码不一致";
				exit;
			}		
			$pwd2=md5($pwd2);
			$pwd1=md5($pwd1);
			if($member['password']!=$pwd1){
				echo "原密码错误";
			}else{
				$mysql_model->Query("UPDATE `@#_member` SET password='".$pwd2."' where uid='".$member['uid']."'");
				echo 'ok';
			}
		}
	
	
public function wxlogin(){
		session_start();
		$this->db=System::load_sys_class('model');
		$wx_set =$this->db->GetOne("SELECT * from `@#_wxset` ");
       	$state  = md5(uniqid(rand(), TRUE));
       	$_SESSION["wxState"] = $state;
		$decode=_encrypt($_COOKIE['regcode'],"DECODE");
		$redirect_uri = urlencode("".$wx_set['mback']."/?/mobile/user/wx_callback/".$decode."/");
		$wxurl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$wx_set['mappid']."&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=$state#wechat_redirect";
	   	header("Location: $wxurl");
	}
    public function wx_callback(){
        session_start();
        $this->db=System::load_sys_class('model');
		$wx_set =$this->db->GetOne("SELECT * from `@#_wxset` ");
	    $code = $_GET["code"];
	    $response = file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$wx_set['mappid']."&secret=".$wx_set['msecret']."&code=$code&grant_type=authorization_code");
	    $jsondecode = json_decode($response,true);
        $wx_openid =$jsondecode["openid"];
		if(!wx_openid){
			_messagemobile("登陆失败,请返回重试!。");
		}
		$access_token =$jsondecode["access_token"];
		$response= file_get_contents("https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$wx_openid");
		$jsondecode = json_decode($response,true);
		$nickname = $jsondecode["nickname"];
		$unionid =$jsondecode["unionid"];
		$touimg = $jsondecode["headimgurl"];
		$go_user_info = $this->db->GetOne("select * from `@#_member_band` where `b_code` = '$wx_openid' and `b_type` = 'weixin' LIMIT 1");
		$decode=_encrypt($_COOKIE['regcode'],"DECODE");
		$decode = intval($decode);
 		if(!$go_user_info){
		    $userpass = md5("123456");
			$path = 'data/uploads/wximg/';
			$mulu = time();
			if($touimg){
				$post_arr= array("img"=>$touimg,"path"=>$path,"mulu"=>$mulu);
				_g_triggerRequest(WEB_PATH.'/mobile/user/wximg',false,$post_arr);
			}
			$go_user_img  = 'wximg/'.$mulu . '.jpg';
			$openid=$wx_openid;
			$go_user_time = time();
		    $q1 = $this->db->Query("INSERT INTO `@#_member` (`username`,`openid`,`password`,`img`,`money`,`band`,`yaoqing`,`time`) VALUES ('$nickname','$openid','$userpass','$go_user_img',0,'weixin','$decode','$go_user_time')");
		    $uid = $this->db->insert_id();
		    $go_user_time = time();
			$this->db->Query("INSERT INTO `@#_member_band` (`b_uid`, `b_type`, `b_code`, `b_time`) VALUES ('$uid', 'weixin', '$wx_openid', '$go_user_time')");
			$member = $this->db->GetOne("select uid,password,mobile,email from `@#_member` where `uid` = '$uid' LIMIT 1");
		    $se1 = _setcookie("uid",_encrypt($member['uid']),60*60*24*7);
		    $se2 = _setcookie("ushell",_encrypt(md5($member['uid'].$member['password'].$member['mobile'].$member['email'])),60*60*24*7);
		   	$callback_url =  WEB_PATH."/mobile/home/mobilebind";
			//$callback_url =  WEB_PATH."/mobile/home/";
			header("Location:$callback_url");
		 }else{
		    $uid = $go_user_info["b_uid"];
			$member = $this->db->GetOne("select uid,password,mobile,email from `@#_member` where `uid` = '$uid' LIMIT 1");
			$se1 = _setcookie("uid",_encrypt($member['uid']),60*60*24*7);
		    $se2 = _setcookie("ushell",_encrypt(md5($member['uid'].$member['password'].$member['mobile'].$member['email'])),60*60*24*7);
			if(!$member['mobile']){
			  	// _message("登录成功，请绑定邮箱或手机号和及时修改默认密码!",$callback_url);
			    $callback_url =  WEB_PATH."/mobile/home/mobilebind";
			//$callback_url =  WEB_PATH."/mobile/home/";
			    header("Location:$callback_url");
           }else{
			$uid = $go_user_info["b_uid"];
			$member = $this->db->GetOne("select uid,password,mobile,email from `@#_member` where `uid` = '$uid' LIMIT 1");
			if(empty($member)){
				$this->db->Query("delete from `@#_member_band` where `b_id` = '".$go_user_info['b_id']."'");
				$userpass = md5('123456');
				$path = 'data/uploads/wximg/';
				$mulu = time();
				if($touimg){
					$post_arr= array("img"=>$touimg,"path"=>$path,"mulu"=>$mulu);
					_g_triggerRequest(WEB_PATH.'/mobile/user/wximg',false,$post_arr);
				}
				$go_user_img  = 'wximg/'.$mulu . '.jpg';
				$go_user_time = time();
					$q1 = $this->db->Query("INSERT INTO `@#_member` (`username`,`password`,`img`,`money`,`band`,`yaoqing`,`time`) VALUES ('$nickname','$userpass','$go_user_img',0,'weixin','$decode','$go_user_time')");
				$uid = $this->db->insert_id();
				$this->db->Query("INSERT INTO `@#_member_band` (`b_uid`, `b_type`, `b_code`, `b_time`) VALUES ('$uid', 'weixin', '$wx_openid', '$go_user_time')");
				$member = $this->db->GetOne("select uid,password,mobile,email from `@#_member` where `uid` = '$uid' LIMIT 1");
				$se1 = _setcookie("uid",_encrypt($member['uid']),60*60*24*7);
				$se2 = _setcookie("ushell",_encrypt(md5($member['uid'].$member['password'].$member['mobile'].$member['email'])),60*60*24*7);
				$callback_url =  WEB_PATH."/mobile/home/mobilebind";
				//$callback_url =  WEB_PATH."/mobile/home/";
				header("Location:$callback_url");
			}
			$se1 = _setcookie("uid",_encrypt($member['uid']),60*60*24*7);
			$se2 = _setcookie("ushell",_encrypt(md5($member['uid'].$member['password'].$member['mobile'].$member['email'])),60*60*24*7);
			$callback_url =  WEB_PATH."/mobile/home/";
			header("Location:$callback_url");
		}
		}
	}
	public function wximg(){
		$imgurl = trim($_POST['img']);
		$path = trim($_POST['path']);
		$mulu = trim($_POST['mulu']);
		$IMG = file_get_contents($imgurl);
		file_put_contents($path ."/". $mulu . '.jpg',$IMG);
		file_put_contents($path ."/". $mulu . '.jpg_160160.jpg',$IMG);
		file_put_contents($path ."/". $mulu . '.jpg_3030.jpg',$IMG);
		file_put_contents($path ."/". $mulu . '.jpg_8080.jpg',$IMG);
        echo 1;
    }
}
?>