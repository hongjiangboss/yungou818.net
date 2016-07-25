<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar-blue.css" type="text/css"> 
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar.js"></script>
 
<style>
tbody tr{ line-height:30px; height:30px;} 
</style>
</head>
<body>
 <div class="header-data lr10">
<form action="" method="post" id="form1" style="display:inline-block; ">
	添加时间:
    	   <input name="posttime1" value="<?php echo empty($start_time)?"":date("Y-m-d H:i:s",$start_time);?>" type="text" id="posttime1" class="input-text posttime"  readonly="readonly" />
          &nbsp; <===> &nbsp; 
 		   <input name="posttime2" value="<?php echo empty($end_time)?"":date("Y-m-d H:i:s",$end_time);?>" type="text" id="posttime2" class="input-text posttime"  readonly="readonly" />
 		<!--   <select name="is_shaidan" style="width:100px;height:25px;line-height:25px;">-->
<!--  		     <option value="1" checked>已经晒单</opption> -->
<!--  		     <option value="0" checked>未晒单</option> -->
<!--  		  </select> -->

<script type="text/javascript">
		date = new Date();
		Calendar.setup({
					inputField     :    "posttime1",
					ifFormat       :    "%Y-%m-%d %H:%M:%S",
					showsTime      :    true,
					timeFormat     :    "24"
		});
		Calendar.setup({
					inputField     :    "posttime2",
					ifFormat       :    "%Y-%m-%d %H:%M:%S",
					showsTime      :    true,
					timeFormat     :    "24"
		});
				
</script>
<input class="button" type="submit" name="timesubmit" value=" 搜 索 ">
</form>
</div>
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
            <th align="center">购买时间</th>
            <th align="center">地址</th>
            <th align="center">添加晒单</th>
		</tr>
    </thead>
    <tbody>
		<?php foreach($recordlist AS $v) {	?>		
            <tr>
                <td align="center"><?php echo $v['code'];?> <?php if($v['code_tmp'])echo " <font color='#ff0000'>[多]</font>"; ?></td>
                <td width="35%">
                <a  target="_blank" href="<?php echo WEB_PATH.'/goods/'.$v['shopid']; ?>"> 
                第(<?php echo $v['shopqishu'];?>)期<?php echo _strcut($v['shopname'],0,25);?></a>
                </td>              
                 <td align="center" width="10%"><?php echo $v['username']; ?></td>
                 <td align="center"><?php  echo $v['huode'] ? "中奖" : '未中奖';?></td>
                 <td align="center"><?php echo date("Y-m-d H:i:s",$v['time']);?></td>
                 <td algin="center" width="16%"><?php echo $v['ip'];?></td>
                <td align="center">
                <?php 
                    if($v['is_share']==1){
                 ?>      
                     <a href="javascript:;">不可以晒单</a>
                   <?php  }else if($v['is_share'] == 2){?>    
                    <a href="javascript:;" style="color:red">已经晒单</a>
                    <?php }else if($v['is_share'] == 3){?>
                    <a href="javascript:;" style="color:red">不能晒单</a>
                    <?php }else{?>
                    <a href="<?php echo G_MODULE_PATH;?>/dingdan/sharefood/<?php echo $v['id']; ?>" style="color:green">添加晒单 </a>
                    <?php }?>
                </td>
                
            </tr>
            <?php } ?>
  	</tbody>
</table>
<style>
.Page_This{color:red}
</style>
<div class="btn_paixu"></div>
<div id="pages" style="cursor:pointer;"><ul><li>总共 <?php echo $total; ?> 记录分为<?php echo $p;?>页，每页<input type="text" value="<?php echo $num;?>" readonly style="width:20px"/></li><?php echo $page->show('three','li'); ?></ul></div>
</div><!--table-list end-->

</body>
</html> 