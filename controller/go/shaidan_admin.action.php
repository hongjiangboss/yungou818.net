<?php

defined('G_IN_SYSTEM')or exit('no');
System::load_app_class('admin',G_ADMIN_DIR,'no');
System::load_app_fun('global',G_ADMIN_DIR);
class shaidan_admin extends admin {
	private $db;
	public function __construct(){
		parent::__construct();
		$this->ment=array(
			array("lists","晒单管理",ROUTE_M.'/'.ROUTE_C.""),
			array("addcate","晒单回复管理",ROUTE_M.'/'.ROUTE_C."/sd_hueifu"),
		);
		$this->db=System::load_sys_class('model');
	}
	public function init(){
		$num=20;
		$total=$this->db->GetCount("select * from `@#_shaidan`");
		$page=System::load_sys_class('page');
		if(isset($_GET['p'])){
			$pagenum=$_GET['p'];
		}else{$pagenum=1;}
		$page->config($total,$num,$pagenum,"0");
		if($pagenum>$page->page){
			$pagenum=$page->page;
		}
		$shaidan=$this->db->GetPage("select * from `@#_shaidan`",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
		include $this->tpl(ROUTE_M,'shaidan.list');
	}
	public function sd_del(){
		$id=intval($this->segment(4));
		$shaidanx=$this->db->getlist("select * from `@#_shaidan` where `sd_id`='$id' limit 1 ");
		if($shaidanx){
			$this->db->Query("DELETE FROM `@#_shaidan` where `sd_id`='$id' ");
			_message("删除成功");
		}else{
			_message("参数错误");
		}
	}
	public function hf_del(){
		$id=intval($this->segment(4));
		$shaidanx=$this->db->getlist("select * from `@#_shaidan_hueifu` where `id`='$id' limit 1 ");
		if($shaidanx){
			$this->db->Query("DELETE FROM `@#_shaidan_hueifu` where `id`='$id' ");
			_message("删除成功");
		}else{
			_message("参数错误");
		}
	}
	public function sd_hueifu(){
		$member=$this->db->getlist("select * from `@#_member`");
		$num=20;
		$total=$this->db->GetCount("select * from `@#_shaidan_hueifu`");
		$page=System::load_sys_class('page');
		if(isset($_GET['p'])){
			$pagenum=$_GET['p'];
		}else{$pagenum=1;}
		$page->config($total,$num,$pagenum,"0");
		if($pagenum>$page->page){
			$pagenum=$page->page;
		}
		$shaidan=$this->db->GetPage("select * from `@#_shaidan_hueifu`",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));
		include $this->tpl(ROUTE_M,'shaidan.liuyan');
	}
	public function check(){
		$id=intval($this->segment(4));
		if(empty($id)){
			_message("晒单不存在");
		}
		$shaidanx=$this->db->getOne("select * from `@#_shaidan` where `sd_id`='$id' limit 1 ");
		if(empty($shaidanx)){
			_message("晒单不存在");
		}
		if(!empty($_POST)){
			$grade = strtoupper($_POST['grade']);
			$score = $_POST['score'] ? intval($_POST['score']) : 0;
			if($grade=='D'){
				$this->db->Query("update `@#_shaidan` set grade='D' where `sd_id`='$id'");
			}
			if(in_array($grade, array("A","B","C"))){
				$this->db->Query("update `@#_shaidan` set grade='$grade',score='$score' where `sd_id`='$id'");
				if($score > 0){
					$time = time();
					$this->db->Query("insert into `@#_member_account` (`uid`,`type`,`pay`,`content`,`money`,`time`) values ('{$shaidanx['sd_userid']}','1','福分','晒单商品','{$score}','$time')");
					$this->db->Query("update `@#_member` set score=score+{$score} where uid='{$shaidanx['sd_userid']}'");
				}
			}
			_message("操作成功",WEB_PATH.'/'.ROUTE_M.'/'.ROUTE_C."/init");
		}
		include $this->tpl(ROUTE_M,'shaidan.check');
	}
}
?>