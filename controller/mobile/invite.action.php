<?php 
defined('G_IN_SYSTEM')or exit('No permission resources.');
System::load_app_class('base','member','no');
System::load_app_fun('my','go');
System::load_app_fun('user','go');
System::load_sys_fun('send');
System::load_sys_fun('user');
class invite extends base {
	public function __construct(){ 
		parent::__construct();
		if(ROUTE_A!='userphotoup' and ROUTE_A!='singphotoup'){
			if(!$this->userinfo)_messagemobile("请登录",WEB_PATH."/mobile/user/login",3);
		}		
		$this->db = System::load_sys_class('model');
	}

	function friends(){
        $webname=$this->_cfg['web_name'];
        $member=$this->userinfo;
        $title="我的用户中心";
        $memberdj=$this->db->GetList("select * from `@#_member_group`");
        $jingyan=$member['jingyan'];
        if(!empty($memberdj)){
            foreach($memberdj as $key=>$val){
                if($jingyan>=$val['jingyan_start'] && $jingyan<=$val['jingyan_end']){
                    $member['yungoudj']=$val['name'];
                }
            }
        }

        $mysql_model=System::load_sys_class('model');
        $member=$this->userinfo;
        $uid=_getcookie('uid');
        $notinvolvednum=0;  //未参加购买的人数
        $involvednum=0;     //参加预购的人数
        $involvedtotal=0;   //邀请人数


        //查询邀请好友信息
        $invifriends=$mysql_model->GetList("select * from `@#_member` where `yaoqing`='$member[uid]' ORDER BY `time` DESC");
        $involvedtotal=count($invifriends);


        //var_dump($invifriends);

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
		$wx = 0;
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') != false ) {
			$wxconfig=System::load_sys_config("weixin");
            include dirname(__FILE__).DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR."weixinsdk.php";
            $obj = new JSSDK($wxconfig['api'],$wxconfig['apikey']);
            $config = $obj->getSignPackage();
            $wx = 1;
            $info=System::load_sys_config("share");
        }
        include templates("mobile/invite","friends");
    }
	function friends1(){
        $webname=$this->_cfg['web_name'];
        $member=$this->userinfo;
        $title="我的购买中心";
        $memberdj=$this->db->GetList("select * from `@#_member_group`");
        $jingyan=$member['jingyan'];
        if(!empty($memberdj)){
            foreach($memberdj as $key=>$val){
                if($jingyan>=$val['jingyan_start'] && $jingyan<=$val['jingyan_end']){
                    $member['yungoudj']=$val['name'];
                }
            }
        }

        $mysql_model=System::load_sys_class('model');
        $member=$this->userinfo;
        $uid=_getcookie('uid');
        $notinvolvednum=0;  //未参加购买的人数
        $involvednum=0;     //参加预购的人数
        $involvedtotal=0;   //邀请人数


        //查询邀请好友信息
        $invifriends=$mysql_model->GetList("select * from `@#_member` where `yaoqing`='$member[uid]' ORDER BY `time` DESC");
        $involvedtotal=count($invifriends);


        //var_dump($invifriends);

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
        include templates("mobile/invite","friends1");
    }
	
	function promlist(){
        $mysql_model=System::load_sys_class('model');
		$uid = $this->segment(4);
		$member=$this->db->Getone("select * from `@#_member` where `uid`='$uid' limit 1");
		if (!$member) {
			_messagemobile("会员不存在");
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
        include templates("mobile/invite","promlist");
    }

	 function address(){
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		$title="收货地址";
		$member_dizhi=$mysql_model->Getlist("select * from `@#_member_dizhi` where uid='".$member['uid']."' limit 5");
		foreach($member_dizhi as $k=>$v){		
			$member_dizhi[$k] = _htmtocode($v);
		}
		$count=count($member_dizhi);
		include templates("mobile/invite","address");
    }
        

		function usertransfer(){
		$member=$this->userinfo;
		$title="转帐";
	
		if(isset($_POST['submit1'])){
			
			$tmoney=$_POST[money];
			$tuser=$_POST[txtBankName];
			if($member[score]<1000)
				_messagemobile("帐户福分不得小与1000",null,3);
		if($_POST[money]<1000)
				_messagemobile("转帐福分不得小于1000",null,3);
			if(empty($tmoney)||empty($tuser))
				_messagemobile("转入用户和金额不得为空",null,3);
			if($tmoney>$member[score])
				_messagemobile("转入福分不得大于帐户福分",null,3);
			$user= $this->db->GetOne("SELECT * FROM `@#_member` where `email` = '$tuser' limit 1");	
			if(empty($user))
				$user= $this->db->GetOne("SELECT * FROM `@#_member` where `mobile` = '$tuser' limit 1");	
			if(empty($user))
					_messagemobile("转入用户不存在",null,3);
			$uid=$member[uid];
			$tuid=$user[uid];
		if($uid==$tuid)
					_messagemobile("不能给自己转帐",null,3);
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
				
				
		_messagemobile("给".$tname."的".$tmoney."福分冲值成功
!",null,3);		
		}
		include templates("mobile/invite","usertransfer");
	}	
    function commissions(){
        $webname=$this->_cfg['web_name'];
        $member=$this->userinfo;
        $title="我的用户中心";
        $mysql_model=System::load_sys_class('model');
        $uid = $member['uid'];
		$recodetotal=1;  //计算佣金余额
		$recodearr=$mysql_model->GetList("select * from `@#_member_account` where `uid`='$uid' and pay='佣金' ORDER BY `time` DESC limit 10");

        include templates("mobile/invite","commissions");
    }
	public function clistajax(){
	    $webname=$this->_cfg['web_name'];
		$member=$this->userinfo;
		$uid = $member['uid'];
		$cate_band =htmlspecialchars($this->segment(4));
		$select =htmlspecialchars($this->segment(5));
		$p =htmlspecialchars($this->segment(6)) ? $this->segment(6) :1;

		//分页

		$end=$_GET['pagesize'] ? intval($_GET['pagesize']) : 10;
		$star=($p-1)*$end;
		$mysql_model=System::load_sys_class('model');
		$count=$mysql_model->GetList("select * from `@#_member_account` where `uid`='$uid' and pay='佣金'");
		$list=$mysql_model->GetList("select * from `@#_member_account` where `uid`='$uid' and pay='佣金' ORDER BY `time` DESC limit $star,$end");
		$pagex=ceil(count($count)/$end);
		if($list){
			foreach($list as $k=>$v){
				$list[$k]['date'] = date("Y-m-d H:i",$v['time']);
			}
		}
		if($p<=$pagex){
			$list[0]['page']=$p+1;
		}
		if($pagex>0){
			$list[0]['sum']=$pagex;
		}else if($pagex==0){
			$list[0]['sum']=$pagex;
		}

		echo json_encode($list);
	}
    function cashout(){

        $webname=$this->_cfg['web_name'];
        $member=$this->userinfo;
        $title="我的用户中心";
        $memberdj=$this->db->GetList("select * from `@#_member_group`");
        $jingyan=$member['jingyan'];
        if(!empty($memberdj)){
            foreach($memberdj as $key=>$val){
                if($jingyan>=$val['jingyan_start'] && $jingyan<=$val['jingyan_end']){
                    $member['yungoudj']=$val['name'];
                }
            }
        }

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


        $total=$member['yongjin'];  //计算佣金余额
        $cashoutdjtotal= $cashoutdj['summoney'] ? $cashoutdj['summoney'] : 0;  //冻结佣金余额
        $cashouthdtotal= $total-$cashoutdj['summoney'];  //活动佣金余额

		$fufen = System::load_app_config("user_fufen",'','member');
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
		     _messagemobile("金额大于100元才能提现！");exit;
		 }elseif($total<$money ){
		     _messagemobile("输入额超出余额！");exit;
		 }else{
			$this->db->Query("UPDATE `@#_member` SET `yongjin`=yongjin-'$money' WHERE (`uid`='{$uid}')");
			$this->db->Query("INSERT INTO `@#_member_cashout`(`uid`,`money`,`username`,`bankname`,`branch`,`banknumber`,`linkphone`,`time`)VALUES
			('$uid','$money','$username','$bankname','$branch','$banknumber','$linkphone','$time')");
			_messagemobile("申请成功！请等待审核！");
		 }
	   }
	   if(isset($_POST['submit2'])){//充值
		  $money      = abs(intval($_POST['txtCZMoney']));
		  $type       = -1;
		  $pay        ="佣金";
		  $time       =time();
		  

		 if($money <= 0 || $money > $total){
			  _messagemobile("佣金金额输入不正确！");exit;
		 }
		 
		 $fee = ceil($money/100)*$fufen['fufen_yongjintx'];
		 $money_c = $money - $fee;
		 $content    ="使用佣金充值到购买账户,手续费".$fee."元";
			
		  //插入记录
		 $account=$this->db->Query("INSERT INTO `@#_member_account`(`uid`,`type`,`pay`,`content`,`money`,`time`)VALUES
			('$uid','$type','$pay','$content','$money_c','$time')");

		 // 查询是否有该记录
		 if($account){
			 //修改剩余金额
			 $this->db->Query("UPDATE `@#_member` SET `yongjin`=`yongjin`-$money,`money`=`money`+$money_c WHERE (`uid`='{$uid}')");
			 
			 //在佣金表中插入记录
		    $account=$this->db->Query("INSERT INTO `@#_member_account`(`uid`,`type`,`pay`,`content`,`money`,`time`)VALUES
			('$uid','1','账户','$content','$money_c','$time')");
			_messagemobile("充值成功！");
		 }else{
		     _messagemobile("充值失败！");
		 }
	   }

        include templates("mobile/invite","cashout");
    }
    
	function record(){
        $webname=$this->_cfg['web_name'];
        $member=$this->userinfo;
        $title="我的用户中心";
        $memberdj=$this->db->GetList("select * from `@#_member_group`");
        $jingyan=$member['jingyan'];
        if(!empty($memberdj)){
            foreach($memberdj as $key=>$val){
                if($jingyan>=$val['jingyan_start'] && $jingyan<=$val['jingyan_end']){
                    $member['yungoudj']=$val['name'];
                }
            }
        }

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
        include templates("mobile/invite","record");
    }

	//购买记录
	public function userbuylist(){
	   $webname=$this->_cfg['web_name'];
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		$uid = $member['uid'];
		$title="购买记录";					
		//$record=$mysql_model->GetList("select * from `@#_member_go_record` where `uid`='$uid' ORDER BY `time` DESC");				
		include templates("mobile/user","userbuylist");
	}
	//购买记录详细
	public function userbuydetail(){
	    $webname=$this->_cfg['web_name'];
		$mysql_model=System::load_sys_class('model');
		$member=$this->userinfo;
		$title="购买详情";
		$crodid=intval($this->segment(4));
		$record=$mysql_model->GetOne("select * from `@#_member_go_record` where `id`='$crodid' and `uid`='$member[uid]' LIMIT 1");		
		if($crodid>0){
			include templates("member","userbuydetail");
		}else{
			echo _messagemobile("页面错误",WEB_PATH."/member/home/userbuylist",3);
		}
	}
	//获得的商品
	public function orderlist(){
	    $webname=$this->_cfg['web_name'];
		$member=$this->userinfo;
		$title="获得的商品";
		//$record=$this->db->GetList("select * from `@#_member_go_record` where `uid`='".$member['uid']."' and `huode`>'10000000' ORDER BY id DESC");				
		include templates("mobile/user","orderlist");
	}
	//账户管理
	public function userbalance(){
	    $webname=$this->_cfg['web_name'];
		$member=$this->userinfo;
		$title="账户记录";
		$account=$this->db->GetList("select * from `@#_member_account` where `uid`='$member[uid]' and `pay` = '账户' ORDER BY time DESC");
         $czsum=0;
         $xfsum=0;
		if(!empty($account)){ 
			foreach($account as $key=>$val){
			  if($val['type']==1){
				$czsum+=$val['money'];		  
			  }else{
				$xfsum+=$val['money'];		  
			  }		
			} 		
		}
		
		include templates("mobile/user","userbalance");
	}
	
	 
	public function userrecharge(){
	    $webname=$this->_cfg['web_name'];
		$member=$this->userinfo;
		$title="账户充值";
		//$paylist = $this->db->GetList("SELECT * FROM `@#_pay` where `pay_start` = '1'");
 	
		include templates("mobile/user","recharge");
	}	

	//晒单
	public function singlelist(){
		 $webname=$this->_cfg['web_name'];
		include templates("mobile/user","singlelist");
	}	

	 
}

?>