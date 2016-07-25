<?php

defined('G_IN_SYSTEM')or exit('No permission resources.');

System::load_app_class('base','member','no');

System::load_app_fun('my','go');

System::load_app_fun('user','go');

System::load_sys_fun('send');

System::load_sys_fun('user');

class ajax extends base {

    private $Mcartlist;

    private $Mcartlist_jf;

public function find(){
		$phone= trim($_POST['phone']);
		if(!_checkmobile($phone)){
			echo json_encode(array('sta'=>'0','msg'=>'请输入有效的手机号码！'));
			die;
		}
		$member=$this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$phone' LIMIT 1");
		if($member){
			$randcode=rand(100000,999999);
			$checkcodes=$randcode.'|'.time();
			$this->db->Query("UPDATE `@#_member` SET passcode='$checkcodes' where `uid`='$member[uid]'");
			$temp = $this->db->GetOne("select value from `@#_caches` where `key` = 'template_mobile_pwd' LIMIT 1");
			$txt = $temp['value'] ? str_replace("000000",$randcode,$temp['value']) : "您的用户名：{$member['mobile']}，申请重置密码：{$randcode}。如非本人操作请忽略！";
			$sendok=_sendmobile($phone,$txt);
			if($sendok[0]!=1){
				echo json_encode(array('sta'=>'0','msg'=>'验证码发送失败！'));
				die;
			}
			echo json_encode(array('sta'=>'1','msg'=>$phone));
			die;
		}else{
		    echo json_encode(array('sta'=>'0','msg'=>'未找到匹配的账号！'));
			die;
		}
	}

	public function findmobilecheck(){
		$phone = $this->segment(4);
		if(!empty($_POST)){
			$code = trim($_POST['code']);
			$pass1 = trim($_POST['pass1']);
			$pass2 = trim($_POST['pass2']);
			$sql="select * from `@#_member` where `mobile`='$phone' AND `passcode` like '$code%' LIMIT 1";
			$member=$this->db->GetOne($sql);
			if(!$member){
				echo "用户不存在!";die;
			}
			if($pass1 != $pass2){
				echo "密码不一致!";die;
			}
			$password=md5($pass1);
			$this->db->Query("UPDATE `@#_member` SET `password`='$password',`passcode`='-1' where `uid`='$member[uid]'");
			echo 1;die;
		}
		if(!_checkmobile($phone)){
			_message("请输入有效的手机号码！!",WEB_PATH."/mobile/user/findpassword",3);
		}
		$member=$this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$phone' LIMIT 1");
		if($member['passcode']==-1){
			_message("错误!",WEB_PATH."/mobile/user/findpassword",3);
		}
		include templates("mobile/user","findmobilecheck");
	}


	public function __construct(){

		parent::__construct();

/* 		if(ROUTE_A!='userphotoup' and ROUTE_A!='singphotoup'){

			if(!$this->userinfo)_message("请登录",WEB_PATH."/mobile/user/login",3);

		}	 */

		$this->db = System::load_sys_class('model');





		//查询购物车的信息

		$Mcartlist=_getcookie("Cartlist");

		$this->Mcartlist=json_decode(stripslashes($Mcartlist),true);



		$Mcartlist_jf=_getcookie("Cartlist_jf");

		$this->Mcartlist_jf=json_decode(stripslashes($Mcartlist_jf),true);

	}
public function sendmobcode(){

	  		$name=$this->segment(4);
            $member=$this->userinfo;

			//echo json_encode($member);
			if(!$member){
			    //_message("参数不正确!");
				$sendmobile['state']=1;
				echo json_encode($sendmobile);
				exit;
		    }
			$checkcode=explode("|",$member['mobilecode']);
			$times=time()-$checkcode[1];
			if($times > 120){

				$sendok = send_mobile_reg_code($name,$member['uid']);
				if($sendok[0]!=1){
					//_message($sendok[1]);exit;
                   	$sendmobile['state']=times;
					echo json_encode($sendmobile);
					exit;
				}
				//成功
				    $sendmobile['state']="ok";
					echo json_encode($sendmobile);
					exit;
			}else{
				    $sendmobile['state']=120-$times;
					echo json_encode($sendmobile);
					exit;
			}

	}

	public function mobileregbind(){
	    $mobile= $this->segment(4);
	    $checkcodes= $this->segment(5);
        $members=$this->userinfo;
		$member=$this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid` = $members[uid] and `mobilecode`!=1 LIMIT 1");
		if(!$member){
			$mobileregsn['state']="该账号己绑定手机!!";
			echo json_encode($mobileregsn);
			exit;
		}
		$isbind=$this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$mobile' LIMIT 1");
		if($isbind){
			$mobileregsn['state']="该手机已被使用!";
			echo json_encode($mobileregsn);
			exit;
		}
		if(strlen($checkcodes)!=6){
		    //_message("验证码输入不正确!");
			$mobileregsn['state']="验证码输入不正确";
			echo json_encode($mobileregsn);
			exit;
		}
		$usercode=explode("|",$member['mobilecode']);
		if($checkcodes!=$usercode[0]){
		   //_message("验证码输入不正确!");
			$mobileregsn['state']="验证码输入不正确";
			echo json_encode($mobileregsn);
			exit;
		}
        $time=time();
		$this->db->Query("UPDATE `@#_member` SET mobilecode='1',mobile='{$mobile}' where `uid`='$member[uid]'");

		$member['mobile'] = $mobile;
		_setcookie("uid",_encrypt($member['uid']));
		_setcookie("ushell",_encrypt(md5($member['uid'].$member['password'].$member['mobile'].$member['email'])));

		 $mobileregsn['state']=0;
		 $mobileregsn['str']=1;

        echo json_encode($mobileregsn);
	}

	public function init(){

	    if(ROUTE_A!='userphotoup' and ROUTE_A!='singphotoup'){

			if(!$this->userinfo)_message("请登录",WEB_PATH."/mobile/user/login",3);

		}



		$member=$this->userinfo;

		$title="我的用户中心";



		 $user['code']=1;

		 $user['username']=get_user_name($member['uid']);

		 $user['uid']=$member['uid'];

		 if(!empty($member)){

		   $user['code']=0;

		 }



		echo json_encode($user);





	}

	//幻灯

	public function slides(){

	  $sql="select * from `@#_wap` where 1";

	  $SlideList=$this->db->GetList($sql);

	  if(empty($SlideList)){

	    $slides['state']=1;

	  }else{

	   $slides['state']=0;

	    foreach($SlideList as $key=>$val){

		   $shopid = ereg_replace('[^0-9]','',$val['link']);

		  // $shopid=explode("/",$val['link']);

		   $slides['listItems'][$key]['alt']=$val['color'];

		   $slides['listItems'][$key]['url']=WEB_PATH."/mobile/mobile/item/".$shopid;

		   $slides['listItems'][$key]['src']=G_WEB_PATH."/data/uploads/".$val['img'];

		   $slides['listItems'][$key]['width']='614PX';

		   $slides['listItems'][$key]['height']='110PX';



		}



	  }

	   echo json_encode($slides);

	}



   // 今日揭晓商品

    public function show_jrjxshop(){

		$pagetime=safe_replace($this->segment(4));





		$w_jinri_time = strtotime(date('Y-m-d'));

		$w_minri_time = strtotime(date('Y-m-d',strtotime("+1 day")));





		$jinri_shoplist = $this->db->GetList("select * from `@#_shoplist` where `xsjx_time` > '$w_jinri_time' and `xsjx_time` < '$w_minri_time' order by xsjx_time limit 0,3 ");



		if(!empty($jinri_shoplist)){

		   $m['errorCode']=0;

		}else{

		   $m['errorCode']=1;

		}

		//echo $pagetime;

		echo json_encode($m);



	}

	//最新揭晓商品

	public function show_newjxshop(){



		//最新揭晓

		$shopqishu=$this->db->GetList("select * from `@#_shoplist` where `q_end_time` !='' ORDER BY `q_end_time` DESC LIMIT 4");



		echo json_encode($shopqishu);



	}



	//即将揭晓商品

	public function show_msjxshop(){

	      //暂时没做







		//即将揭晓商品

	    $shoplist['listItems'][0]['codeID']=14;  //商品id

	    $shoplist['listItems'][0]['period']=3;  //商品期数

	    $shoplist['listItems'][0]['goodsSName']='苹果';  //商品名称

	    $shoplist['listItems'][0]['seconds']=10;  //商品名称



		$shoplist['errorCode']=0;

		//echo json_encode($shoplist);



	}



    //购物车数量

	public function cartnum(){

	  $Mcartlist=$this->Mcartlist;

	  if(is_array($Mcartlist)){

	  	  $cartnum['code']=0;

	      $cartnum['num']=count($Mcartlist);

	  }else{

	  	  $cartnum['code']=1;

	      $cartnum['num']=0;

	  }

      //var_dump($Mcartlist);

	  echo json_encode($cartnum);

	}



	//购物车数量

	public function cartnum_jf(){

	  $Mcartlist=$this->Mcartlist_jf;

	  if(is_array($Mcartlist)){

	  	  $cartnum['code']=0;

	      $cartnum['num']=count($Mcartlist);

	  }else{

	  	  $cartnum['code']=1;

	      $cartnum['num']=0;

	  }

      //var_dump($Mcartlist);

	  echo json_encode($cartnum);

	}



	//添加购物车

	public function addShopCart(){

		$ShopId=safe_replace($this->segment(4));

		$ShopNum=safe_replace($this->segment(5));



		$cartbs=safe_replace($this->segment(6));//标识从哪里加的购物车



		$shopis=0;          //0表示不存在  1表示存在

		$Mcartlist=$this->Mcartlist;

		if($ShopId==0 || $ShopNum==0){



		$cart['code']=1;   //表示添加失败



		}else{

		  if(is_array($Mcartlist)){

			foreach($Mcartlist as $key=>$val){

			   if($key==$ShopId){

			      if(isset($cartbs) && $cartbs=='cart'){

		            $Mcartlist[$ShopId]['num']=$ShopNum;

				  }else{

				    $Mcartlist[$ShopId]['num']=$val['num']+$ShopNum;

				  }

				  $shopis=1;

			   }else{

				  $Mcartlist[$key]['num']=$val['num'];

			   }

			}



		    }else{

				$Mcartlist =array();

				$Mcartlist[$ShopId]['num']=$ShopNum;

		    }





		    if($shopis==0){

		    	$Mcartlist[$ShopId]['num']=$ShopNum;

		    }



			_setcookie('Cartlist',json_encode($Mcartlist),'');

			$cart['code']=0;   //表示添加成功

		}



		$cart['num']=count($Mcartlist);    //表示现在购物车有多少条记录



		echo json_encode($cart);



	}



	//添加购物车

	public function jf_addShopCart(){

		$ShopId=safe_replace($this->segment(4));

		$ShopNum=safe_replace($this->segment(5));



		$cartbs=safe_replace($this->segment(6));//标识从哪里加的购物车



		$shopis=0;          //0表示不存在  1表示存在

		$Mcartlist=$this->Mcartlist_jf;

		if($ShopId==0 || $ShopNum==0){



		$cart['code']=1;   //表示添加失败



		}else{

		  if(is_array($Mcartlist)){

			foreach($Mcartlist as $key=>$val){

			   if($key==$ShopId){

			      if(isset($cartbs) && $cartbs=='cart'){

		            $Mcartlist[$ShopId]['num']=$ShopNum;

				  }else{

				    $Mcartlist[$ShopId]['num']=$val['num']+$ShopNum;

				  }

				  $shopis=1;

			   }else{

				  $Mcartlist[$key]['num']=$val['num'];

			   }

			}



		    }else{

				$Mcartlist =array();

				$Mcartlist[$ShopId]['num']=$ShopNum;

		    }





		    if($shopis==0){

		    	$Mcartlist[$ShopId]['num']=$ShopNum;

		    }



			_setcookie('Cartlist_jf',json_encode($Mcartlist),'');

			$cart['code']=0;   //表示添加成功

		}



		$cart['num']=count($Mcartlist);    //表示现在购物车有多少条记录



		echo json_encode($cart);



	}



	public function delCartItem(){

	   $ShopId=safe_replace($this->segment(4));



	   $cartlist=$this->Mcartlist;



		if($ShopId==0){



		  $cart['code']=1;   //删除失败



		}else{

			   if(is_array($cartlist)){

			      if(count($cartlist)==1){

				     foreach($cartlist as $key=>$val){

					   if($key==$ShopId){

					     $cart['code']=0;

						    _setcookie('Cartlist','','');

						}else{

					     $cart['code']=1;

					   }

					 }



				  }else{

					   foreach($cartlist as $key=>$val){

							if($key==$ShopId){

							  $cart['code']=0;

							}else{

							  $Mcartlist[$key]['num']=$val['num'];

							}

						}



						   _setcookie('Cartlist',json_encode($Mcartlist),'');



					}



				}else{

				   $cart['code']=1;   //删除失败

				}



		}

		echo json_encode($cart);

	}



	public function delCartItem_jf(){

	   $ShopId=safe_replace($this->segment(4));



	   $cartlist=$this->Mcartlist_jf;



		if($ShopId==0){



		  $cart['code']=1;   //删除失败



		}else{

			   if(is_array($cartlist)){

			      if(count($cartlist)==1){

				     foreach($cartlist as $key=>$val){

					   if($key==$ShopId){

					     $cart['code']=0;

						    _setcookie('Cartlist_jf','','');

						}else{

					     $cart['code']=1;

					   }

					 }



				  }else{

					   foreach($cartlist as $key=>$val){

							if($key==$ShopId){

							  $cart['code']=0;

							}else{

							  $Mcartlist[$key]['num']=$val['num'];

							}

						}



						   _setcookie('Cartlist_jf',json_encode($Mcartlist),'');



					}



				}else{

				   $cart['code']=1;   //删除失败

				}



		}

		echo json_encode($cart);

	}



	public function getCodeState(){

	  $itemid=safe_replace($this->segment(4));

	  $item=$mysql_model->GetOne("select * from `@#_shoplist` where `id`='".$itemid."' LIMIT 1");



	  $a['Code']=1;

	  if(!$item){

	     $a['Code']=0;

	  }



	 echo json_encode($a);

	}





	//login

	public function userlogin(){

	    $username=safe_replace($this->segment(4));

	    $pwd = base64_decode($this->segment(5));

	    $password=md5(safe_replace($pwd));



		$logintype='';

		if(strpos($username,'@')==false){

			//手机

			$logintype='mobile';

		}else{

			//邮箱

			$logintype='email';

		}



		$member=$this->db->GetOne("select * from `@#_member` where `$logintype`='$username' and `password`='$password'");

		if(!$member){

			//帐号不存在错误

			$user['state']=1;

			$user['num']=-2;

			echo json_encode($user);die;

		}



		if($member[$logintype.'code'] != 1){

			$user['state']=2; //未验证

			echo json_encode($user);die;

		}


		if(!is_array($member)){

			//帐号或密码错误

			$user['state']=1;

			$user['num']=-1;

		}else{

		   //登录成功

			_setcookie("uid",_encrypt($member['uid']),60*60*24*7);

			_setcookie("ushell",_encrypt(md5($member['uid'].$member['password'].$member['mobile'].$member['email'])),60*60*24*7);



			$user['state']=0;



		}

		echo json_encode($user);

	}



	//登录成功后

	public function loginok(){



	  $user['Code']=0;

	  echo json_encode($user);

	}

	/***********************************注册*********************************/



	//检测用户是否已注册

	public function checkname(){

	    $config_email = System::load_sys_config("email");

		$config_mobile = System::load_sys_config("mobile");



		$name= $this->segment(4);

		if(!_checkmobile($name)){

			$user['state']=1;//_message("系统短息配置不正确!");

			 echo json_encode($user);

			 exit;

		}



		$regtype=null;

		if(_checkmobile($name)){

			$regtype='mobile';

			$cfg_mobile_type  = 'cfg_mobile_'.$config_mobile['cfg_mobile_on'];

			$config_mobile = $config_mobile[$cfg_mobile_type];

			if(empty($config_mobile['mid']) && empty($config_email['mpass'])){



				 $user['state']=2;//_message("系统短息配置不正确!");

				 echo json_encode($user);

				 exit;

			}

		}

		$member=$this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$name' LIMIT 1");

		if(is_array($member)){

			if($member['mobilecode']==1 || $member['emailcode']==1){

			  $user['state']=1;//_message("该账号已被注册");

			}else{

			  $sql="DELETE from`@#_member` WHERE `mobile` = '$name'";

			  $this->db->Query($sql);

			  $user['state']=0;

			}

		}else{

		    $user['state']=0;//表示数据库里没有该帐号

		}



	    echo json_encode($user);

	}



	//将数据注册到数据库

	public function userMobile(){

# 我的修改

		$name= isset($_GET['username'])? $_GET['username']: $this->segment(4);

		$pass= isset($_GET['password'])? md5($_GET['password']): md5(base64_decode($this->segment(5)));

		$time=time();

	//	$decode = 0;

		//邮箱验证 -1 代表未验证， 1 验证成功 都不等代表等待验证

		//$sql="INSERT INTO `@#_member`(`mobile`,password,img,emailcode,mobilecode,yaoqing,time)VALUES('$name','$pass','photo/member.jpg','-1','-1','$decode','$time')";

	//	if(!$name || $this->db->Query($sql)){

			

		//	$userMobile['state']=0;

	//	}else{

		//	$userMobile['state']=1;

	//	}

	//  echo json_encode($userMobile);

	//}

# 手机版邀请升级的修改
		$decode=_encrypt($_COOKIE['regcode'],"DECODE");
		$decode = intval($decode);
		$decode = 0;
		//邮箱验证 -1 代表未验证， 1 验证成功 都不等代表等待验证
		$sql="INSERT INTO `@#_member`(`mobile`,password,img,emailcode,mobilecode,yaoqing,time)VALUES('$name','$pass','photo/member.jpg','-1','-1','$decode','$time')";
		if(!$name || $this->db->Query($sql)){
			setcookie("regcode",'');
			$userMobile['state']=0;
		}else{
			$userMobile['state']=1;
		}
		echo json_encode($userMobile);
	}
# 手机版邀请升级的修改-结束

	//验证输入的手机验证码

	public function mobileregsn(){
	    $mobile= $this->segment(4);
	    $checkcodes= $this->segment(5);

		$member=$this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$mobile' LIMIT 1");

		if(strlen($checkcodes)!=6){
		    //_message("验证码输入不正确!");
			$mobileregsn['state']=1;
			echo json_encode($mobileregsn);
			exit;
		}
		$usercode=explode("|",$member['mobilecode']);
		if($checkcodes!=$usercode[0]){
		   //_message("验证码输入不正确!");
			$mobileregsn['state']=1;
			echo json_encode($mobileregsn);
			exit;
		}
		
		# 手机版邀请升级的修改
			/*$fili_cfg = System::load_app_config("user_fufen","","member");
			$score = $fili_cfg['f_phonecode'];
			$jingyan = $fili_cfg['z_phonecode'];
			$this->db->Query("UPDATE `@#_member` SET mobilecode='1',score=score+$score,jingyan=jingyan+$jingyan where `uid`='$member[uid]'");
			
			if($member['yaoqing']){
				$time = time();
				$yaoqinguid = $member['yaoqing'];
				//福分、经验添加
				if($fili_cfg['f_visituser']){
					$this->db->Query("insert into `@#_member_account` (`uid`,`type`,`pay`,`content`,`money`,`time`) values ('$yaoqinguid','1','福分','邀请好友奖励','$fili_cfg[f_visituser]','$time')");
				}
				$this->db->Query("UPDATE `@#_member` SET `score`=`score`+'$fili_cfg[f_visituser]',`jingyan`=`jingyan`+'$fili_cfg[z_visituser]' where uid='$yaoqinguid'");
			}*/
# 手机版邀请升级的修改-结束

		//福分、经验添加
		$decode=_encrypt($_COOKIE['regcode'],"DECODE");
		$decode = intval($decode);
		$isset_user=$this->db->GetOne("select `uid` from `@#_member_account` where `pay`='手机认证完善奖励' and `type`='1' and `uid`='{$member['uid']}'");
		if(empty($isset_user)){
			$config = System::load_app_config("user_fufen","","member");//福分/经验
			$time=time();
			$this->db->Query("insert into `@#_member_account` (`uid`,`type`,`pay`,`content`,`money`,`time`) values ('{$member['uid']}','1','福分','手机认证完善奖励','{$config[f_phonecode]}','{$time}')");
			$this->db->Query("insert into `@#_member_account` (`uid`,`type`,`pay`,`content`,`money`,`time`) values ('{$member['uid']}','1','经验','手机认证完善奖励','{$config[z_phonecode]}','{$time}')");
			$this->db->Query("UPDATE `@#_member` SET mobilecode='1',`score`=`score`+'$config[f_phonecode]',`jingyan`=`jingyan`+'$config[z_phonecode]',yaoqing='".$decode."' where uid='".$member['uid']."'");
			
			if($member['yaoqing']){
				$yaoqinguid = $member['yaoqing'];
				$score = $jingyan = 0;
				if($config['f_visituser']){
					$score = $config['f_visituser'];
					$this->db->Query("insert into `@#_member_account` (`uid`,`type`,`pay`,`content`,`money`,`time`) values ('$yaoqinguid','1','福分','邀请好友奖励','$config[f_visituser]','$time')");
				}
				if($config['z_visituser']){
					$jingyan = $config['z_visituser'];
					$this->db->Query("insert into `@#_member_account` (`uid`,`type`,`pay`,`content`,`money`,`time`) values ('$yaoqinguid','1','经验','邀请好友奖励','$config[z_visituser]','$time')");
				}
				$this->db->Query("UPDATE `@#_member` SET `score`=`score`+'$config[f_visituser]',`jingyan`=`jingyan`+'$config[z_visituser]',yaoqing='".$decode."' where uid='$yaoqinguid'");
			}
		}else{
			$this->db->Query("UPDATE `@#_member` SET mobilecode='1' where uid='".$member['uid']."'");
		}

		_setcookie("uid",_encrypt($member['uid']),60*60*24*7);
		_setcookie("ushell",_encrypt(md5($member['uid'].$member['password'].$member['mobile'].$member['email'])),60*60*24*7);

		$mobileregsn['state']=0;
		$mobileregsn['str']=1;

        echo json_encode($mobileregsn);
	}



	//重新发送验证码

	public function sendmobile(){



	  		$name=$this->segment(4);

			$member=$this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$name' LIMIT 1");

			if(!$member){

			    //_message("参数不正确!");

				$sendmobile['state']=1;

				echo json_encode($sendmobile);

				exit;

		    }

			$checkcode=explode("|",$member['mobilecode']);

			$times=time()-$checkcode[1];

			if($times > 120){



				$sendok = send_mobile_reg_code($name,$member['uid']);

				if($sendok[0]!=1){

					//_message($sendok[1]);exit;

                   	$sendmobile['state']=1;

					echo json_encode($sendmobile);

					exit;

				}

				//成功

				    $sendmobile['state']=0;

					echo json_encode($sendmobile);

					exit;

			}else{

				    $sendmobile['state']=1;

					echo json_encode($sendmobile);

					exit;

			}



	}

	//最新揭晓

	public function getLotteryList(){

	   $FIdx=$this->segment(4);

	   $EIdx=10;//$this->segment(5);

	   $isCount=$this->segment(6);



	      $shopsum=$this->db->GetList("select id from `@#_shoplist` where `q_uid` is not null and `q_showtime` = 'N'");



	   //最新揭晓

		$shoplist['listItems']=$this->db->GetList("select * from `@#_shoplist` where `q_uid` is not null and `q_showtime` = 'N' ORDER BY `q_end_time` DESC limit $FIdx,$EIdx");



		if(empty($shoplist['listItems'])){

		  $shoplist['code']=1;

		}else{

		 foreach($shoplist['listItems'] as $key=>$val){

		 //查询出购买次数

		   $recodeinfo=$this->db->GetOne("select sum(gonumber) as num from `@#_member_go_record` where `uid` ='$val[q_uid]'  and `shopid`='$val[id]' ");

		   $shoplist['listItems'][$key]['q_user']=get_user_name($val['q_uid']);

		   $shoplist['listItems'][$key]['userphoto']=get_user_key($val['q_uid'],'img');

		   $shoplist['listItems'][$key]['q_end_time']=microt($val['q_end_time']);

		   $shoplist['listItems'][$key]['gonumber']=$recodeinfo['num'];

		 }

		  $shoplist['code']=0;

		  $shoplist['count']=count($shopsum);

		}



		echo json_encode($shoplist);



	}



	//访问他人购买记录

	public function getUserBuyList(){

	   $type=$this->segment(4);

	   $uid=$this->segment(5);

	   $FIdx=$this->segment(6);

	   $EIdx=10;//$this->segment(7);

	   $isCount=$this->segment(8);



		 if($type==0){

          //参与购买的商品 全部...

		  $shoplist=$this->db->GetList("select *,sum(gonumber) as gonumber from `@#_member_go_record` a left join `@#_shoplist` b on a.shopid=b.id where a.uid='$uid' GROUP BY shopid ");



		  $shop['listItems']=$this->db->GetList("select *,sum(gonumber) as gonumber from `@#_member_go_record` a left join `@#_shoplist` b on a.shopid=b.id where a.uid='$uid' GROUP BY shopid order by a.time desc limit $FIdx,$EIdx " );

		 }elseif($type==1){

		   //获得奖品

		    $shoplist=$this->db->GetList("select * from  `@#_shoplist`  where q_uid='$uid' " );



		    $shop['listItems']=$this->db->GetList("select * from  `@#_shoplist`  where q_uid='$uid' order by q_end_time desc limit $FIdx,$EIdx" );

		 }elseif($type==2){

		   //晒单记录

		    $shoplist=$this->db->GetList("select * from `@#_shaidan` a left join `@#_shoplist` b on a.sd_shopid=b.id where a.sd_userid='$uid' " );



		    $shop['listItems']=$this->db->GetList("select * from `@#_shaidan` a left join `@#_shoplist` b on a.sd_shopid=b.id where a.sd_userid='$uid' order by a.sd_time desc limit $FIdx,$EIdx" );



		 }



		 if(empty($shop['listItems'])){

		   $shop['code']=4;



		 }else{

		   foreach($shop['listItems'] as $key=>$val){

		      if($val['q_end_time']!=''){

			    $shop['listItems'][$key]['codeState']=3;

			    $shop['listItems'][$key]['q_user']=get_user_name($val['q_uid']);

                $shop['listItems'][$key]['q_end_time']=microt($val['q_end_time']);



			  }

			  if(isset($val['sd_time'])){

			   $shop['listItems'][$key]['sd_time']=date('m月d日 H:i',$val['sd_time']);

			  }

		   }

		   $shop['code']=0;

		   $shop['count']=count($shoplist);

		 }

		 //ECHO "<PRE>";

	     //PRINT_R($shop);



	   echo json_encode($shop);

	}



	 //查看计算结果

	 public function getCalResult(){

	     $itemid=$this->segment(4);

		 $curtime=time();



		 $item=$this->db->GetOne("select * from `@#_shoplist` where `id`='$itemid' and `q_end_time` is not null LIMIT 1");



		if($item['q_content']){

		    $item['contcode']=0;

			$item['itemlist'] = unserialize($item['q_content']);



			foreach($item['itemlist'] as $key=>$val){

			  	$item['itemlist'][$key]['time']	=microt($val['time']);

				$h=date("H",$val['time']);

			    $i=date("i",$val['time']);

			    $s=date("s",$val['time']);

			    list($timesss,$msss) = explode(".",$val['time']);



				$item['itemlist'][$key]['timecode']=$h.$i.$s.$msss;

			}



		}else{

		    $item['contcode']=1;

		}



		if(!empty($item)){

		  $item['code']=0;



		}else{

		  $item['code']=1;

		}



    //echo "<pre>";

	//print_r($item);

	//print_r($record_time);

	   echo json_encode($item);





	 }





	 //付款

	public function UserPay(){





	}



	//显示两分钟内 马上揭晓的商品

	public function GetStartRaffleAllList(){



	   //暂时没有该功能。。。。。

	}



//增加手机版头像修改
	public function userimg(){
		if(!$this->userinfo){
			echo 0;die;
		}

		$member=$this->userinfo;

		$img= isset($_POST['img']) ? rawurldecode($_POST['img']) : '';

		if(empty($img)){
			echo 0;die;
		}

		//匹配出图片的格式
		if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $img, $result)){
			$type = $result[2];
			$filename = 'touimg/'.time().".jpg";
			$new_file = dirname($_SERVER['SCRIPT_FILENAME']).'/data/uploads/'.$filename;
			$base64_body = substr(strstr($img,','),1);
			if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $img)))){
				$uid = $this->userinfo['uid'];
				$sql="update `@#_member` set img='$filename' where uid='$uid'";
				if($this->db->Query($sql)){
					$tname  = $filename;
					if(file_exists($new_file)){
						$wh = getimagesize($new_file);
						$x = 0;
						$y = 0;
						$w = $wh[0];
						$h = $wh[1];
						$point = array("x"=>$x,"y"=>$y,"w"=>$w,"h"=>$h);

						System::load_sys_class('upload','sys','no');
						upload::thumbs(160,160,false,G_UPLOAD.$tname,$point);
						upload::thumbs(80,80,false,G_UPLOAD.$tname,$point);
						upload::thumbs(30,30,false,G_UPLOAD.$tname,$point);
					}
					echo 1;
				}else{
					echo 0;
				}
			}else{
				echo 0;
			}
		}else{
			echo 0;
		}
	}

//增加手机版头像修改


}



?>