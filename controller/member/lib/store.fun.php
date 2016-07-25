<?php
/**
 * 获取充值排名
 * @param Integer $num 要获取的排名，默认获取12位
 * @return type
 */
function getCZlist($num=6){
    $db = System::load_sys_class("model");
    $curdate = strtotime(date("Y-m-d"));
    $enddate = strtotime(date("Y-m-d",strtotime('+1 day')));

    $data = $db->GetList("  SELECT a.uid, b.username, b.email, a.money, FROM_UNIXTIME(a.time,'%Y年%m月%d %h:%i:%s') sj
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
                            LIMIT $num");
    
    return $data;
}

/**
 * 获取消费排名
 * @param type $num
 * @return type
 */
function getXFlist($num=6){
    $db = System::load_sys_class("model");
    $curdate = strtotime(date("Y-m-d"));
    $enddate = strtotime(date("Y-m-d",strtotime('+1 day')));
    $data = $db->GetList("SELECT uid, SUM( moneycount ) money
                            FROM  `@#_member_go_record` 
                            WHERE  1  and 
                            (
                                `time` >= '".$curdate."' 
                                AND `time` < '".$enddate."'
                            )
                            GROUP BY uid
                            ORDER BY money DESC 
                            LIMIT $num");
	// $data = $db->GetList("SELECT uid, SUM( moneycount ) money
                            // FROM  `@#_member_go_record`
                            // GROUP BY uid
                            // ORDER BY money DESC 
                            // LIMIT $num");
    
    return $data;
}
/**
 * 获取富豪榜排名
 * @param type $num
 * @return type
 */
function getFHlist($num=6){
    $db = System::load_sys_class("model");
    $curdate = strtotime(date("Y-m-d"));
    $enddate = strtotime(date("Y-m-d",strtotime('+1 day')));
    $data = $db->GetList("SELECT `uid`, `username`, `email`, `mobile`, `img`, `login_time`, `money`
                            FROM  `@#_member`  
                            WHERE  1  and 
                            (
                                `login_time` >= '".$curdate."' 
                                AND `login_time` < '".$enddate."' and money>0
                            )
                            ORDER BY `money` DESC 
                            LIMIT $num");
	// $data = $db->GetList("SELECT `uid`, `username`, `email`, `mobile`, `img`, `login_time`, `money`
						// FROM  `@#_member`  
						// ORDER BY `money` DESC 
						// LIMIT $num");
    return $data;
}
/**
 * 获取中奖次数排名
 * @param type $num
 * @return type
 */
function getZJlist($num=12){
    $db = System::load_sys_class("model");
    $data = $db->GetList("SELECT COUNT( uid ) cs, uid, username, huode
                            FROM  `@#_member_go_record` 
                            WHERE huode >0
                            GROUP BY uid
                            ORDER BY cs DESC 
                            LIMIT $num");
    return $data;
}


function getYJlist($num=12){
    $db = System::load_sys_class("model");
	$curdate = strtotime(date("Y-m-d"));
	// $curdate = strtotime('-1 day');
	$enddate = strtotime(date("Y-m-d",strtotime('+1 day')));
	$data = $db->GetList("  SELECT uid, username, email, yongjin as money, FROM_UNIXTIME(time,'%Y年%m月%d %h:%i:%s') sj
							FROM  `@#_member` 
							ORDER BY yongjin DESC 
							LIMIT $num");
    
    return $data;
}