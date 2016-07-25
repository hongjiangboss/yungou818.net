<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台首页</title>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<style>
body{ background-color:#fff}
</style>
</head>
<body>
<script>
function shaidan(id){
	if(confirm("确定删除该晒单")){
		window.location.href="<?php echo G_MODULE_PATH;?>/shaidan_admin/sd_del/"+id;
	}
}
</script>
<div class="header lr10">
	<?php echo $this->headerment();?>
</div>
<div class="bk10"></div>
<div class="table_form lr10">
<!--start-->
<form name="myform" action="" method="post" enctype="multipart/form-data">
  <table width="100%" cellspacing="0">
  		<tr>
			<td width="120" align="right">评定等级：</td>
			<td>
				<select name="grade">
					<option value="A">&nbsp;&nbsp;A&nbsp;&nbsp;</option>
					<option value="B">&nbsp;&nbsp;B&nbsp;&nbsp;</option>
					<option value="C">&nbsp;&nbsp;C&nbsp;&nbsp;</option>
					<option value="D">&nbsp;&nbsp;D&nbsp;&nbsp;</option>
				</select>
                *审核等级为"D"则表示审核不通过
			</td>
		</tr> 
  	 	<tr>
			<td width="120" align="right">奖励积分</td>
			<td><input type="text" name="score" value="1" class="input-text"></td>
		</tr>
		<tr>
        	<td width="120" align="right"></td>
            <td>
            <input type="submit" class="button" name="submit" value="提交" >
            </td>
		</tr>
</table>
</form>
</div><!--table-list end-->
<script>
function upImage(){
	return document.getElementById('imgfield').click();
}
</script>
</body>
</html>