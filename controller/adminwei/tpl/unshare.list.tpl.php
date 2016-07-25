<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">

<style>
tbody tr{ line-height:30px; height:30px;} 
</style>
</head>
<body>

<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
  <table width="100%" cellspacing="0">
    <thead>
		<tr>
        	<th align="center">订单号</th>
            <th align="center">商品标题</th>
            <th align="center">购买用户</th>
            <th align="center">中奖</th>
            <th align="center">添加晒单</th>
		</tr>
    </thead>
    <tbody>
		<?php foreach($recordlist AS $v) {	?>		
            <tr>
                <td align="center"><?php echo $v['code'];?> <?php if($v['code_tmp'])echo " <font color='#ff0000'>[多]</font>"; ?></td>
                <td align="center">
                <a  target="_blank" href="<?php echo WEB_PATH.'/goods/'.$v['shopid']; ?>">
                第(<?php echo $v['shopqishu'];?>)期<?php echo _strcut($v['shopname'],0,25);?></a>
                </td>              
                 <td align="center"><?php echo $v['username']; ?></td>
                 <td align="center"><?php  echo $v['huode'] ? "中奖" : '未中奖';?></td>
                <td align="center"><a href="<?php echo G_MODULE_PATH;?>/dingdan/sharefood/<?php echo $v['id']; ?>">添加晒单</a></td>
            </tr>
            <?php } ?>
  	</tbody>
</table>
<style>
.Page_This{color:red}
</style>
<div class="btn_paixu"></div>
<div id="pages" style="cursor:pointer;"><ul><li>总共 <?php echo $total; ?> 记录分为<?php echo $p;?>页，每页<input type="text" value="<?php echo $num;?>" readonly style="width:20px"/></li><?php echo $page->show('two','li'); ?></ul></div>
</div><!--table-list end-->

<script>
</script>
</body>
</html> 