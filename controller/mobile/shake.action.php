<?php

defined('G_IN_SYSTEM')or exit('No permission resources.');
System::load_app_class('base', 'member', 'no');
System::load_app_fun('my', 'go');
System::load_app_fun('user', 'go');
System::load_sys_fun('send');
System::load_sys_fun('user');

class shake extends base {

    public function __construct() {
        parent::__construct();
        if (ROUTE_A != 'userphotoup' and ROUTE_A != 'singphotoup') {
            // if(!$this->userinfo)_message("请登录",WEB_PATH."/mobile/user/login",3);
        }
        $this->db = System::load_sys_class('model');
        $this->base = array(
            "1" => array('一等奖', array(0, 0)),
            "2" => array('二等奖', array(1, 1000)),
            "3" => array('三等奖', array(2, 1000)),
            "4" => array('四等奖', array(7, 1000)),
            "5" => array('五等奖', array(10, 1000)),
            "6" => array('六等奖', array(20, 1000)),
            "7" => array('七等奖', array(30, 1000)),
            "8" => array('八等奖', array(100, 1000)),
            "9" => array('九等奖', array(500, 1000)),
            "10" => array('十等奖', array(800, 1000)),
        );
        $this->prize = array();
    }

    public function init() {
        $webname = $this->_cfg['web_name'];
        $member = $this->userinfo;
        $title = "摇一摇";
        include templates("mobile/index", "shake");
    }

    public function getajax() {
        $member = $this->userinfo;
        if (!$member) {
            $msg = $data['winprize'];
            echo '{"error":1,"msg":"您还没有登陆，无法参与抽奖哦"}';
            exit;
        }

        //查询当天是否已经抽过奖
        $curdate = strtotime(date("Y-m-d"));
        $enddate = strtotime(date("Y-m-d",strtotime('+1 day')));
        $tm = $this->db->GetList("select * from `@#_activity_lottery` where uid={$member['uid']} and time >= '".$curdate."' and time < '".$enddate."' ");
        if($tm){
            echo '{"error":1,"msg":"您今天已经抽过奖了，每天只能摇一次！"}';
            exit;
        }
        //组成prize
        $list = $this->db->GetList("select * from `@#_prize` where open_time > '" . time() . "' and last > 0");
        if ($list) {
            foreach ($list as $k => $v) {
                $t = array($this->base[$v['grade']][0], $v['money'] . '元红包', $v['money'], $this->base[$v['grade']][1], $v['grade']);
                $this->prize[] = $t;
            }
        } else {
            echo '{"error":1,"msg":"奖品已被抢完，请下次再来吧。"}';
            exit;
        }

        $p = $this->probability();
        if ($p == -1) {
            echo '{"error":0,"msg":"哎呀，姿势不对吧，竟然没中奖！"}';
            exit;
        } else {
            list($title, $desc, $money, $tmp, $prizetype) = $this->prize[$p];
            //$round = $this->round($p);
            $this->db->Query("UPDATE `@#_prize` SET `last` = `last` - 1 where `grade`='{$prizetype}'");
            $this->db->Query("UPDATE `@#_member` SET `money` = `money` + $money where (`uid` = '" . $member['uid'] . "')");
            $this->db->Query("INSERT INTO `@#_activity_lottery`(uid,prize,money,time,title,`desc`) VALUES"
                    . "('" . $member['uid'] . "','$p','$money','" . time() . "','{$title}','{$desc}')");
            $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('" . $member['uid'] . "', '1', '账户', '摇一摇抽奖[{$desc}]', '$money', '" . time() . "')");
            echo '{"error":1,"msg":"恭喜您抽中' . $desc . '。"}';
            exit;
        }
        echo '{"error":1,"msg":"今天抽奖已经结束，欢迎明天再来吧。"}';
        exit;
    }

    # 获取范围

    private function round($p) {
        $width = 360 / 7;
        $a = $p * $width;
        $b = $a + $width;
        return mt_rand($a + 10, $b - 10);
    }

    # 随机一个概率出来
    private function probability() {
        $probability_all = array(0, 0);
        foreach ($this->prize as $i => $val) {
            list($title, $desc, $money, $probability) = $val;
            $probability_all[0] += $probability[0];
            $probability_all[1] += $probability[1];
        }

        if (empty($this->prize)) {
            return -1;
        }

        $probability_all[1] = intval($probability_all[1] / count($this->prize));
        $yes = mt_rand(1, $probability_all[1]);
        $prize = -1;
        if ($probability_all[0] <= 0 || $probability_all[1] <= 0 || $yes > $probability_all[0]) {
            
        } else {
            $list = array();
            $add = 0;
            $total = 0;
            foreach ($this->prize as $i => $val) {
                list($title, $desc, $money, $probability) = $val;
                if ($probability[0] <= 0) {
                    continue;
                }
                $total = $add += $probability[0];
                $list[$add] = $i;
            }

            $yes = mt_rand(1, $total);
            foreach ($list as $k => $v) {
                if ($yes <= $k) {
                    $prize = $v;
                    break;
                }
            }
        }
        return $prize;
    }

}

?>