<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><HTML xmlns="http://www.w3.org/1999/xhtml">
<head>
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta content="IE=11.0000" http-equiv="X-UA-Compatible">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
<title>YYGCMS后台登陆</title>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/login/js/jquery-1.9.1.min.js" type="text/javascript"></script>
<style>
body{
	background: #ebebeb;
	font-family: "Helvetica Neue","Hiragino Sans GB","Microsoft YaHei","\9ED1\4F53",Arial,sans-serif;
	color: #222;
	font-size: 12px;
}
*{padding: 0px;margin: 0px;}
.top_div{
	background: #008ead;
	width: 100%;
	height: 400px;
}
.ipt{
	border: 1px solid #d3d3d3;
	padding: 10px 10px;
	width: 290px;
	border-radius: 4px;
	padding-left: 35px;
	-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
	box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
	-webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
	-o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
	transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s
}
.ipt:focus{
	border-color: #66afe9;
	outline: 0;
	-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6);
	box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6)
}
.u_logo{
	background: url("<?php echo G_GLOBAL_STYLE; ?>/global/login/images/username.png") no-repeat;
	padding: 10px 10px;
	position: absolute;
	top: 43px;
	left: 66px;

}
.p_logo{
	background: url("<?php echo G_GLOBAL_STYLE; ?>/global/login/images/password.png") no-repeat;
	padding: 10px 10px;
	position: absolute;
	top: 12px;
	left: 66px;
}
.code{
	padding: 10px 10px;
	position: absolute;
	top: 12px;
	left: 40px;
}
.coder
{
margin: 200px;


}
a{
	text-decoration: none;
}
.tou{
	background: url("<?php echo G_GLOBAL_STYLE; ?>/global/login/images/tou.png") no-repeat;
	width: 97px;
	height: 92px;
	position: absolute;
	top: -87px;
	left: 140px;
}
.left_hand{
	background: url("<?php echo G_GLOBAL_STYLE; ?>/global/login/images/left_hand.png") no-repeat;
	width: 32px;
	height: 37px;
	position: absolute;
	top: -38px;
	left: 150px;
}
.right_hand{
	background: url("<?php echo G_GLOBAL_STYLE; ?>/global/login/images/right_hand.png") no-repeat;
	width: 32px;
	height: 37px;
	position: absolute;
	top: -38px;
	right: -64px;
}
.initial_left_hand{
	background: url("<?php echo G_GLOBAL_STYLE; ?>/global/login/images/hand.png") no-repeat;
	width: 30px;
	height: 20px;
	position: absolute;
	top: -12px;
	left: 100px;
}
.initial_right_hand{
	background: url("<?php echo G_GLOBAL_STYLE; ?>/global/login/images/hand.png") no-repeat;
	width: 30px;
	height: 20px;
	position: absolute;
	top: -12px;
	right: -112px;
}
.left_handing{
	background: url("<?php echo G_GLOBAL_STYLE; ?>/global/login/images/left-handing.png") no-repeat;
	width: 30px;
	height: 20px;
	position: absolute;
	top: -24px;
	left: 139px;
}
.right_handinging{
	background: url("<?php echo G_GLOBAL_STYLE; ?>/global/login/images/right_handing.png") no-repeat;
	width: 30px;
	height: 20px;
	position: absolute;
	top: -21px;
	left: 210px;
}

.code{
margin : -20px -1px 0px 204px;
}
</style>
     
<script type="text/javascript">
$(function(){
	//得到焦点
	$("#password").focus(function(){
		$("#left_hand").animate({
			left: "150",
			top: " -38"
		},{step: function(){
			if(parseInt($("#left_hand").css("left"))>140){
				$("#left_hand").attr("class","left_hand");
			}
		}}, 2000);
		$("#right_hand").animate({
			right: "-64",
			top: "-38px"
		},{step: function(){
			if(parseInt($("#right_hand").css("right"))> -70){
				$("#right_hand").attr("class","right_hand");
			}
		}}, 2000);
	});
	//失去焦点
	$("#password").blur(function(){
		$("#left_hand").attr("class","initial_left_hand");
		$("#left_hand").attr("style","left:100px;top:-12px;");
		$("#right_hand").attr("class","initial_right_hand");
		$("#right_hand").attr("style","right:-112px;top:-12px");
	});
});
</script>
 
<meta name="GENERATOR" content="MSHTML 11.00.9600.17496"></head> 
<body>



<div class="top_div"></div>
<div style="background: rgb(255, 255, 255); margin: -100px auto auto; border: 1px solid rgb(231, 231, 231); border-image: none; width: 400px; height: 200px; text-align: center;">
<div style="width: 165px; height: 96px; position: absolute;">
<div class="tou"></div>
<div style="left:100px;top:-12px;" class="initial_left_hand" id="left_hand"></div>
<div style="right:-112px;top:-12px" class="initial_right_hand" id="right_hand"></div></div>
 <form action="#" method="post" id="form">
<p style="padding: 30px 0px 10px; position: relative;">
<span class="u_logo"></span>         
<input id="input-u" name="username" class="ipt" placeholder="YYGCMS管理账号" value="" type="text"> 
</p>
<p style="position: relative;">
<span class="p_logo"></span>         
<input class="ipt" type="password" id="input-p" name="password" placeholder="YYGCMS管理密码" value="">   
</p>
<?php if(_cfg("web_off")){ ?>
</br>
<p style="position: relative;">
<span class="code">
<img width="88px" height="35px" id="checkcode" src="<?php echo WEB_PATH; ?>/api/checkcode/image/80_27/"/>
</span>   
<?php } ?>
<input class="ipt" type="text" id="input-c" name="code" placeholder="YYGCMS验证码" value="">   
</p>
<div style="height: 50px; line-height: 50px; margin-top: 30px; border-top-color: rgb(231, 231, 231); border-top-width: 1px; border-top-style: solid;">
<p style="margin: 0px 35px 20px 45px;"> 
<span style="float: right;">
<input style="background: rgb(0, 142, 173); padding: 7px 10px; border-radius: 4px; border: 1px solid rgb(26, 117, 152); border-image: none; color: rgb(255, 255, 255); font-weight: bold;" type="button" id="form_but"  value="YYGCMS登录" />
</span>         
</p>
 </form>
</div>
</div>

<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/global.js"></script>
<script src="<?php echo G_PLUGIN_PATH; ?>/layer/layer.min.js"></script>

<script type="text/javascript">
var loading;
var form_but;
window.onload=function(){
	
	 document.onkeydown=function(){
		if(event.keyCode == 13){
             ajaxsubmit();
        }          
	}
	form_but=document.getElementById('form_but');
	form_but.onclick=ajaxsubmit;	
	<?php if(_cfg("web_off")){ ?>
	var checkcode=document.getElementById('checkcode');	
	checkcode.src = checkcode.src + new Date().getTime();	
	var src=checkcode.src;
		checkcode.onclick=function(){
				this.src=src+'/'+new Date().getTime();
	}
   <?php } ?>
		
}

$(document).ready(function(){$.focusblur("#input-u");$.focusblur("#input-p");$.focusblur("#input-c");});

function ajaxsubmit(){
		var name=document.getElementById('form').username.value;
		var pass=document.getElementById('form').password.value;
		<?php if(_cfg("web_off")){ ?>
		var codes=document.getElementById('form').code.value;
	    <?php }else{ ?>
		var codes = '';
		<?php } ?>
		//document.getElementById('form').submit();
		$.ajaxSetup({
			async : false
		});				
		$.ajax({
			   "url":window.location.href,
			   "type": "POST",
			   "data": ({username:name,password:pass,code:codes,ajax:true}),
			   "beforeSend":beforeSend, //添加loading信息
			   "success":success//清掉loading信息
		});
	
}
function beforeSend(){
	 form_but.value="登录中...";
	 loading=$.layer({
		type : 3,
		time : 0,
		shade : [0.5 , '#000' , true],
		border : [5 , 0.5 , '#7298a6', true],
		loading : {type : 4}
	});
}

function success(data){
	layer.close(loading);
	form_but.value="登录";
	var obj = jQuery.parseJSON(data);
	if(!obj.error){	
		window.location.href=obj.text;
	}else{
		$.layer({
			type :0,
			area : ['auto','auto'],
			title : ['信息',true],
			border : [5 , 0.5 , '#7298a6', true],
			dialog:{msg:obj.text}
		});
		var checkcode=document.getElementById('checkcode');
		var src=checkcode.src;
			checkcode.src='';
			checkcode.src=src;
		}
}
</script>
</body>
</html>