<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<style>
tbody tr{ line-height:30px; height:30px; clear:} 
</style>
</head>
<body>
<div class="header-title lr10">
	<b>在线升级</b> <span class="lr10"> <font color="red">升级前请做好数据备份
	</font> </span>
</div>
<div class="bk10"></div>
<div class="header-data lr10">
	系统版本: <?php echo $v_version; ?>
	<span class="lr10">&nbsp;</span>
	升级时间: <?php echo $v_time; ?>
	<span class="lr10">&nbsp;</span>
</div>
<div class="bk10"></div>
<!--table-list end-->
<script>
</script>
</body>
</html> 