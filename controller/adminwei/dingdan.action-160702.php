<?php

defined('G_IN_SYSTEM')or exit('no');
System::load_app_class('admin',G_ADMIN_DIR,'no');
class dingdan extends admin {
	private $db;
	public function __construct(){
		parent::__construct();
		$this->db=System::load_sys_class('model');
		$this->ment=array(
						array("lists","订单列表",ROUTE_M.'/'.ROUTE_C."/lists"),
						array("lists","中奖订单",ROUTE_M.'/'.ROUTE_C."/lists/zj"),
						array("lists","已发货",ROUTE_M.'/'.ROUTE_C."/lists/sendok"),
						array("lists","未发货",ROUTE_M.'/'.ROUTE_C."/lists/notsend"),
						array("insert","已完成",ROUTE_M.'/'.ROUTE_C."/lists/ok"),
						array("insert","已作废",ROUTE_M.'/'.ROUTE_C."/lists/del"),
						array("insert","待收货",ROUTE_M.'/'.ROUTE_C."/lists/shouhuo"),
						array("genzhong","<b>快递跟踪</b>",ROUTE_M.'/'.ROUTE_C."/genzhong"),
		);
	}

	public function genzhong(){
		include $this->tpl(ROUTE_M,'dingdan.genzhong');
	}
	public function lists(){

		/*
			已付款,未发货,已完成
			未付款,已发货,已作废
			已付款,未发货,待收货
		*/
		$where = $this->segment(4);
		if(!$where){
			$list_where = "where `status` LIKE  '%已付款%'";
		}elseif($where == 'zj'){
			//中奖
			$list_where = "where `huode` != '0'";
		}elseif($where == 'sendok'){
			//已发货订单
			$list_where = "where `huode` != '0' and  `status` LIKE  '%已发货%'";
		}elseif($where == 'notsend'){
			//未发货订单
			$list_where = "where `huode` != '0' and `status` LIKE  '%未发货%'";
		}elseif($where == 'ok'){
			//已完成
			$list_where = "where `huode` != '0' and  `status` LIKE  '%已完成%'";
		}elseif($where == 'del'){
			//已作废
			$list_where = "where `status` LIKE  '%已作废%'";
		}elseif($where == 'gaisend'){
			//该发货
			$list_where = "where `huode` != '0' and `status` LIKE  '%未发货%'";
		}elseif($where == 'shouhuo'){
			//该发货
			$list_where = "where `status` LIKE  '%待收货%'";
		}
$list_where  .= " and (uid>10307 or uid<4) ";
		if(isset($_POST['paixu_submit'])){
			$paixu = $_POST['paixu'];
			if($paixu == 'time1'){
				$list_where.=" order by `time` DESC";
			}
			if($paixu == 'time2'){
				$list_where.=" order by `time` ASC";
			}
			if($paixu == 'num1'){
				$list_where.=" order by `gonumber` DESC";
			}
			if($paixu == 'num2'){
				$list_where.=" order by `gonumber` ASC";
			}
			if($paixu == 'money1'){
				$list_where.=" order by `moneycount` DESC";
			}
			if($paixu == 'money2'){
				$list_where.=" order by `moneycount` ASC";
			}

		}else{
			$list_where.=" order by `time` DESC";
			$paixu = 'time1';
		}

		$num=20;

		$total=$this->db->GetCount("SELECT COUNT(*) FROM `@#_member_go_record` $list_where");
		$page=System::load_sys_class('page');
		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}
		$page->config($total,$num,$pagenum,"0");
		$recordlist=$this->db->GetPage("SELECT * FROM `@#_member_go_record` $list_where",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));


		include $this->tpl(ROUTE_M,'dingdan.list');
	}

	//订单详细
	public function get_dingdan(){
		$code=abs(intval($this->segment(4)));
		$record=$this->db->GetOne("SELECT * FROM `@#_member_go_record` where `id`='$code'");
		$id=$record['uid'];
		if(!$record)_message("参数不正确!");
        $member =$this->db->GetOne("SELECT * FROM `@#_member`  WHERE uid =".$id);
		$openid=$member['openid'];
		if(isset($_POST['submit'])){
			$record_code =explode(",",$record['status']);
			$status = $_POST['status'];
			$company = $_POST['company'];
			$company_code = $_POST['company_code'];
			$company_money = floatval($_POST['company_money']);
			$code = abs(intval($_POST['code']));
			if(!$company_money){
				$company_money = '0.00';
			}else{
				$company_money = sprintf("%.2f",$company_money);
			}

			if($status == '未完成'){
				$status = $record_code[0].','.$record_code[1].','.'未完成';
			}
			if($status == '已发货'){
				$status = '已付款,已发货,待收货';
				$url="http://m.yungou818.com/?/mobile/home/orderlist";
				$da='商家已发货，等待收货';
				$des="最新消息，你的订单已出库，将由[".$company."]配送，快递单号[".$company_code."] ,处理时间".date("y-m-d h:i:s",time())."点击详情查看订单完整信息.";
			}
			if($status == '未发货'){
				$status = '已付款,未发货,未完成';
				$url="http://m.yungou818.com/?/mobile/home/orderlist";
				$da='等待商家发货';
				$des="最新消息，你的订单已打包完毕 ,处理时间".date("y-m-d h:i:s",time())."点击详情查看订单完整信息.";
			}
			if($status == '已完成'){
				$url="http://m.yungou818.com/?/mobile/home/orderlist";
				$status = '已付款,已发货,已完成';
				$da='确认收货';
				$des="最新消息，你的订单已送达目的地 ,处理时间".date("y-m-d h:i:s",time())."登陆电脑进行确认收货即可,点击详情查看订单完整信息.";
			}
			if($status == '已作废'){
				$status = $record_code[0].','.$record_code[1].','.'已作废';
			}

			$ret = $this->db->Query("UPDATE `@#_member_go_record` SET `status`='$status',`company` = '$company',`company_code` = '$company_code',`company_money` = '$company_money' where id='$code'");
			
			if($ret){
				
				
			$weixin=System::load_sys_class('weixin');
		    $imp=array('touser' => $openid,
		   'template_id' => "C4li4CHhGBMZyuPpJWDU_rXEdQANWLmTDAGViExG4tA",
		   'url' => $url,
		   'data' => array(
		   'first' => array('value' =>"获得商品:".$record['shopname'],'color'=>"#737373"),
		   'OrderSn' => array('value' => $record['code'],'color'=>"#737373"),
		  'OrderStatus' => array('value' => $da,'color'=>"#FF0000"),
		  'remark'   => array('value' => $des, 'color'=>"#737373")
		  ));
		  $weixin=new weixin();
		  $weixin -> send(json_encode($imp));	
				_message("更新成功");
			}else{
				_message("更新失败");
			}
		}

		System::load_sys_fun("user");
		$uid= $record['uid'];
		$user = $this->db->GetOne("select * from `@#_member` where `uid` = '$uid'");

		$go_time = $record['time'];

		if($record['address']){
			$user_dizhi = $this->db->GetOne("select * from `@#_member_dizhi` where `uid` = '$uid' and `id` = '{$record['address']}'");
		}else{
			$user_dizhi = $this->db->GetOne("select * from `@#_member_dizhi` where `uid` = '$uid' and `default` = 'Y'");
		}
		include $this->tpl(ROUTE_M,'dingdan.code');
	}

	//订单搜索
	public function select(){
		$record = '';
		if(isset($_POST['codesubmit'])){
			$code = htmlspecialchars($_POST['text']);
			$record = $this->db->GetList("SELECT * FROM `@#_member_go_record` where `code` = '$code'");
		}
		if(isset($_POST['usersubmit'])){
			if($_POST['user'] == 'uid'){
				$uid = intval($_POST['text']);
				$record = $this->db->GetList("SELECT * FROM `@#_member_go_record` where `uid` = '$uid'");
			}
		}
		if(isset($_POST['shopsubmit'])){
			if($_POST['shop'] == 'sid'){
				$sid = intval($_POST['text']);
				$record = $this->db->GetList("SELECT * FROM `@#_member_go_record` where `shopid` = '$sid'");
			}
			if($_POST['shop'] == 'sname'){
				$sname= htmlspecialchars($_POST['text']);
				$record = $this->db->GetList("SELECT * FROM `@#_member_go_record` where `shopname` = '$sname'");
			}
		}

		if(isset($_POST['timesubmit'])){
				$start_time = strtotime($_POST['posttime1']) ? strtotime($_POST['posttime1']) : time();
				$end_time   = strtotime($_POST['posttime2']) ? strtotime($_POST['posttime2']) : time();
				$record = $this->db->GetList("SELECT * FROM `@#_member_go_record` where `time` > '$start_time' and `time` < '$end_time'");
		}


		include $this->tpl(ROUTE_M,'dingdan.soso');
	}
	
	
	
	public function share(){
		
		$num=20;
		$total=$this->db->GetCount("SELECT COUNT(*) FROM `@#_member_go_record` where auto_user = 1 and `huode` != '0'");
		$page=System::load_sys_class('page');
		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}
		$page->config($total,$num,$pagenum,"0");
		$recordlist=$this->db->GetPage("SELECT * FROM `@#_member_go_record`where auto_user = 1  and `huode` != '0'",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));	
	    include $this->tpl(ROUTE_M,'share.list');	
		
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
	
	
	
	
	public function sharefood(){
		
	    $recordid=intval($this->segment(4));
		$shopid = $recordid;
		$shaidan=$this->db->GetOne("select * from `@#_member_go_record` where `id`='$recordid' ");
		if(!$shaidan){
			_message("该商品您不可晒单!");
		}
		$shaidanyn=$this->db->GetOne("select sd_id from `@#_shaidan` where `sd_shopid`='$shaidan[shopid]' and `sd_userid` = '$shaidan[uid]'");
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


			$sd_userid = $shaidan['uid'];
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
			$this->db->Query("INSERT INTO `@#_shaidan`(`sd_userid`,`sd_shopid`,`sd_shopsid`,`sd_qishu`,`sd_ip`,`sd_title`,`sd_thumbs`,`sd_content`,`sd_photolist`,`sd_time`,`grade`)VALUES
			('$sd_userid','$sd_shopid','$sd_shopsid','$sd_qishu','$sd_ip','$sd_title','$sd_thumbs','$sd_content','$sd_photolist','$sd_time','A')");
			_message("晒单分享成功" );
		}
	
	 include $this->tpl(ROUTE_M,'share.food');		
	}

}
?>