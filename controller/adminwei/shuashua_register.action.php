<?php 
defined('G_IN_SYSTEM')or exit('no');
System::load_app_class('admin',G_ADMIN_DIR,'no');
System::load_app_fun('global',G_ADMIN_DIR);
class shuashua_register extends admin {
	private $db;
	private $categorys;
	public function __construct(){		
		parent::__construct();		
		$this->db=System::load_app_model('admin_model');
	}
	
	public function show(){
	/*
		set_time_limit(0);
		ignore_user_abort(true);//检测用户断开
		for($i = 0;$i<100;$i++){
			
			file_put_contents("test.txt", "haha\n", FILE_APPEND);
			sleep(1);
		}
		*/
	//	echo "sss";
		include $this->tpl(ROUTE_M,'shuashua_register');
	}
	public function fileaction(){
		set_time_limit(0);
		ignore_user_abort(true);//检测用户断开
		if (($_FILES["file"]["type"] == "text/plain") && ($_FILES["file"]["size"] < 2000000)){
		  if($_FILES["file"]["error"] > 0){
				echo "Error: " . $_FILES["file"]["error"] . "<br />";
				return;
			}
		}else{
		  echo "文件太大---或者不是txt文件";
		  return;
		}
		
		//设定统计变量
		$tems  = 0;
		$file = fopen($_FILES["file"]["tmp_name"], "r") or exit("Unable to open file!"); 
		while(!feof($file)){  
			$line= fgets($file); 
			//中文处理
			$encode = mb_detect_encoding($line, array("ASCII","UTF-8","GB2312","GBK","BIG5")); 
			if($encode == "EUC-CN"){$line = iconv("EUC-CN","UTF-8",$line);}
			$line = $this->trimall($line);
			if(!$line){continue;}
			$linearray = explode(',',$line);
			if(count($linearray) != 4 ){continue;}
			$username = $linearray[0];//用户名
			$password = $linearray[1];//密码
			$email = isset($linearray[2])?$linearray[2]:-1;//邮箱
			$mobile = isset($linearray[3])?$linearray[3]:-1;//手机
			$ip_1 = -1;
			$ip_2 = -1;
			$ip_3 = rand(0,255);
			$ip_4 = rand(0,255);
			$ipall = array(
							array(array(58,14),array(58,25)),
							array(array(58,30),array(58,63)),
							array(array(58,66),array(58,67)),
							array(array(60,200),array(60,204)),
							array(array(60,160),array(60,191)),
							array(array(60,208),array(60,223)),
							array(array(117,48),array(117,51)),
							array(array(117,57),array(117,57)),
							array(array(121,8),array(121,29)),
							array(array(121,192),array(121,199)),
							array(array(123,144),array(123,149)),
							array(array(124,112),array(124,119)),
							array(array(125,64),array(125,98)),
							array(array(222,128),array(222,143)),
							array(array(222,160),array(222,163)),
							array(array(220,248),array(220,252)),
							array(array(211,163),array(211,163)),
							array(array(210,21),array(210,22)),
							array(array(125,32),array(125,47))
			);
			$ip_p = rand(0,count($ipall)-1);#随机生成需要IP段
			$ip_1 = $ipall[$ip_p][0][0];
			if($ipall[$ip_p][0][1] == $ipall[$ip_p][1][1]){
				$ip_2 = $ipall[$ip_p][0][1];
			}else{
				$ip_2 = rand(intval($ipall[$ip_p][0][1]),intval($ipall[$ip_p][1][1]));
			}
			$ip=_get_ip_dizhi($ip_1.'.'.$ip_2.'.'.$ip_3.'.'.$ip_4);
			
			
			
			if(!$password){
				$password =md5('111111');
			}else{
				$password =md5($password);
			}
			$member_e = array();
			$member_m = array();
			
			$sql = "";
			$time = time();
			if($email != -1 ){
				if( _checkemail($email)){
					$member_e=$this->db->GetOne("SELECT * FROM `@#_member` WHERE `email` = '$email' LIMIT 1");	
				}
			}
			if($mobile != -1 ){
				if(_checkmobile($mobile)){
					$member_m=$this->db->GetOne("SELECT * FROM `@#_member` WHERE `mobile` = '$mobile' LIMIT 1");
				}
			}
			if(is_array($member_e)){
				if(!is_array($member_m)){
					$sql="INSERT INTO `@#_member`(username,password,mobile,user_ip,img,emailcode,mobilecode,time,auto_user)VALUES('$username','$password','$mobile','$ip','photo/member.jpg','-1','1','$time','1')";
					$this->db->Query($sql);
					$tems++;
				}
			}else{
				if(is_array($member_m)){
					$sql="INSERT INTO `@#_member`(username,password,email,user_ip,img,emailcode,mobilecode,time,auto_user)VALUES('$username','$password','$email','$ip','photo/member.jpg','1','-1','$time','1')";
				}else{
					$sql="INSERT INTO `@#_member`(username,password,email,user_ip,mobile,img,emailcode,mobilecode,time,auto_user)VALUES('$username','$password','$email','$ip','$mobile','photo/member.jpg','1','1','$time','1')";
				}
				$this->db->Query($sql);
				$tems++;
			}
		}
		fclose($file); 
		//输出自动注册成功条数
		echo  "批量执行成功了：".$tems."条";
	}

	public function trimall($str)//删除空格
	{
		$qian=array(" ","　","\t","\n","\r");
		$hou=array("","","","","");
		return str_replace($qian,$hou,$str);   
	}

	
}
?>