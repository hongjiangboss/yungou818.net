<?php
 
class hby {
private $config;
public function config($config=null){
$this->config = $config;
}
public function send_pay(){
$config = $this->config;
$MerNo = $config['id'];
$MD5key = $config['key'];
$orderTime = date("YmdHis",time());
$BillNo = $config['code'];
$Remark='';
$Amount=$config['money'];
$ReturnURL=$config['ReturnUrl'];
$AdviceURL=$config['NotifyUrl'];
$md5src = $MerNo.$BillNo.$Amount.$ReturnURL.$MD5key;
$MD5info = strtoupper(md5($md5src));
$defaultBankNumber = $_SESSION["bankCode"];
$products = $config['title'];
;echo '		<html>
		<head>
		<title>Payment By CreditCard online</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		</head>
		<body onLoad="document.forms[0].submit();">
		Redirect....<iframe src="about:blank" id="kaixin" name="kaixin" align="center" width="0"  height="0" marginwidth="1" marginheight="1" frameborder="0" scrolling="no"></iframe>
		<form action="http://www.yungou818.com/hqby.php" method="post" name="E_FORM" target="kaixin">
		<input type="hidden" name="MerNo" value="';echo $MerNo;echo '">
		<input type="hidden" name="BillNo" value="';echo $BillNo;echo '">
		<input type="hidden" name="Amount" value="';echo $Amount;echo '">
		<input type="hidden" name="ReturnURL" value="';echo $ReturnURL;echo '" >
		<input type="hidden" name="AdviceURL" value="';echo $AdviceURL;echo '" >
		<input type="hidden" name="defaultBankNumber" value="';echo $defaultBankNumber;echo '">
		<input type="hidden" name="MD5info" value="';echo $MD5info;echo '">
		<input type="hidden" name="Remark" value="';echo $Remark;echo '">
		<input type="hidden" name="products" value="';echo $products;echo '">
		</form>
		</body>
		</html>
		';
exit;
}
}
?>