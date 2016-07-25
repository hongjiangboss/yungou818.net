<?php

defined('G_IN_SYSTEM')or exit('No permission resources.');
System::load_app_class('base', 'member', 'no');
System::load_sys_fun('user');
System::load_app_fun('my');
System::load_app_fun('user');
@ini_set("memory_limit", "-1");

class store extends SystemAction {

    public function __construct() {
        $this->db = System::load_sys_class('model');
    }

    //限时揭晓
    public function init() {

        //header("location: ".WEB_PATH);
        System::load_sys_fun('user');
        $title = "排名" . "_" . $this->_cfg["web_name"];

        //头部他们正在云购
        $go_record=$this->db->GetList("select `@#_member`.uid,`@#_member`.username,`@#_member`.email,`@#_member`.mobile,`@#_member`.img,`@#_member_go_record`.shopname,`@#_member_go_record`.shopid from `@#_member_go_record`,`@#_member` where `@#_member`.uid = `@#_member_go_record`.uid and `@#_member_go_record`.`status` LIKE '%已付款%'  ORDER BY `@#_member_go_record`.time DESC LIMIT 0,9");

        //头部充值排行
        $charge_list=$this->db->GetList("select `@#_member`.uid,`@#_member`.username,`@#_member`.email,`@#_member`.mobile,`@#_member`.img,(select sum(`@#_member_addmoney_record`.money) as amount from `@#_member_addmoney_record` where `@#_member`.uid = `@#_member_addmoney_record`.uid) as amount from `@#_member_addmoney_record`,`@#_member` where `@#_member`.uid = `@#_member_addmoney_record`.uid and `@#_member_addmoney_record`.`status` = '已付款'  ORDER BY amount DESC LIMIT 0,9");

        //充值前10排行榜
        $czph = $this->getczlist();
        //消费前10排行榜
        $xfph = $this->getxflist();
        //富豪前10排行
        $fhph = $this->getfhlist();
        //中奖前10排行
        // $zjph = $this->getzjlist();
		
		//佣金前10排行
        $yjzq = $this->getyjlist();

        include templates("index", "store");
    }

    function getczlist(){
        $ajax = $_REQUEST['ajax']?$_REQUEST['ajax']:0;
		$curdate = strtotime(date("Y-m-d"));
		$enddate = strtotime(date("Y-m-d",strtotime('+1 day')));

		$data = $this->db->GetList("  SELECT a.uid, b.username, b.email, a.money, FROM_UNIXTIME(a.time,'%Y年%m月%d %h:%i:%s') sj
								FROM  `@#_member_account` a
								LEFT JOIN  `@#_member` b ON a.uid = b.uid
								WHERE TYPE =1
								AND (
									a.`content` =  '充值'
									OR  a.`content` =  '使用佣金充值到购买账户'
									OR  a.`content` =  '使用余额宝充值到购买账户'
									OR  a.`content` =  '充值卡'
								) and 
								(
									a.time >= '".$curdate."' 
									AND a.time < '".$enddate."'
								)
								GROUP BY uid
								ORDER BY money DESC 
								LIMIT 6");
        
        if($ajax){
            return json_decode($data);
        }else{
            return $data;
        }
    }
    

    function getxflist(){
        $ajax = $_REQUEST['ajax']?$_REQUEST['ajax']:0;
		$curdate = strtotime(date("Y-m-d"));
		$enddate = strtotime(date("Y-m-d",strtotime('+1 day')));
        $data = $this->db->GetList("SELECT uid, username, SUM( moneycount ) money
                                    FROM  `@#_member_go_record` where time >= '".$curdate."' AND time < '".$enddate."'
                                    GROUP BY uid
                                    ORDER BY money DESC 
                                    LIMIT 6");
        if($ajax){
            return json_decode($data);
        }else{
            return $data;
        }
    }
    
    function getfhlist(){
        $ajax = $_REQUEST['ajax']?$_REQUEST['ajax']:0;
		$curdate = strtotime(date("Y-m-d"));
		$enddate = strtotime(date("Y-m-d",strtotime('+1 day')));
        $data = $this->db->GetList("SELECT uid, username, email, mobile, img, TIME, money
                                    FROM  `@#_member` where login_time >= '".$curdate."' AND login_time < '".$enddate."' and money>0
                                    ORDER BY money DESC 
                                    LIMIT 6");
        if($ajax){
            return json_decode($data);
        }else{
            return $data;
        }
    }
    
    function getzjlist(){
        $ajax = $_REQUEST['ajax']?$_REQUEST['ajax']:0;
        $data = $this->db->GetList("SELECT COUNT( uid ) cs, uid, username, huode
                                    FROM  `@#_member_go_record` 
                                    WHERE huode >0
                                    GROUP BY uid
                                    ORDER BY cs DESC 
                                    LIMIT 12");
        if($ajax){
            return json_decode($data);
        }else{
            return $data;
        }
    }
	
	function getyjlist(){
		$ajax = $_REQUEST['ajax']?$_REQUEST['ajax']:0;
		$curdate = strtotime(date("Y-m-d"));
		// $curdate = strtotime('-1 day');
		$enddate = strtotime(date("Y-m-d",strtotime('+1 day')));
        $data = $this->db->GetList("  SELECT uid, username, email, yongjin as money, FROM_UNIXTIME(time,'%Y年%m月%d %h:%i:%s') sj
							FROM  `@#_member` 
							ORDER BY yongjin DESC 
							LIMIT 12");
        if($ajax){
            return json_decode($data);
        }else{
            return $data;
        }
    }
}

?>