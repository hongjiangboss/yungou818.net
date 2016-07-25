<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>YYGCMS后台首页</title>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">









<link id=cssfile2 rel=stylesheet type=text/css 
href="<?php echo G_WEB_PATH;?>/data/plugin/style/images/skin_0.css">







<meta name=GENERATOR content="MSHTML 8.00.6001.23636"></head>
<body>
<div id=append_parent></div>
<div id=ajaxwaitid></div>

<style>
	 body{ background-color:#fefeff; font:12px/1.5 arial,宋体b8b\4f53,sans-serif;}
	.width30{ width:25%; font-size:12px; border-radius:5px 2px 20px 2px;  }
	
	.title{ font-size:15px; font-weight:bold; color:#444; line-height:30px; border-bottom:1px solid #ccc;}
	.div-news{ height:50px; background-color:#fff}
	.div-user span{ display:block; font-size:12px; font: 12px/1.5 arial,宋体b8b\4f53,sans-serif; line-height:20px; color:#999}
	.div-user{ background-color:#fff; padding:20px;width:30%;float:left;  border-bottom:1px solid #eee }
	.div-button{ float:left;background-color:#fff; float:left; padding:20px; margin:0 10px; width:55%;border-radius:5px 5px 5px 5px;}
	.div-button ul li{ float:left; margin:0px 25px;}
	.div-button li a{  cursor:pointer; text-decoration:none}
	.div-button li span{ display:block; width:60px; text-align:center; line-height:32px;} 
	
	.div-system{background-color:#fff; float:left; padding:20px; margin:0 10px;border-right:1px solid #eee}
	.div-webinfo{background-color:#fff; float:left; padding:20px; margin:0 10px; width:27%;border-right:1px solid #eee }
	.div-about{background-color:#fff; float:left; padding:20px; margin:0 10px; overflow:hidden}
	
	
	
	 li{font:12px/1.5 arial,宋体b8b\4f53,sans-serif;}
	.div-system ul li{height:30px; line-height:30px;color:#333;border-bottom:1px dotted #ddd;}
	.div-system ul li i{width:90px;height:30px; line-height:30px; display:inline-block; color:#666;}
	
		
	.div-about ul li{height:30px; line-height:30px;color:#333;border-bottom:1px dotted #ddd;}
	.div-about ul li i{width:90px;height:30px; line-height:30px; display:inline-block; color:#666;}
	
	.div-webinfo ul li{height:30px; line-height:30px;color:#333;border-bottom:1px dotted #ddd;}
	.div-webinfo ul li i{width:90px;height:30px; line-height:30px; display:inline-block; color:#666;}
	
	.CMS_message{background-color: #eef3f7;border: 1px solid #d5dfe8; height:20px; padding:5px 0px; overflow:hidden}
	.CMS_message li{ text-indent:50px; height:25px; line-height:25px; color:#339A71;font-size:12px; font-weight:bold;}
	
</style>
</head>
<body>

<div class="bk30"></div>
<div class="div-user lr10">
	<h1>Hello, <font color="#4c95b6"><?php echo $info['username'] ;?></font><h1>
    <span>所属角色: <?php if($info['mid']==0)echo "超级管理员"; ?><?php if($info['mid']==1)echo "普通管理员"; ?></span>
    <span>上次登录时间: <?php echo date("Y-m-d H:i:s",$info['logintime']); ?></span>
    <span>上次登录IP: <?php echo $info['loginip']; ?></span>
</div>
<div class="div-button">
<div class="bk15"></div>
	<ul>
	  <?php if($info['artic']==1) { ?>
    	<li><a  href="<?php echo G_MODULE_PATH; ?>/content/article_add"><img src="<?php echo G_GLOBAL_STYLE; ?>/global/image/btn_60_60_t.png"><span>添加文章</span></a></li>
        <?php } ?>
		
		<?php if($info['shop']==1) { ?>
	   <li><a href="<?php echo G_MODULE_PATH; ?>/content/goods_add"><img src="<?php echo G_GLOBAL_STYLE; ?>/global/image/btn_60_60_g.png"><span>添加商品</span></a></li>
         <?php } ?>
		 
		 
		<?php if($info['user']==1) { ?>
		<li><a href="<?php echo WEB_PATH; ?>/member/member/lists"><img src="<?php echo G_GLOBAL_STYLE; ?>/global/image/btn_60_60_m.png"><span>会员管理</span></a></li>
        <?php } ?>
	   
	    <?php if($info['xitong']==1) { ?>
	   <li><a href="<?php echo G_MODULE_PATH; ?>/setting/webcfg"><img src="<?php echo G_GLOBAL_STYLE; ?>/global/image/btn_60_60_s.png"><span>系统设置</span></a></li>
        <?php } ?>
	  
	  
	  <li><a href="<?php echo G_WEB_PATH; ?>"><img src="<?php echo G_GLOBAL_STYLE; ?>/global/image/btn_60_60_i.png"><span>网站首页</span></a></li>
    </ul>
</div><div class="bk10"></div>
<div class=page>
<div class=fixed-empty></div>
<div class=info-panel>

     <?php if($info['xitong']==1) { ?>

<dl class=circle>
  <dt>
  <div class=ico><i></i></div>
  <h3>系统设置</h3>
  <h5>　</h5></dt>
  <dd>
  <ul>
        <li class="w20pre normal"><a 
    href="<?php echo G_MODULE_PATH; ?>/setting/webcfg">SEO设置</a></li>
    <li class="w20pre none"><a 
    href="<?php echo G_MODULE_PATH; ?>/setting/config">基本设置<sub><em 
    id=statistics_store_bind_class_applay>0</em></sub></a></li>
    <li class="w20pre none"><a 
    href="<?php echo G_MODULE_PATH; ?>/setting/email">邮箱配置<sub><em 
    id=statistics_store_reopen_applay>0</em></sub></a></li>

    <li class="w20pre none"><a 
    href="<?php echo G_MODULE_PATH; ?>/setting/mobile">短信配置<sub><em 
    id=statistics_store_expired>0</em></sub></a></li>
    <li class="w20pre none"><a 
    href="<?php echo WEB_PATH; ?>/pay/pay/pay_list">支付方式<sub><em 
    id=statistics_store_expire>0</em></sub></a></li></ul></dd></dl>
    </ul></dd></dl>

 <?php } ?>

 <?php if($info['yuny']==1) { ?>
<dl class=microshop>
  <dt>
  <div class=ico><i></i></div>
  <h3>站长运营</h3>
  <h5>　</h5></dt>
  <dd>
  <ul>
      <li class="w33pre normal"><a 
    href="<?php echo G_ADMIN_PATH; ?>/yunwei/websitemap">站点地图</a></li>
    <li class="w33pre none"><a 
    href="<?php echo G_ADMIN_PATH; ?>/yunwei/websubmit">网站提交<sub><em 
    id=statistics_store_bind_class_applay>0</em></sub></a></li>
    <li class="w33pre none"><a 
    href="<?php echo G_ADMIN_PATH; ?>/yunwei/webtongji">站长统计<sub><em 
    id=statistics_store_reopen_applay>0</em></sub></a></li>

    
    </ul></dd></dl>
 <?php } ?>
 

  <?php if($info['guanli']==1) { ?>
   <dl class=goods>
  <dt>
  <div class=ico><i></i><sub title=管理员管理><span><em 
  id=statistics_member></em></span></sub></div>
  <h3>管理员管理</h3>
  <h5>　　</h5>
 
	</dt>

  <dd>
  <ul>
    <li class="w33pre normal"><a 
    href="<?php echo G_MODULE_PATH; ?>/user/lists">管理员管理</a></li>
    <li class="w33pre none"><a 
    href="<?php echo G_MODULE_PATH; ?>/user/reg">添加管理员<sub><em 
    id=statistics_store_bind_class_applay>0</em></sub></a></li>
    <li class="w33pre none"><a 
    href="<?php echo G_MODULE_PATH; ?>/user/edit/<?php echo $info['uid']; ?>">修改密码<sub><em 
    id=statistics_store_reopen_applay>0</em></sub></a></li>

    
    
    </ul></dd></dl>

 <?php } ?>
	
	
	  <?php if($info['artic']==1) { ?>
<dl class=shop>
  <dt>
  <div class=ico><i></i><sub title=新增店铺数><span><em 
  id=statistics_store></em></span></sub></div>
  
  <h3>文章</h3>
  <h5>　</h5></dt>
  <dd>
  <ul>
    <li class="w25pre normal"><a 
    href="<?php echo G_MODULE_PATH; ?>/content/article_add">添加文章</a></li>
    <li class="w25pre none"><a 
    href="<?php echo G_MODULE_PATH; ?>/content/article_list">文章列表<sub><em 
    id=statistics_product_verify>0</em></sub></a></li>
    <li class="w25pre none"><a 
    href="<?php echo G_MODULE_PATH; ?>/category/lists/article">文章分类<sub><em 
    id=statistics_inform_list>0</em></sub></a></li>
	<li class="w25pre none"><a 
    href="<?php echo G_MODULE_PATH; ?>/category/lists/single">单页列表<sub><em 
    id=statistics_inform_list>0</em></sub></a></li>
    </ul></dd></dl>

   <?php } ?>
   
   
   
    <?php if($info['shop']==1) { ?>  
    
<dl class=trade>
  <dt>
  <div class=ico><i></i><sub title=商品管理><span><em 
  id=statistics_order></em></span></sub></div>
  
  <h3>商品</h3>
  <h5>今日新增商品:<?php echo count($tj_shoplist_new); ?></h5>
  <dd>
  <ul>
    <li class="w20pre none"><a 
    href="<?php echo G_MODULE_PATH; ?>/content/goods_add">添加商品<sub><em 
    id=statistics_store_joinin>0</em></sub></a></li>
    <li class="w20pre none"><a 
    href="<?php echo G_MODULE_PATH; ?>/content/goods_list">商品列表<sub><em 
    id=statistics_store_bind_class_applay>0</em></sub></a></li>
    <li class="w20pre none"><a 
    href="<?php echo G_MODULE_PATH; ?>/category/lists/goods">商品分类<sub><em 
    id=statistics_store_reopen_applay>0</em></sub></a></li>
    <li class="w20pre none"><a 
    href="<?php echo G_MODULE_PATH; ?>/brand/lists">品牌管理<sub><em 
    id=statistics_store_expired>0</em></sub></a></li>
    <li class="w20pre none"><a 
    href="<?php echo G_MODULE_PATH; ?>/brand/insert">添加品牌<sub><em 
    id=statistics_store_expire>0</em></sub></a></li></ul></dd></dl>
    
     <?php } ?>
	 
	 
	 
	 
	  <?php if($info['exchange']==1) { ?>
	
    <dl class=trade>
  <dt>
  <div class=ico><i></i><sub title=直购商品><span><em 
  id=statistics_order></em></span></sub></div>
  
  <h3>直购商品</h3>
  <dd>
  <ul>
    <li class="w20pre none"><a 
    href="<?php echo G_MODULE_PATH; ?>/content/jf_goods_add">添加直购商品<sub><em 
    id=statistics_store_joinin>0</em></sub></a></li>
    <li class="w20pre none"><a 
    href="<?php echo G_MODULE_PATH; ?>/content/jf_goods_list">直购商品列表<sub><em 
    id=statistics_store_bind_class_applay>0</em></sub></a></li>
    <li class="w20pre none"><a 
    href="<?php echo G_MODULE_PATH; ?>/category/lists/jf_goods">直购商品分类<sub><em 
    id=statistics_store_reopen_applay>0</em></sub></a></li>
    <li class="w20pre none"><a 
    href="<?php echo G_MODULE_PATH; ?>/jf_brand/lists">直购品牌管理<sub><em 
    id=statistics_store_expired>0</em></sub></a></li>
    <li class="w20pre none"><a 
    href="<?php echo G_MODULE_PATH; ?>/jf_brand/insert">添加直购品牌<sub><em 
    id=statistics_store_expire>0</em></sub></a></li></ul></dd></dl>
     <?php } ?>
    
    
	<?php if($info['user']==1) { ?>
	
    <dl class=member>
  <dt>
  <div class=ico><i></i><sub title=会员总数><span><em 
  id=statistics_member></em></span></sub></div>
  <h3>会员</h3>
  <h5>　　</h5>
 
	</dt>
  <dd>
  <ul>
    <li class="w20pre normal"><a 
    href="<?php echo WEB_PATH; ?>/member/member/lists">会员列表</a></li>
    <li class="w20pre none"><a 
    href="<?php echo WEB_PATH; ?>/member/member/pay_list">消费记录<sub><em 
    id=statistics_store_bind_class_applay>0</em></sub></a></li>
    <li class="w20pre none"><a 
    href="<?php echo WEB_PATH; ?>/member/member/oplist">福分转让<sub><em 
    id=statistics_store_reopen_applay>0</em></sub></a></li>
    <li class="w20pre none"><a 
    href="<?php echo WEB_PATH; ?>/member/member/member_group">会员组<sub><em 
    id=statistics_store_expired>0</em></sub></a></li>
    <li class="w20pre none"><a 
    href="<?php echo WEB_PATH; ?>/member/member/commissions">佣金管理<sub><em 
    id=statistics_store_expire>0</em></sub></a></li></ul></dd></dl>
    
     <?php } ?>
	 
	 
	 
    
     <?php if($info['tp']==1) { ?>
<dl class=operation>
  <dt>
  <div class=ico><i></i></div>
  <h3>界面管理</h3>
  <h5>　</h5></dt>
  <dd>
  <ul>
    <li class="w20pre normal"><a 
    href="<?php echo G_MODULE_PATH; ?>/ments/navigation">导航管理</a></li>
    <li class="w20pre none"><a 
    href="<?php echo G_MODULE_PATH; ?>/slide">幻灯管理<sub><em 
    id=statistics_store_bind_class_applay>0</em></sub></a></li>
    <li class="w20pre none"><a 
    href="<?php echo WEB_PATH; ?>/mobile/wap">手机幻灯片<sub><em 
    id=statistics_store_reopen_applay>0</em></sub></a></li>
    <li class="w20pre none"><a 
    href="<?php echo G_MODULE_PATH; ?>/template">模板设置<sub><em 
    id=statistics_store_expired>0</em></sub></a></li>
    <li class="w20pre none"><a 
    href="<?php echo G_MODULE_PATH; ?>/template/see">查看模板<sub><em 
    id=statistics_store_expire>0</em></sub></a></li></ul></dd></dl>
    
    <?php } ?>
	
	 <?php if($info['pl']==1) { ?>
      
<dl class=cms>
  <dt>
  <div class=ico><i></i></div>
  <h3>插件管理</h3>
  <h5>　</h5></dt>
  <dd>
  <ul>
     <li class="w20pre normal"><a 
    href="<?php echo WEB_PATH; ?>/api/qqlogin/qq_set_config">QQ登陆</a></li>
    <li class="w20pre none"><a 
    href="<?php echo WEB_PATH; ?>/adminwei/fund/fundset">公益基金<sub><em 
    id=statistics_store_bind_class_applay>0</em></sub></a></li>
    <li class="w20pre none"><a 
    href="<?php echo G_ADMIN_PATH; ?>/shuashua_register/show">批量注册<sub><em 
    id=statistics_store_reopen_applay>0</em></sub></a></li>
    <li class="w20pre none"><a 
    href="<?php echo WEB_PATH; ?>/shuashua/shuashua_p/show">自动购买<sub><em 
    id=statistics_store_expired>0</em></sub></a></li>
    <li class="w20pre none"><a 
    href="<?php echo WEB_PATH; ?>/czk/vote_admin/">卡密（优惠券）<sub><em 
    id=statistics_store_expire>0</em></sub></a></li></ul></dd></dl>
 <?php } ?>
<div class=clear></div>
<div class=system-info></div></div>



<script type="text/javascript">
(function(A){
   function _ROLL(obj){
      this.ele = document.getElementById(obj);
	  this.interval = false;
	  this.currentNode = 0;
	  this.passNode = 0;
	  this.speed = 100;
	  this.childs = _childs(this.ele);
	  this.childHeight = parseInt(_style(this.childs[0])['height']);
	      addEvent(this.ele,'mouseover',function(){
				                               window._dataYR.pause();
											});
		  addEvent(this.ele,'mouseout',function(){
				                               window._dataYR.start(_dataYR.speed);
											});
   }
   function _style(obj){
     return obj.currentStyle || document.defaultView.getComputedStyle(obj,null);
   }
   function _childs(obj){
	  var childs = [];
	  for(var i=0;i<obj.childNodes.length;i++){
		 var _this = obj.childNodes[i];
		 if(_this.nodeType===1){
			childs.push(_this);
		 }
	  }   
	  return childs;
   }
	function addEvent(elem,evt,func){
	   if(-[1,]){
		   elem.addEventListener(evt,func,false);   
	   }else{
		   elem.attachEvent('on'+evt,func);
	   };
	}
	function innerest(elem){
      var c = elem;
	  while(c.childNodes.item(0).nodeType==1){
	      c = c.childNodes.item(0);
	  }
	  return c;
	}
   _ROLL.prototype = {
      start:function(s,v){
	          var _this = this;
			  
			  _this.hh=v;
			  _this.speed = s || 100;//速度
		      _this.interval = setInterval(function(){
									
						    _this.ele.scrollTop += 1;							
							if(_this.ele.scrollTop==_this.hh){								
								//clearInterval(_this.interval);
							}
							
							_this.passNode++;
							if(_this.passNode%_this.childHeight==0){
								  var o = _this.childs[_this.currentNode] || _this.childs[0];
								  _this.currentNode<(_this.childs.length-1)?_this.currentNode++:_this.currentNode=0;
								  _this.passNode = 0;
								  _this.ele.scrollTop = 0;
								  _this.ele.appendChild(o);
							}
						  },_this.speed);
	  },
	
	  pause:function(){
		 var _this = this;
	     clearInterval(_this.interval);
	  }
   }
    A.marqueen = function(obj){A._dataYR = new _ROLL(obj); return A._dataYR;}
})(window);

marqueen('roll').start(50,30);
</script>

<div style="overflow:hidden">
<!------------>
    <div class="div-system width30">
        <div class="title">系统信息</div>
        	<div class="bk10"></div>
            <ul>        
                <li><i>操作系统: </i><?php echo $SysInfo['os'];?></li>
                <li><i>服务器版本: </i><?php echo $SysInfo['web_server'];?></li>
                <li><i>PHP版本: </i><?php echo $SysInfo['phpv'];?></li>
                <li><i>MYSQL版本: </i><?php echo $SysInfo['MysqlVersion'];?></li>
                <li><i>上传限制: </i><?php echo $SysInfo['fileupload'];?></li>
                <li><i>时区: </i><?php echo $SysInfo['timezone'];?></li>
                <li><i>GD库: </i><?php echo showResult('imageline');?></li>
                <li><i>POST限制: </i><?php echo get_cfg_var('post_max_size'); ?></li>
                <li><i>脚本超时时间: </i><?php echo ini_get('max_execution_time').'秒'; ?></li>
				<li><i>set_time_limit: </i><?php echo showResult('set_time_limit'); ?></li>
				<li><i>fsockopen: </i><?php echo showResult('fsockopen'); ?></li>
                <li style="border-bottom:none;"><i>ZEND支持: </i><?php echo showResult('zend_version'); ?> </li>
      
            </ul>      
    </div>
	<?php
	$tj_category=$this->db->GetList("SELECT cateid FROM `@#_category` WHERE `model` = '1'");
	$tj_brand=$this->db->GetList("SELECT id FROM `@#_brand`");
	$tj_article=$this->db->GetList("SELECT * FROM `@#_article`");
	$tj_shoplist=$this->db->GetList("SELECT id FROM `@#_shoplist`");	
	$time=time();
	$tj_shoplist_xsjx=$this->db->GetList("SELECT id FROM `@#_shoplist` where `xsjx_time`>'$time'");
	$tj_member=$this->db->GetList("SELECT uid FROM `@#_member`");
	
	$tm=time()-24*3600;
	$tj_member_new=$this->db->GetList("SELECT uid FROM `@#_member` where `time`>'$tm' ");
	$tj_shoplist_new=$this->db->GetList("SELECT id FROM `@#_shoplist` where `time`>'$tm' ");
	$tj_member_account=$this->db->GetList("SELECT money FROM `@#_member_account` where `pay`='账户' and `type`=1 and `time`>'$tm'");
	$today_money=0;
	foreach ($tj_member_account as $account){
		$today_money=$account['money']+$today_money;
	}
	?>
    <div class="div-webinfo width30">
        <div class="title">网站信息统计</div>
        <div class="bk10"></div>
        <ul>
           <li><i>栏目:</i><?php echo count($tj_category); ?></li>
           <li><i>品牌:</i><?php echo count($tj_brand); ?></li>
           <li><i>文章:</i><?php echo count($tj_article); ?></li>
           <li><i>商品数量:</i><?php echo count($tj_shoplist); ?></li>
           <li><i>限时揭晓:</i><?php echo count($tj_shoplist_xsjx); ?></li>
           <li style="border-bottom:none;"><i>会员人数:</i><?php echo count($tj_member); ?></li>
           <li class="bk30"></li>
           <li><i>今日新增会员:</i><?php echo count($tj_member_new); ?></li>
           <li><i>今日新增商品:</i><?php echo count($tj_shoplist_new); ?></li>
           <li style="border-bottom:none;"><i>今日账户收入:</i><?php echo $today_money; ?></li>
        </ul>
    </div>
    
    <div class="div-about width30">
        <div class="title">关于我们</div>
        <div class="bk10"></div>
        <ul>
        	<li><i>程序版本:</i><?php echo $versions['version']; ?><font color="#f60">【YYGCMS】</font></li>
			<li><i>更新时间:</i><?php echo $versions['release']; ?></li>
            <li><i>程序提供:</i>区域科技</li>
           
            <li><i>官方微博:</i><a href="http://weibo.com/u/5239768948" target="_black" style="color:#0f0">关注官方微博</a></li>        
        </ul>
            <p style="color:#666; padding:20px;font: 12px/1.5 tahoma,arial,宋体b8b\4f53,sans-serif;">
            	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;一元云购商城源码系统是一款专为一元购类网站设计的CMS系统,
                具有圈子,限时揭晓,晒单等功能。
            </p> 
    </div>
<!------------>
</div>
</body>
</html> 
