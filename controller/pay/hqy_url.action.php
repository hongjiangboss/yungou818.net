<?php
 
defined('G_IN_SYSTEM')or exit('No permission resources.');
ini_set("display_errors","OFF");
class hqy_url extends SystemAction {
public function __construct(){
$this->db=System::load_sys_class('model');
}
public function qiantai(){
$out_trade_no = $_POST["BillNo"];
$dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no'");
if(!$dingdaninfo ||$dingdaninfo['status'] == '未付款'){
_message("支付失败");
}else{
if(empty($dingdaninfo['scookies'])){
_message("充值成功!",WEB_PATH."/member/home/userbalance");
}else{
if($dingdaninfo['scookies'] == '1'){
_message("支付成功!",WEB_PATH."/member/cart/paysuccess");
}else{
_message("商品还未购买,请重新购买商品!",WEB_PATH."/member/cart/cartlist");
}
}
}
}
public function houtai(){
$pay_type =$this->db->GetOne("SELECT * from `@#_pay` where `pay_class` = 'hqy' and `pay_start` = '1'");
$pay_type_key = unserialize($pay_type['pay_key']);
$userkey =  $pay_type_key['key']['val'];
$BillNo = $_POST["BillNo"];
$Amount = $_POST["Amount"];
$Succeed = $_POST["Succeed"];
$Result = $_POST["Result"];
$MD5info = $_POST["MD5info"];
$Remark = $_POST["Remark"];
$md5src = $BillNo.$Amount.$Succeed.$userkey;
$md5sign = strtoupper(md5($md5src));
if ($MD5info==$md5sign){
if ( strval($Succeed) == "88") {
$out_trade_no = $BillNo;
$dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no' and `status` = '未付款'");
if(!$dingdaninfo){
ob_clean();
echo "ok";
exit;
}
$c_money = sprintf('%01.2f',$dingdaninfo['money']);
$uid = $dingdaninfo['uid'];
$time = time();
$this->db->Autocommit_start();
$up_q1 = $this->db->Query("UPDATE `@#_member_addmoney_record` SET `pay_type` = '汇潮支付', `status` = '已付款' where `id` = '$dingdaninfo[id]' and `code` = '$dingdaninfo[code]'");
$up_q2 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + $c_money where (`uid` = '$uid')");
$up_q3 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '充值', '$c_money', '$time')");
if($up_q1 &&$up_q2 &&$up_q3){
$this->db->Autocommit_commit();
ActivityLotteryPayMoney($out_trade_no, $uid, $c_money);
}else{
$this->db->Autocommit_rollback();
ob_clean();
echo "ok";
exit;
}
if(empty($dingdaninfo['scookies'])){
ob_clean();
echo "ok";
exit;
}
$scookies = unserialize($dingdaninfo['scookies']);
$pay = System::load_app_class('pay','pay');
$pay->scookie = $scookies;
$ok = $pay->init($uid,$pay_type['pay_id'],'go_record');
if($ok != 'ok'){
_setcookie('Cartlist',NULL);
ob_clean();
echo "ok";
exit;
}
$check = $pay->go_pay(1);
if($check){
$this->db->Query("UPDATE `@#_member_addmoney_record` SET `scookies` = '1' where `code` = '$out_trade_no' and `status` = '已付款'");
_setcookie('Cartlist',NULL);
ob_clean();
echo "ok";
exit;
}else{
ob_clean();
echo "ok";
exit;
}
}else{
ob_clean();echo 'fail';
}
}else{
ob_clean();echo 'error';
}
}
}
?>