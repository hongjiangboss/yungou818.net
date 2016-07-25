function setorder(id){
	if(!id){
		$.PageDialog.fail('数据出错,刷新重试');
	}
	$.post(Gobal.Webpath+'mobile/home/set_record/',{oid:id},function(ret){
		if(ret == 1){
			location = location;
		}else{
			$.PageDialog.fail(ret);
		}
	});
};
$(function() {
    var a = function() {
        var b = $("#divOrderLoading");
        var h = $("#btnLoadMore");
        var f = 0;
        var i = 10;
        var c = {
            FIdx: 0,
            EIdx: i,
            isCount: 1
        };
        var g = null;
        var e = false;
        var d = function() {
            var j = function() {
                return "/" + c.FIdx + "/" + c.EIdx + "/" + c.isCount
            };
            
            var k = function() {
                h.hide();
                b.show();
			    GetJPData(Gobal.Webpath, "shopajax", "getUserOrderList"+j(),
                function(p) {
                    if (p.code == 0) {
                        if (c.isCount == 1) {
                            c.isCount = 0;
                            f = p.count
                        }
                        var o = p.listItems;
                        var n = o.length;
                        var m = "";

                        for (var l = 0; l < n; l++) {
                            m += '<li><a class="fl z-Limg" href="'+Gobal.Webpath+'mobile/mobile/item/' + o[l].shopid +'"><img src="' + Gobal.LoadPic + '" src2="'+Gobal.imgpath+'/uploads/' + o[l].thumb + '" border=0 alt=""/></a><div class="u-gds-r gray9"><p class="z-gds-tt"><a href="'+Gobal.Webpath+'mobile/mobile/item/' + o[l].shopid +'" class="gray6">(第' + o[l].qishu + "期)" + o[l].shopname + '</a></p><p>幸运码：<em class="purple">' + o[l].q_user_code + "</em></p><p>揭晓时间：" + o[l].q_end_time + "</p>";
                            var q = parseInt(o[l].orderState);
                            if(o[l].company.length < 1 && o[l].status == '已付款,未发货,未完成'){
							    m += '<a href="'+Gobal.Webpath+'mobile/home/address/"  class="z-gds-btn">'+o[l].status+'</a>'
                            }
                            if(o[l].status == '已付款,已发货,已完成'){
                                m += '<a href="javascript:;"  class="z-gds-btn">'+o[l].status+'</a>'
                            }
                            if(o[l].status == '已付款,已发货,待收货'){
                                m += '<a href="javascript:;" onclick="setorder('+o[l].shopid+')"  class="z-gds-btn">确认收货</a>'
                            }
                            if(o[l].company.length > 1){
                                m += '<p>'+o[l].company+'：<em class="purple">'+o[l].company_code+'</em></p>'
                            }
                            m += '</div><b class="z-arrow"></b></li>'
                        }
                        if (c.FIdx > 0) {
                            b.prev().removeClass("bornone")
                        }
                        b.before(m).prev().addClass("bornone");
                        if (c.EIdx < f) {
                            e = false;
                            h.show()
                        }
                        loadImgFun()
                    } else {
                        if (p.code == 10) {
                            location.reload()
                        } else {
                            if (c.FIdx == 0) {
                                b.before(Gobal.NoneHtml)
                            }
                        }
                    }
                    b.hide()
                })
            };
            this.getInitPage = function() {
                k()
            };
            this.getNextPage = function() {
                c.FIdx += i;
                c.EIdx += i;
                k()
            }
        };
        h.click(function() {
            if (!e) {
                e = true;
                g.getNextPage()
            }
        }).show();
        g = new d();
        g.getInitPage()
    };
    Base.getScript(Gobal.Skin + "/mobile/js/Comm.js", a);
    Base.getScript(Gobal.Skin+"/mobile/js/pageDialog.js");
});