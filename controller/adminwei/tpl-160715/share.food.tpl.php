<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>添加晒单</title>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/api-uploadify.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar-blue.css" type="text/css">
<script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/calendar/calendar.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/uploadify/jquery.uploadify-3.1.min.js"></script>

<script type="text/javascript">
var editurl=Array();
editurl['editurl']='<?php echo G_PLUGIN_PATH; ?>/ueditor/';
editurl['imageupurl']='<?php echo G_ADMIN_PATH; ?>/ueditor/upimage/';
editurl['imageManager']='<?php echo G_ADMIN_PATH; ?>/ueditor/imagemanager';
</script>
<script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo G_PLUGIN_PATH; ?>/ueditor/ueditor.all.min.js"></script>
<style>
	.bg{background:#fff url(<?php echo G_GLOBAL_STYLE; ?>/global/image/ruler.gif) repeat-x scroll 0 9px }
	.color_window_td a{ float:left; margin:0px 10px;}
</style>
</head>
<body>
<style>

.R-content .sd_lilie{display:block; width:780px; margin:10px 0 10px 0; float:left;}

.R-content .sd_lilie .sd_span{font-size:14px; float:left; display:block;width:70px;}

#sd_text{border:1px solid #ccc; width:450px; height:30px; padding:0 0 0 5px;}

#sd_textarea{width:500px; height:150px; border:1px solid #ccc; resize:none; padding:5px;}

#fileQueue{display: block; margin: 30px 0 0 0;}

#fileQueue div{ margin:5px 0 0 0;}

#sd_submit{padding:5px 10px; float:right; border-right:1px solid #666; border-bottom:1px solid #666;}

.button{ margin:5px 0 0 0; float:left;padding:3px 7px; float:left; border-right:1px solid #666; border-bottom:1px solid #666;}

.fileWarp{ width:710px;  float:left;}

.fileWarp ul li{ float:left;  margin-right:10px;}

.fileWarp ul li a{ display:block; height:25px; width:100px; text-align:center; background-color:#eee; color:#f60;line-height:25px;}

</style>


<script>

//上传文件

$(function(){

    $('#sd_file').uploadify({

    	'auto'     : false,

    	'removeTimeout' : 1,

        'swf'      : '<?php echo G_PLUGIN_PATH; ?>/uploadify/uploadify.swf',

        'uploader' : '<?php echo G_MODULE_PATH;?>/dingdan/singphotoup',

        'method'   : 'post',

		'buttonText' : '选择图片',

		'buttonImage': '<?php echo G_PLUGIN_PATH; ?>/uploadify/select.png',

		'width': 120,

		'height': 30,

        'multi'    : true,

		'uploadLimit' : 10,

		'queueID'         : 'fileQueue',

        'fileTypeDesc' : 'Image Files',

        'fileTypeExts' : '*.gif; *.jpg; *.png',

        'fileSizeLimit' : '500KB',



		'formData'        : {

			'uid': '{wc:$uid}',			

			'ushell':'{wc:$ushell}'

		},	

		'onUploadSuccess' : function(file, data, response){		

			$(".fileWarp ul").append(SetImgContent(data));

			SetUploadFile();

		}

    });

});

function sdUpload(){

		$('#sd_file').uploadify('settings','formData',{'typeCode':document.getElementById('id_file').value});

		$('#sd_file').uploadify('upload','*')

	}

//显示上传的图片

function SetImgContent(data){

	var sLi = "";

		sLi += '<li>';

		sLi += '<img src="<?php echo G_UPLOAD_PATH; ?>/' + data + '" width="100" height="100" />';

		sLi += '<input type="hidden" name="fileurl_tmp[]" value="' + data + '">';

		sLi += '<a href="javascript:;">删除</a>';

		sLi += '</li>';

	return sLi;

}



//删除上传元素DOM并清除目录文件

function SetUploadFile(){

	$(".fileWarp ul li").each(function(l_i){

		$(this).attr("id", "li_" + l_i);

	})

	$(".fileWarp ul li a").each(function(a_i){

		$(this).attr("rel", "li_" + a_i);

	}).click(function(){

		$.get(

			'<?php echo G_MODULE_PATH;?>/dingdan/singdel',

			{action:"del", filename:$(this).prev().val()},

			function(){}

		);

		$("#" + this.rel).remove();

	})

}

</script>



<div class="header lr10">
	
</div>
<div class="bk10"></div>
<div class="table_form lr10">
<form method="post" action="" >
	<table width="100%"  cellspacing="0" cellpadding="0">
	
        <tr>
			<td align="right" style="width:120px"><font color="red">*</font>晒单标题：</td>
			<td>
            <input  type="text" id="title"  name="title" onKeyUp="return gbcount(this,100,'texttitle');"  class="input-text wid400 bg">

            <span style="margin-left:10px">还能输入<b id="texttitle">100</b>个字符</span>

            </td>
		</tr>
		
     

		<tr>
        	<td height="300" style="width:120px"  align="right"><font color="red">*</font>内容详情：</td>
			<td><script name="content" id="myeditor" type="text/plain"></script>
            	<style>
				.content_attr {
					border: 1px solid #CCC;
					padding: 5px 8px;
					background: #FFC;
					margin-top: 6px;
					width:915px;
				}
				</style>
            
            </td>
		
			
		</tr>
		
			
		
		
		
 
        <tr height="60px">
			<td align="right" style="width:120px"></td>
			<td><input type="submit" name="submit" class="button" value="添加晒单" /></td>
			
			<td>
	     	<div class="sd_lilie">

			<span class="sd_span">晒图：</span>

			<div style="float:left; width:400px;">

				<input id="sd_file" type="file" name="upimg"/>				

				<div style="width:710px; float:left;"></div>

				<input type="hidden" name="imglist" id="id_file" />

				<input type="button"  class="button" onClick="sdUpload()" value="上传图片" />

				<div class="fileWarp"><div id="fileQueue"></div><ul></ul></div>			

			</div>

		</div>
</td>
		</tr>
	</table>
</form>
</div>
 <span id="title_colorpanel" style="position:absolute; left:568px; top:155px" class="colorpanel"></span>
<script type="text/javascript">
    //实例化编辑器
    var ue = UE.getEditor('myeditor');

    function getContent() {
        var arr = [];
        arr.push( "使用editor.getContent()方法可以获得编辑器的内容" );
        arr.push( "内容为：" );
        arr.push(  UE.getEditor('myeditor').getContent() );
        alert( arr.join( "\n" ) );
    }
    function hasContent() {
        var arr = [];
        arr.push( "使用editor.hasContents()方法判断编辑器里是否有内容" );
        arr.push( "判断结果为：" );
        arr.push(  UE.getEditor('myeditor').hasContents() );
        alert( arr.join( "\n" ) );
    }

	var info=new Array();
    function gbcount(message,maxlen,id){

		if(!info[id]){
			info[id]=document.getElementById(id);
		}
        var lenE = message.value.length;
        var lenC = 0;
        var enter = message.value.match(/\r/g);
        var CJK = message.value.match(/[^\x00-\xff]/g);//计算中文
        if (CJK != null) lenC += CJK.length;
        if (enter != null) lenC -= enter.length;
		var lenZ=lenE+lenC;
		if(lenZ > maxlen){
			info[id].innerHTML=''+0+'';
			return false;
		}
		info[id].innerHTML=''+(maxlen-lenZ)+'';
    }

function set_title_color(color) {
	$('#title2').css('color',color);
	$('#title_style_color').val(color);
}
function set_title_bold(){
	if($('#title_style_bold').val()=='bold'){
		$('#title_style_bold').val('');
		$('#title2').css('font-weight','');
	}else{
		$('#title2').css('font-weight','bold');
		$('#title_style_bold').val('bold');
	}
}

$(".radioc").change(function(){
	var v = $("input[name='goods_type']:checked").val();
	if(v == 1){
		$("#limit_n").hide();
		$("#limit_t").show();
	}else{
		$("#limit_n").show();
		$("#limit_t").hide();
	}
});

//API JS
//window.parent.api_off_on_open('open');
</script>



</body>
</html>