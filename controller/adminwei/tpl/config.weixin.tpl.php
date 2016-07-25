<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
</head>
<body>
<div class="header-title lr10">
	<b>微信接口设置</b>
    <!---
	<span class="lr10"> | </span>
	<b><a href="<?php echo G_MODULE_PATH; ?>/template/winxin_temp">邮件发送模板配置</a><b>--->
</div>
<div class="bk10"></div>
<div class="table_form lr10">
<form action="" method="post" id="myform">
<table width="100%" class="lr10">
  <tr>
    <td>微信api</td>
    <td><input type="text" class="input-text" name="api" size="30" value="<?php echo $info['api'];?>"/></td>
  </tr>  
  <tr>
	 <td>微信apkey</td>
	 <td>
     <input type="text" name="apikey" id="apikey" class="input-text"  size="30" value="<?php echo $info['apikey'];?>"/>
     </td>
  </tr> 
	<tr>
    	<td width="100"></td> 
   		<td> <input type="submit" value=" 提交 " name="dosubmit" class="button"></td>
    </tr>
</table>

</form>
</div><!--table-list end-->

</body>
</html> 