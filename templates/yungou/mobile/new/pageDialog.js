(function(d){d.PageDialog=function(g,p){var j={W:255,H:45,obj:null,oL:0,oT:0,autoClose:true,autoTime:2000,ready:function(){},submit:function(){}};var h={obj:null,oL:0,oT:0,autoClose:true,autoTime:2000,ready:function(){},submit:function(){}};p=p||h;d.extend(j,p);var f=j.autoClose;var m=function(t){var q=t.get(0);q.addEventListener("touchstart",s,false);function s(v){if(v.touches.length===1){q.addEventListener("touchmove",r,false);q.addEventListener("touchend",u,false)}}function r(v){v.preventDefault()}function u(v){q.removeEventListener("touchmove",r,false);q.removeEventListener("touchend",u,false)}};var o=d("#pageDialogBG");if(!f&&o.length==0){o=d('<div id="pageDialogBG" class="pageDialogBG"></div>');o.appendTo("body");m(o)}var l=d("#pageDialog");if(l.length==0){l=d('<div id="pageDialog" class="pageDialog" />');l.appendTo("body");if(!f){m(l)}}var e=d(window);if(j.obj!=null){if(j.obj.length<1){j.obj=null}}l.css({width:j.W+"px",height:j.H+"px"});l.html(g);var n=function(){var q,s,t;if(j.obj!=null){var r=j.obj.offset();q=r.left+j.oL;s=r.top+j.obj.height()+j.oT;t="absolute"}else{q=(e.width()-j.W)/2;s=(e.height()-j.H)/2;t="fixed"}l.css({position:t,left:q,top:s})};n();e.resize(n);var k=function(){if(f){l.fadeOut("slow")}else{l.hide();o.hide()}};var i=function(){j.submit();k()};if(f){l.fadeIn(500)}else{l.show();o.show()}l.ready=j.ready();if(f){window.setTimeout(i,j.autoTime)}this.close=function(){i()};this.cancel=function(){k()}};d.PageDialog.ok=function(f,e){d.PageDialog('<div class="Prompt">'+f+"</div>",{submit:(e===undefined?function(){}:e)})};d.PageDialog.fail=function(h,g,i,e,f){d.PageDialog('<div class="Prompt">'+h+"</div>",{obj:g,oT:i,oL:e,autoTime:2000,submit:(f===undefined?function(){}:f)})};var b=0;d.PageDialog.confirm=function(j,f,e){var h=null;var g='<div class="clearfix m-round u-tipsEject"><div class="u-tips-txt">'+j+'</div><div class="u-Btn"><div class="u-Btn-li"><a href="javascript:;" id="btnMsgCancel" class="z-CloseBtn">取消</a></div><div class="u-Btn-li"><a id="btnMsgOK" href="javascript:;" class="z-DefineBtn">确定</a></div></div></div>';var i=function(){d("#btnMsgCancel").click(function(){h.cancel()});d("#btnMsgOK").click(function(){h.close()})};b++;h=new d.PageDialog(g,{H:(e===undefined?126:e),autoClose:false,ready:i,submit:f})};d.PageDialog.fail1=function(h,g,f){var i=c(g);var e=a(g);d.PageDialog('<div class="Prompt">'+h+"</div>",{obj:g,oT:i,oL:e,autoTime:2000,submit:(f===undefined?function(){}:f)})};var a=function(h){var e=d(h).width()-255;var g=e>0?e:e*-1;var f=g/2;return f};var c=function(e){return(d(e).height()*2+20)*-1}})(jQuery);