<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8"> 
<link rel="Shortcut Icon" href="<?php echo G_WEB_PATH;?>/favicon.ico">
<link id=cssfile rel=stylesheet type=text/css href="<?php echo G_WEB_PATH;?>/data/plugin/style/images/skin_0.css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/index.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_PLUGIN_PATH; ?>/layer/layer.min.js"></script>
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/global.js"></script>
<title>后台首页</title>
<script type="text/javascript">
var ready=1;
var kj_width;
var kj_height;
var header_height=91;
var R_label;
var R_label_one = "当前位置: 系统设置 >";


function left(init){
	var left = document.getElementById("left");
	var leftlist = left.getElementsByTagName("ul");
	
	for (var k=0; k<leftlist.length; k++){
		leftlist[k].style.display="none";
	}
	document.getElementById(init).style.display="block";
}

function secBoard(elementID,n,init,r_lable) {
			
	var elem = document.getElementById(elementID);
	var elemlist = elem.getElementsByTagName("li");
	for (var i=0; i<elemlist.length; i++) {
		elemlist[i].className = "normal";		
	}
	elemlist[n].className = "current";
	R_label_one="当前位置: "+r_lable+" >";
	R_label.text(R_label_one);
	left(init);
}


function set_div(){
		kj_width=$(window).width();
		kj_height=$(window).height();
		if(kj_width<1000){kj_width=1000;}
		if(kj_height<500){kj_height=500;}

		$("#header").css('width',kj_width); 
		$("#header").css('height',header_height);
		$("#left").css('height',kj_height-header_height); 
	    $("#right").css('height',kj_height-header_height); 
		$("#left").css('top',header_height); 
		$("#right").css('top',header_height);
		
		$("#left").css('width',180);		
		$("#right").css('width',kj_width-182); 
		$("#right").css('left',182);
		
		$("#right_iframe").css('width',kj_width-206); 
		$("#right_iframe").css('height',kj_height-148);
		 		
		$("#iframe_src").css('width',kj_width-208); 
		$("#iframe_src").css('height',kj_height-150); 	
		
		$("#off_on").css('height',kj_height-180);
		
		var nav=$("#nav");		
				nav.css("left",(kj_width-nav.get(0).offsetWidth)/2);
		nav.css("top",61);	
}


$(document).ready(function(){	
		set_div();		
		$("#off_on").click(function(){
				if($(this).attr('val')=='open'){
					$(this).attr('val','exit');
					$("#right").css('width',kj_width);
					$("#right").css('left',1);
					$("#right_iframe").css('width',kj_width-25); 
					$("iframe").css('width',kj_width-27);
				}else{
					$(this).attr('val','open');
					$("#right").css('width',kj_width-182);
					$("#right").css('left',182);
					$("#right_iframe").css('width',kj_width-206); 
					$("iframe").css('width',kj_width-208);
				}
		});
		
		left('setting');
		$(".left_date a").click(function(){
				$(".left_date li").removeClass("set");						  
				$(this).parent().addClass("set");
				R_label.text(R_label_one+' '+$(this).text()+' >');
				$("#iframe_src").attr("src",$(this).attr("src"));
		});
		$(".left_date1 a").click(function(){
				$(".left_date li").removeClass("set");						  
				$(this).parent().addClass("set");
				R_label.text(R_label_one+' '+$(this).text()+' >');
				$("#iframe_src").attr("src",$(this).attr("src"));
		});
		$("#iframe_src").attr("src","<?php echo G_MODULE_PATH; ?>/index/Tdefault");
		R_label=$("#R_label");
	//	$('body').bind('contextmenu',function(){return false;});
	//	$('body').bind("selectstart",function(){return false;});
				
});

function api_off_on_open(key){
	if(key=='open'){
				$("#off_on").attr('val','exit');
				$("#right").css('width',kj_width);
				$("#right").css('left',1);
				$("#right_iframe").css('width',kj_width-25); 
				$("iframe").css('width',kj_width-27);
	}else{
					$("#off_on").attr('val','open');
					$("#right").css('width',kj_width-182);
					$("#right").css('left',182);
					$("#right_iframe").css('width',kj_width-206); 
					$("iframe").css('width',kj_width-208);
	}
}
</script>

<style>
.header_case{  position:absolute; right:10px; top:10px; color:#fff}
.header_case a{ padding-left:5px}
.header_case a:hover{ color:#fff; }

.left_date a{background: url("../images/sky/left.png") repeat-y scroll left top rgba(0, 0, 0, 0);}
.left_date{overflow:hidden;}
.left_date ul{ margin:0px; padding:0px;}
.left_date li{line-height:25px; height:25px; margin:0px 10px; margin-left:15px; overflow:hidden;}
.left_date li a{ display:block;text-indent:5px;  overflow:hidden}
.left_date li a:hover{ background-color:#d3e8f2;text-decoration:none;border-radius:3px;}
.left_date .set a{background-color:#d3e8f2;border-radius:3px; font-weight:bold}
.head{ border-bottom:1px solid #032E43; color:#032E43; font-weight:bold; margin-bottom:10px;}

.left_date1 {overflow:hidden;}
</style>

</head>

<link id="cssfile" rel=stylesheet type=text/css href="<?php echo G_WEB_PATH;?>/data/plugin/style/images/skin_0.css">




<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries --><!--[if lt IE 9]>
<SCRIPT src="/data/plugin/style/images/html5shiv.js"></SCRIPT>

<SCRIPT src="/data/plugin/style/images/respond.min.js"></SCRIPT>
<![endif]-->


<script>
//
$(document).ready(function () {
    $('span.bar-btn').click(function () {
	$('ul.bar-list').toggle('fast');
    });
});

$(document).ready(function(){
	var pagestyle = function() {
		var iframe = $("#workspace");
		var h = $(window).height() - iframe.offset().top;
		var w = $(window).width() - iframe.offset().left;
		if(h < 300) h = 300;
		if(w < 973) w = 973;
		iframe.height(h);
		iframe.width(w);
	}
	pagestyle();
	$(window).resize(pagestyle);
	//turn location
	if($.cookie('now_location_act') != null){
		openItem($.cookie('now_location_op')+','+$.cookie('now_location_act')+','+$.cookie('now_location_nav'));
	}else{
		$('#mainMenu>ul').first().css('display','block');
		//第一次进入后台时，默认定到欢迎界面
		$('#item_welcome').addClass('selected');
		$('#workspace').attr('src','<?php echo G_ADMIN_PATH; ?>/index/Tdefault');
	}
	$('#iframe_refresh').click(function(){
		var fr = document.frames ? document.frames("workspace") : document.getElementById("workspace").contentWindow;;
		fr.location.reload();
	});

});
//收藏夹
function addBookmark(url, label) {
    if (document.all)
    {
        window.external.addFavorite(url, label);
    }
    else if (window.sidebar)
    {
        window.sidebar.addPanel(label, url, '');
    }
}


function openItem(args){
    closeBg();
	//cookie

	if($.cookie('F81E_sys_key') === null){
		location.href = 'index.php?act=login&op=login';
		return false;
	}

	spl = args.split(',');
	op  = spl[0];
	try {
		act = spl[1];
		nav = spl[2];
	}
	catch(ex){}
	if (typeof(act)=='undefined'){var nav = args;}
	$('.actived').removeClass('actived');
	$('#nav_'+nav).addClass('actived');

	$('.selected').removeClass('selected');

	//show
	$('#mainMenu ul').css('display','none');
	$('#sort_'+nav).css('display','block');

	if (typeof(act)=='undefined'){
		//顶部菜单事件
		html = $('#sort_'+nav+'>li>dl>dd>ol>li').first().html();
		str = html.match(/openItem\('(.*)'\)/ig);
		arg = str[0].split("'");
		spl = arg[1].split(',');
		op  = spl[0];
		act = spl[1];
		nav = spl[2];
		first_obj = $('#sort_'+nav+'>li>dl>dd>ol>li').first().children('a');
		$(first_obj).addClass('selected');
		//crumbs
		$('#crumbs').html('<span>'+$('#nav_'+nav+' > span').html()+'</span><span class="arrow">&nbsp;</span><span>'+$(first_obj).text()+'</span>');
	}else{
		//左侧菜单事件
		//location
		$.cookie('now_location_nav',nav);
		$.cookie('now_location_act',act);
		$.cookie('now_location_op',op);
		$("a[name='item_"+op+act+"']").addClass('selected');
		//crumbs
		$('#crumbs').html('<span>'+$('#nav_'+nav+' > span').html()+'</span><span class="arrow">&nbsp;</span><span>'+$('#item_'+op+act).html()+'</span>');
	}
	src = 'index.php?act='+act+'&op='+op;
	$('#workspace').attr('src',src);

}

$(function(){
		bindAdminMenu();
		})
		function bindAdminMenu(){

		$("[nc_type='parentli']").click(function(){
			var key = $(this).attr('dataparam');
			if($(this).find("dd").css("display")=="none"){
				$("[nc_type='"+key+"']").slideDown("fast");
				$(this).find('dt').css("background-position","-322px -170px");
				$(this).find("dd").show();
			}else{
				$("[nc_type='"+key+"']").slideUp("fast");
				$(this).find('dt').css("background-position","-483px -170px");
				$(this).find("dd").hide();
			}
		});
	}
</script>

<script type=text/javascript>
//显示灰色JS遮罩层
function showBg(ct,content){
var bH=$("body").height();
var bW=$("body").width();
var objWH=getObjWh(ct);
$("#pagemask").css({width:bW,height:bH,display:"none"});
var tbT=objWH.split("|")[0]+"px";
var tbL=objWH.split("|")[1]+"px";
$("#"+ct).css({top:tbT,left:tbL,display:"block"});
$(window).scroll(function(){resetBg()});
$(window).resize(function(){resetBg()});
}
function getObjWh(obj){
var st=document.documentElement.scrollTop;//滚动条距顶部的距离
var sl=document.documentElement.scrollLeft;//滚动条距左边的距离
var ch=document.documentElement.clientHeight;//屏幕的高度
var cw=document.documentElement.clientWidth;//屏幕的宽度
var objH=$("#"+obj).height();//浮动对象的高度
var objW=$("#"+obj).width();//浮动对象的宽度
var objT=Number(st)+(Number(ch)-Number(objH))/2;
var objL=Number(sl)+(Number(cw)-Number(objW))/2;
return objT+"|"+objL;
}
function resetBg(){
var fullbg=$("#pagemask").css("display");
if(fullbg=="block"){
var bH2=$("body").height();
var bW2=$("body").width();
$("#pagemask").css({width:bW2,height:bH2});
var objV=getObjWh("dialog");
var tbT=objV.split("|")[0]+"px";
var tbL=objV.split("|")[1]+"px";
$("#dialog").css({top:tbT,left:tbL});
}
}

//关闭灰色JS遮罩层和操作窗口
function closeBg(){
$("#pagemask").css("display","none");
$("#dialog").css("display","none");
}
</script>

<script type=text/javascript>
$(function(){
    var $li =$("#skin li");
		$li.click(function(){
		$("#"+this.id).addClass("selected").siblings().removeClass("selected");
		$("#cssfile").attr("href","<?php echo WEB_PATH; ?>/admin/templates/default/css/"+ (this.id) +".css");
        $.cookie( "MyCssSkin" ,  this.id , { path: '/', expires: 10 });

        $('iframe').contents().find('#cssfile2').attr("href","<?php echo WEB_PATH; ?>/admin/templates/default/css/"+ (this.id) +".css");
    });

    var cookie_skin = $.cookie( "MyCssSkin");
    if (cookie_skin) {
		$("#"+cookie_skin).addClass("selected").siblings().removeClass("selected");
		$("#cssfile").attr("href","<?php echo WEB_PATH; ?>/admin/templates/default/css/"+ cookie_skin +".css");
		$.cookie( "MyCssSkin" ,  cookie_skin  , { path: '/', expires: 10 });
    }
});
function addFavorite(url, title) {
	try {
		window.external.addFavorite(url, title);
	} catch (e){
		try {
			window.sidebar.addPanel(title, url, '');
        	} catch (e) {
			showDialog("请按 Ctrl+D 键添加到收藏夹", 'notice');
		}
	}
}
</script>

      <div id="topnav" class="top-nav">
	  <div class="top-navleft">欢迎您进入管理后台！</div>
      <ul>
        <li class="adminid" title="<?php echo $info['username']; ?>">您好&nbsp;:&nbsp;<STRONG><?php echo $info['username']; ?></STRONG></li>
      <?php if($info['guanli']==1) { ?>   <li><a href="<?php echo G_MODULE_PATH; ?>/user/edit/<?php echo $info['uid']; ?>" target="workspace"><span>修改密码</span></a></li>		<?php } ?>
        <li><a title="安全退出" href="<?php echo G_MODULE_PATH; ?>/user/out"><span>安全退出</span></a></li>
        <li><a title="网站首页" href="<?php echo G_WEB_PATH; ?>" target="_blank"><span>网站首页</span></a></li>
		</ul>
	</div><!-- End of Top navigation --><!-- Main navigation -->
	 
	<div id="navbox" class="navbox">
	<div class="navleft"></div>
	<div class="navright">
	<NAV id="nav" class="main-nav">
      <ul>
              <li class="current"><a href="#" src="<?php echo G_MODULE_PATH; ?>/index/Tdefault" onClick="secBoard('nav',0,'setting','后台首页');">后台首页</a></li>
	        <li class="normal"><a href="#" onClick="secBoard('nav',1,'setting','系统设置');">系统设置</a></li>
            <li class="normal"><a href="#" onClick="secBoard('nav',2,'yunying','站长运营');">站长运营</a></li>
            <li class="normal"><a href="#" onClick="secBoard('nav',3,'admin','管理员管理');">管理员管理</a></li>
            <li class="normal"><a href="#" onClick="secBoard('nav',4,'content','文章管理');">文章管理</a></li>
            <li class="normal"><a href="#" onClick="secBoard('nav',5,'shop','商品管理');">商品管理</a></li>
            <li class="normal"><a href="#" onClick="secBoard('nav',6,'jf_shop','全额购管理');">全额购管理</a></li>
            <li class="normal"><a href="#" onClick="secBoard('nav',7,'user','用户管理');">用户管理</a></li>
            <li class="normal"><a href="#" onClick="secBoard('nav',8,'template','界面管理');">界面管理</a></li>
            <li class="normal"><a href="#" onClick="secBoard('nav',9,'yunapp','插件管理');">插件管理</a></li>
		</ul></NAV>
		</div>
	</div>

<!--header end-->
<div id="left">
    <ul class="left_date" id="setting"> 

    	<li class="head">系统管理</li>
		<?php if($info['xitong']==1) { ?>	
		<li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/setting/webcfg">SEO设置</a></li> 
    	<li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/setting/config">基本设置</a></li>
        <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/setting/upload">上传设置</a></li> 
        <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/setting/watermark">水印设置</a></li>	
    <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/setting/email">邮箱配置</a></li> 
        <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/setting/mobile">短信配置</a></li>
		   <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/setting/weixin">微信配置</a></li> 
        <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/template/temp">通知模板配置</a></li>
        <li><a href="javascript:void(0);" src="<?php echo WEB_PATH; ?>/pay/pay/pay_list">支付方式</a></li>        	
		<li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/setting/domain">模块域名绑定</a></li>
		<li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/qq_admin">官方QQ群</a></li>	
		<?php } ?>
		
        <li class="head">后台首页</li>
        <li><a href="javascript:void(0);" src="<?php echo G_ADMIN_PATH; ?>/index/Tdefault">后台首页</a></li>
		<li class="head">其他</li>
		<li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/cache/init">清空缓存</a></li>
  </ul>
    <ul class="left_date" id="yunying">

      	<li class="head">站长运营</li>
		 <?php if($info['yuny']==1) { ?>
        <li><a href="javascript:void(0);" src="<?php echo G_ADMIN_PATH; ?>/yunwei/websitemap">站点地图</a></li>
		<li><a href="javascript:void(0);" src="<?php echo G_ADMIN_PATH; ?>/yunwei/websubmit">网站提交</a></li>
		<li><a href="javascript:void(0);" src="<?php echo G_ADMIN_PATH; ?>/yunwei/webtongji">站长统计</a></li>
          <?php } ?>
    </ul>

  
   <ul class="left_date" id="admin">

       <li class="head">管理员管理</li>
	        <?php if($info['guanli']==1) { ?>
		<li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/user/lists">管理员管理</a></li>
        <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/user/reg">添加管理员</a></li>
        <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/user/edit/<?php echo $info['uid']; ?>">修改密码</a></li>   <?php } ?>
    </ul>

   
  <ul class="left_date" id="shop">

		<li class="head">商品管理</li>
		  <?php if($info['shop']==1) { ?>
        <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/content/goods_add">添加新商品</a></li>
        <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/content/goods_list">商品列表</a></li>
        <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/content/goods_list_l">限购商品列表</a></li>
        <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/category/lists/goods">商品分类</a></li> 
        <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/brand/lists">品牌管理</a></li>    	
    	<li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/brand/insert">添加品牌</a></li>      
        <!--li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/content/goods_del_list">商品回收站</a></li-->  
        <li class="head">订单管理</li>
       <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/dingdan/lists">订单列表</a></li>
        <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/dingdan/select">订单查询</a></li>
        <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/dingdan/lists/zj">中奖订单</a></li> 
		<li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/dingdan/lists/notsend">未发货订单</a></li> 	 
		  <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/dingdan/share">小号晒单</a></li> 
          
		 <li class="head">促销管理</li>
        <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/content/goods_list/xianshi">限时揭晓商品</a></li>
        <li class="head">其他</li>
        <li><a href="javascript:void(0);" src="<?php echo WEB_PATH; ?>/go/shaidan_admin/init">晒单查看</a></li>   <?php } ?>
         </ul>	
       
         
           <ul class="left_date" id="jf_shop">
		   
		   
        <li class="head">积分商品管理</li>
		 <?php if($info['exchange']==1) { ?>
        <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/content/jf_goods_add">添加新商品</a></li>
        <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/content/jf_goods_list">商品列表</a></li>
        <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/category/lists/jf_goods">积分全额商品分类</a></li>
        <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/jf_brand/lists">积分全额品牌管理</a></li>
     
        <li class="head">订单管理</li>
       <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/jf_dingdan/lists">订单列表</a></li>
        <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/jf_dingdan/select">订单查询</a></li>
        <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/jf_dingdan/lists/notsend">未发货订单</a></li>
        <li class="head">其他</li>
        <li><a href="javascript:void(0);" src="<?php echo WEB_PATH; ?>/go/shaidan_admin/init">晒单查看</a></li> <?php } ?>
    </ul>
 
  
     <ul class="left_date" id="user">
	
     	<li class="head">用户管理</li>
		 <?php if($info['user']==1) { ?>
        <li><a href="javascript:void(0);" src="<?php echo WEB_PATH; ?>/member/member/lists">会员列表</a></li>
		 <li><a href="javascript:void(0);" src="<?php echo WEB_PATH; ?>/member/member/select">查找会员</a></li>
        <li><a href="javascript:void(0);" src="<?php echo WEB_PATH; ?>/member/member/insert">添加会员</a></li>
        <li><a href="javascript:void(0);" src="<?php echo WEB_PATH; ?>/member/member/config">会员配置</a></li>
		 <li><a href="javascript:void(0);" src="<?php echo WEB_PATH; ?>/member/member/member_fufen">会员福利配置</a></li>
        <li><a href="javascript:void(0);" src="<?php echo WEB_PATH; ?>/member/member/recharge">充值记录</a></li>
        <li><a href="javascript:void(0);" src="<?php echo WEB_PATH; ?>/member/member/pay_list">消费记录</a></li>
		 <li><a href="javascript:void(0);" src="<?php echo WEB_PATH; ?>/member/member/oplist">福分转让记录</a></li>
		<li><a href="javascript:void(0);" src="<?php echo WEB_PATH; ?>/member/member/member_group">会员组</a></li>
		<li><a href="javascript:void(0);" src="<?php echo WEB_PATH; ?>/member/member/commissions">佣金申请提现管理</a></li>
	
		<li><a href="javascript:void(0);" src="<?php echo WEB_PATH; ?>/member/member/prize_list">佣金记录</a></li>
  <?php } ?>   
    </ul>
     
         
        <ul class="left_date" id="content">
	
     	<li class="head">文章管理</li>
			<?php if($info['artic']==1) { ?>
        <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/content/article_add">添加文章</a></li>
        <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/category/lists/article">文章分类</a></li>
        <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/content/article_list">文章列表</a></li>
        <li class="head">单页管理</li>
        <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/category/addcate/danweb">添加单页</a></li>
        <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/category/lists/single">单页列表</a></li>	
        <li class="head">其他</li>
        <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/content/model">内容模型</a></li>
        <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/category/lists">栏目管理</a></li>
        <li class="head">模块管理</li>
        <li><a href="javascript:void(0);" src="<?php echo WEB_PATH; ?>/group/quanzi">圈子模块</a></li>  
        <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/link/lists">友情链接</a></li>
        <!--li><a href="javascript:void(0);" src="<?php echo WEB_PATH; ?>/admanage/admanage_admin">广告模块</a></li>
        <li><a href="javascript:void(0);" src="<?php echo WEB_PATH; ?>/vote/vote_admin/">投票模块</a></li-->   <?php } ?>
        </ul>	
      
		 
         <ul class="left_date" id="template">
		
     	<li class="head">界面管理</li>
		  <?php if($info['tp']==1) { ?>
        <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/ments/navigation">导航条管理</a></li>
        <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/slide">幻灯管理</a></li>
		<li><a href="javascript:void(0);" src="<?php echo WEB_PATH; ?>/mobile/wap">手机幻灯片</a></li>
        <li class="head">模板风格</li>
        <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/template">模板设置</a></li>       
		<li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/template/see">查看模板</a></li>      <?php } ?>
         </ul>	         
	
		 
        <ul class="left_date" id="yunapp">
	
          <li class="head">插件管理</li>
		  	<?php if($info['pl']==1) { ?>
       
		<li><a href="javascript:void(0);" src="<?php echo WEB_PATH; ?>/api/qqlogin/qq_set_config">QQ登陆</a></li>
        		<li><a href="javascript:void(0);" src="<?php echo WEB_PATH; ?>/api/qqlogin/wx_set_config">微信登陆</a></li>
		<li><a href="javascript:void(0);" src="<?php echo WEB_PATH; ?>/adminwei/fund/fundset">公益基金</a></li>

				<li><a href="javascript:void(0);" src="<?php echo G_ADMIN_PATH; ?>/shuashua_register/show">批量注册</a></li>
    <li><a href="javascript:void(0);" src="<?php echo WEB_PATH; ?>/shuashua/shuashua_p/show">自动购买</a></li>
     <li class="head">卡密（优惠券）管理</li>
        <li><a href="javascript:void(0);" src="<?php echo WEB_PATH; ?>/czk/vote_admin/">卡密（优惠券）管理</a></li>
		 <li class="head">奖品管理</li>
         <li><a href="javascript:void(0);" src="<?php echo G_MODULE_PATH; ?>/content/prize">手机摇红包管理</a></li>    <?php } ?>  
    </ul>
  
	 <div style="padding:30px 10px; color:#ccc">
     	<p>
        	 © 2014 YYGCMS<br>
			All Rights Reserved.
        </p>
     </div>

</div><!--left end-->
<div id="right">
	<div class="right_top">
    	<ul class="R_label" id="R_label">
        	当前位置: 系统设置 >  后台主页 >
        </ul>
    	<ul class="R_btn">
    	<a href="javascript:;" onClick="btn_iframef5();" class="system_button"><span>刷新框架</span></a>
		   <?php if($info['guanli']==1) { ?>
        <a href="javascript:;" onClick="btn_map('<?php echo G_MODULE_PATH; ?>/index/map');" class="system_button"><span>后台地图</span></a><?php } ?>  
        </ul>
    </div>
    <div class="right_left">
    	<a href="#" val="open" title="全屏" id="off_on">全屏</a>
    </div>
    <div id="right_iframe">
        
         <iframe id="iframe_src" name="iframe" class="iframe"
         frameborder="no" border="1" marginwidth="0" marginheight="0" 
         src="" 
         scrolling="auto" allowtransparency="yes" style="width:100%; height:100%">
         </iframe>
        
    </div>
</div><!--right end-->


</body>
</html>