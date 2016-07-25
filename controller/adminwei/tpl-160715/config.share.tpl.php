<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/api-uploadify.js" type="text/javascript"></script>
</head>
<body>
<div class="header-title lr10">
	<b>分享设置</b>
</div>
<div class="bk10"></div>
<div class="table_form lr10">
<form action="" method="post" id="myform">
<table width="100%" class="lr10">
  <tr>
    <td align="right">PC分享文字：</td>
    <td><input type="text" class="input-text" name="pc_share_title" size="30" value="<?php echo $info['pc_share_title'];?>"/></td>
  </tr>
  <tr>
    <td align="right">微信分享标题：</td>
    <td><input type="text" name="share_title" value="<?php echo $info['share_title']; ?>" size="30"  class="input-text"></td>
  </tr>
  <tr>
    <td align="right">微信分享描述：</td>
    <td><input type="text" name="share_desc" value="<?php echo $info['share_desc']; ?>" size="30"  class="input-text"></td>
  </tr>
    <tr>
    <td align="right">微信分享图片：</td>
    <td>
      <input type="text" id="imagetext" value="<?php echo $info['share_pic']; ?>" name="share_pic" class="input-text wid300">
      <input type="button" class="button" onClick="GetUploadify('<?php echo WEB_PATH; ?>','uploadify','LOGO上传','image','banner',1,500000,'imagetext')" value="上传图片"/>
    </td>
  </tr>
	<tr>
    	<td width="100"></td>
   		<td> <input type="submit" value=" 提交 " name="dosubmit" class="button"></td>
    </tr>
</table>

</form>
</div>

</body>
</html>