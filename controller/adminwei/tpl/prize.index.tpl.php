<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台首页</title>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<style>
	.bg{background:#fff url(<?php echo G_GLOBAL_STYLE; ?>/global/image/ruler.gif) repeat-x scroll 0 9px }
	.color_window_td a{ float:left; margin:0px 10px;}
</style>
</head>
<body>
<script>
function CheckForm(){
	var open_time = parseInt($("#open_time").val());
	if(!open_time){
		window.parent.message("请填写开奖时间!",1,3);
		return false;
	}
	return true;
}
</script>

<div class="bk10"></div>
<div class="table_form lr10">
<form method="post" action="" onSubmit="return CheckForm()">
	<table width="100%"  cellspacing="0" cellpadding="0">
		<tr>
			<td align="right" style="width:120px"><font color="red">*</font>一等奖：</td>
			<td>
				<input type="text" value="<?php echo $arr[1]['money'];?>" name="money1" style="width:65px; padding-left:0px; text-align:center" class="input-text">元&nbsp;
				<input type="text" value="<?php echo $arr[1]['num'];?>" name="num1" style="width:65px; padding-left:0px; text-align:center" class="input-text">个
			</td>
		</tr>
		<tr>
			<td align="right" style="width:120px"><font color="red">*</font>二等奖：</td>
			<td>
				<input type="text" value="<?php echo $arr[2]['money'];?>" name="money2" style="width:65px; padding-left:0px; text-align:center" class="input-text">元&nbsp;
				<input type="text" value="<?php echo $arr[2]['num'];?>" name="num2" style="width:65px; padding-left:0px; text-align:center" class="input-text">个
			</td>
		</tr>
		<tr>
			<td align="right" style="width:120px"><font color="red">*</font>三等奖：</td>
			<td>
				<input type="text" value="<?php echo $arr[3]['money'];?>" name="money3" style="width:65px; padding-left:0px; text-align:center" class="input-text">元&nbsp;
				<input type="text" value="<?php echo $arr[3]['num'];?>" name="num3" style="width:65px; padding-left:0px; text-align:center" class="input-text">个
			</td>
		</tr>
		<tr>
			<td align="right" style="width:120px"><font color="red">*</font>四等奖：</td>
			<td>
				<input type="text" value="<?php echo $arr[4]['money'];?>" name="money4" style="width:65px; padding-left:0px; text-align:center" class="input-text">元&nbsp;
				<input type="text" value="<?php echo $arr[4]['num'];?>" name="num4" style="width:65px; padding-left:0px; text-align:center" class="input-text">个
			</td>
		</tr>
		<tr>
			<td align="right" style="width:120px"><font color="red">*</font>五等奖：</td>
			<td>
				<input type="text" value="<?php echo $arr[5]['money'];?>" name="money5" style="width:65px; padding-left:0px; text-align:center" class="input-text">元&nbsp;
				<input type="text" value="<?php echo $arr[5]['num'];?>" name="num5" style="width:65px; padding-left:0px; text-align:center" class="input-text">个
			</td>
		</tr>
		<tr>
			<td align="right" style="width:120px"><font color="red">*</font>六等奖：</td>
			<td>
				<input type="text" value="<?php echo $arr[6]['money'];?>" name="money6" style="width:65px; padding-left:0px; text-align:center" class="input-text">元&nbsp;
				<input type="text" value="<?php echo $arr[6]['num'];?>" name="num6" style="width:65px; padding-left:0px; text-align:center" class="input-text">个
			</td>
		</tr>
		<tr>
			<td align="right" style="width:120px"><font color="red">*</font>七等奖：</td>
			<td>
				<input type="text" value="<?php echo $arr[7]['money'];?>" name="money7" style="width:65px; padding-left:0px; text-align:center" class="input-text">元&nbsp;
				<input type="text" value="<?php echo $arr[7]['num'];?>" name="num7" style="width:65px; padding-left:0px; text-align:center" class="input-text">个
			</td>
		</tr>
		<tr>
			<td align="right" style="width:120px"><font color="red">*</font>八等奖：</td>
			<td>
				<input type="text" value="<?php echo $arr[8]['money'];?>" name="money8" style="width:65px; padding-left:0px; text-align:center" class="input-text">元&nbsp;
				<input type="text" value="<?php echo $arr[8]['num'];?>" name="num8" style="width:65px; padding-left:0px; text-align:center" class="input-text">个
			</td>
		</tr>
		<tr>
			<td align="right" style="width:120px"><font color="red">*</font>九等奖：</td>
			<td>
				<input type="text" value="<?php echo $arr[9]['money'];?>" name="money9" style="width:65px; padding-left:0px; text-align:center" class="input-text">元&nbsp;
				<input type="text" value="<?php echo $arr[9]['num'];?>" name="num9" style="width:65px; padding-left:0px; text-align:center" class="input-text">个
			</td>
		</tr>
		<tr>
			<td align="right" style="width:120px"><font color="red">*</font>十等奖：</td>
			<td>
				<input type="text" value="<?php echo $arr[10]['money'];?>" name="money10" style="width:65px; padding-left:0px; text-align:center" class="input-text">元&nbsp;
				<input type="text" value="<?php echo $arr[10]['num'];?>" name="num10" style="width:65px; padding-left:0px; text-align:center" class="input-text">个
			</td>
		</tr>
		<tr>
			<td align="right" style="width:120px">活动结束时间：</td>

			<td>
				<input name="open_time" value="<?php echo $open_time;?>" type="text" id="open_time" class="input-text posttime" />例：2015-06-09 13:00
			</td>
		</tr>

        <tr height="60px">
			<td align="right" style="width:120px"></td>
			<td><input type="submit" name="dosubmit" class="button" value="确定" /></td>
		</tr>
	</table>
</form>
</div>

</body>
</html>