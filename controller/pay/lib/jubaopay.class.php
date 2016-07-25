<?php
include dirname(__FILE__).DIRECTORY_SEPARATOR."jubaopay".DIRECTORY_SEPARATOR."jubaopay3.php";

class jubaopay
{
public $config;
public function config($config = null)
{
$this->config = $config;
}
public function send_pay()
{
	$config = $this->config;
	// $v_mid = $config['id'];
// print_r($config);
	// header("Content-Type: text/html; charset=UTF-8");

	$payid=$config['code'];
	$partnerid=$config['id'];
	$amount=$config['money'];

	$db=System::load_sys_class('model');
	$members = $db->GetOne("SELECT uid FROM `@#_member_addmoney_record` where `code` = '$payid'");
	$payerName=get_user_name($members['uid']);
	$remark="订单".$payid;
	$returnURL="http://".$_SERVER['HTTP_HOST']."/?/pay/jubaopay_url/qiantai/";;
	$callBackURL="http://".$_SERVER['HTTP_HOST']."/?/pay/jubaopay_url/houtai/";
	$payMethod="ALL";

	//////////////////////////////////////////////////////////////////////////////////////////////////
	 //商户利用支付订单（payid）和商户号（mobile）进行对账查询
	 
	$jubaopay=new jubao(dirname(__FILE__).DIRECTORY_SEPARATOR."jubaopay".DIRECTORY_SEPARATOR."/jubaopay.ini");
	$jubaopay->setEncrypt("payid", $payid);
	$jubaopay->setEncrypt("partnerid", $partnerid);
	$jubaopay->setEncrypt("amount", $amount);
	$jubaopay->setEncrypt("payerName", $payerName);
	$jubaopay->setEncrypt("remark", $remark);
	$jubaopay->setEncrypt("returnURL", $returnURL);
	$jubaopay->setEncrypt("callBackURL", $callBackURL);
	// $jubaopay->setEncrypt("payMethodChannels", "ll");
	// $jubaopay->setEncrypt("payType", "credit_express");
	// $jubaopay->setEncrypt("bankCode", "CMBCHINA");

	//对交易进行加密=$message并签名=$signature
	$jubaopay->interpret();
	$message=$jubaopay->message;
	$signature=$jubaopay->signature;

?>
	<form method="post" action="https://www.jubaopay.com/apipay.htm" id="payForm">
	<input type="hidden" name="message" value="<?php echo $message;?>">
	<input type="hidden" name="signature" value="<?php echo $signature;?>">
	<input type="hidden" name="payMethod" value="<?php echo $payMethod;?>">
</form>

<script type="text/javascript">
    document.getElementById('payForm').submit();
</script>
<?php
exit();
}
}
?>