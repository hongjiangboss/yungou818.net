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
<div class="header lr10">
   	<?php echo $this->headerment();?>
</div>
<div class="bk10"></div>

<div class="table_form lr10">
<form action="" method="post" id="myform">
<table width="100%" class="lr10">
    <tr>
    	<td width="80">用户名</td> 
   		<td><?php echo $info['username']; ?></td>
    </tr>
    <tr>
    	<td>密码</td>
    	<td><input type="password" name="password" class="input-text" id="password" value=""></input></td>
    </tr>
    <tr>
    	<td>确认密码</td> 
    	<td><input type="password" name="pwdconfirm" class="input-text" id="pwdconfirm" value=""></input></td>
    </tr>
    <tr>
    	<td>E-mail</td>
    	<td><?php echo $info['useremail']; ?></td>
	</tr>
    <tr>
    <td>所属角色</td>
        <td>
        <select name="mid">
        <option value="0" >超级管理员</option>
		  <option value="1" >普通管理员</option>
        </select>
        </td>
    </tr>
	
	
	   <tr>
    <td>系统权限</td>
        <td>
        <input type="radio" name="xitong" value="1" <?php echo $info['xitong']=='1'?'checked':'';?> class="input-text">已拥有
	<input type="radio" name="xitong" value="0" <?php echo $info['xitong']=='0'?'checked':'';?> class="input-text">未拥有
        </td>
    </tr>
	
	
	   <tr>
    <td>运营权限</td>
        <td>
        <input type="radio" name="yuny" value="1" <?php echo $info['yuny']=='1'?'checked':'';?> class="input-text">已拥有
	   <input type="radio" name="yuny" value="0" <?php echo $info['yuny']=='0'?'checked':'';?> class="input-text">未拥有
        </td>
    </tr>
	
	
	
	  <tr>
    <td>文章编辑</td>
        <td>
        <input type="radio" name="artic" value="1" <?php echo $info['artic']=='1'?'checked':'';?> class="input-text">已拥有
	   <input type="radio" name="artic" value="0" <?php echo $info['artic']=='0'?'checked':'';?> class="input-text">未拥有
        </td>
    </tr>
	
	  <tr>
    <td>商品管理</td>
        <td>
        <input type="radio" name="shop" value="1" <?php echo $info['shop']=='1'?'checked':'';?> class="input-text">已拥有
	   <input type="radio" name="shop" value="0" <?php echo $info['shop']=='0'?'checked':'';?> class="input-text">未拥有
        </td>
    </tr>
	
	  <tr>
    <td>积分购</td>
        <td>
        <input type="radio" name="exchange" value="1" <?php echo $info['exchange']=='1'?'checked':'';?> class="input-text">已拥有
	   <input type="radio" name="exchange" value="0" <?php echo $info['exchange']=='0'?'checked':'';?> class="input-text">未拥有
        </td>
    </tr>
	
	
	  <tr>
    <td>商户管理</td>
        <td>
        <input type="radio" name="bus" value="1" <?php echo $info['bus']=='1'?'checked':'';?> class="input-text">已拥有
	   <input type="radio" name="bus" value="0" <?php echo $info['bus']=='0'?'checked':'';?> class="input-text">未拥有
        </td>
    </tr>
	
	
	  <tr>
    <td>用户管理</td>
        <td>
        <input type="radio" name="user" value="1" <?php echo $info['user']=='1'?'checked':'';?> class="input-text">已拥有
	   <input type="radio" name="user" value="0" <?php echo $info['user']=='0'?'checked':'';?> class="input-text">未拥有
        </td>
    </tr>
	
	 <tr>
    <td>界面管理</td>
        <td>
        <input type="radio" name="tp" value="1" <?php echo $info['tp']=='1'?'checked':'';?> class="input-text">已拥有
	   <input type="radio" name="tp" value="0" <?php echo $info['tp']=='0'?'checked':'';?> class="input-text">未拥有
        </td>
    </tr>
	
		 <tr>
    <td>插件管理</td>
        <td>
        <input type="radio" name="pl" value="1" <?php echo $info['pl']=='1'?'checked':'';?> class="input-text">已拥有
	   <input type="radio" name="pl" value="0" <?php echo $info['pl']=='0'?'checked':'';?> class="input-text">未拥有
        </td>
    </tr>
	
			 <tr>
    <td>管理员</td>
        <td>
        <input type="radio" name="guanli" value="1" <?php echo $info['guanli']=='1'?'checked':'';?> class="input-text">已拥有
	   <input type="radio" name="guanli" value="0" <?php echo $info['guanli']=='0'?'checked':'';?> class="input-text">未拥有
        </td>
    </tr>
	
	
</table>
   	<div class="bk15"></div>
    <input type="hidden" name="submit-1" />
    <input type="button" value=" 提交 " id="dosubmit" class="button">
</form>
</div><!--table-list end-->
<script type="text/javascript">
var error='';
var bool=false;
var id='';


$(document).ready(function(){		
		
	   document.getElementById('dosubmit').onclick=function(){
		   		bool=false;
				var myform=document.getElementById('myform');
				if(!myform.password.value){	
					error='密码不能为空';
					id='password';
					bool=true;
				}
				if(!myform.pwdconfirm.value){
					error='请在次输入密码';
					id='pwdconfirm';
					bool=true;
				}
				
				
				if(bool){					
					window.parent.message(error,8,2);
					$('#'+id).focus();
					return false;
				}else{
					if(myform.password.value!=myform.pwdconfirm.value){
						window.parent.message("2次密码不相等",8,2);
						return false;
					}
					
					document.getElementById('myform').submit();					
				}
	   }
				
	
		
});

</script>
</body>
</html> 