<?php

$post = @$_POST;
if(!$post){exit;}
;echo '<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
</head>
<body>
';
$def_url = '<br /><form style="text-align:center;" action="https://pay.ecpss.com/sslpayment" method="post" name="E_FORM" target=_top>';
$def_url .= '<input type="hidden" name="MerNo" value="'.$post['MerNo'].'">';
$def_url .= '<input type="hidden" name="BillNo" value="'.$post['BillNo'].'">';
$def_url .= '<input type="hidden" name="Amount" value="'.$post['Amount'].'">';
$def_url .= '<input type="hidden" name="ReturnURL" value="'.$post['ReturnURL'].'" >';
$def_url .= '<input type="hidden" name="AdviceURL" value="'.$post['AdviceURL'].'" >';
$def_url .= '<input type="hidden" name="MD5info" value="'.$post['MD5info'].'">';
$def_url .= '<input type="hidden" name="Remark" value="'.$post['Remark'].'">';
$def_url .= '<input type="hidden" name="products" value="'.$post['products'].'">';
$def_url .= '<input type="hidden" name="defaultBankNumber" value="'.$post['defaultBankNumber'].'">';
$def_url .= "<script>document.forms[0].submit();</script>";
$def_url .= "</form>";
echo $def_url;
;echo '</body>
</html>';?>