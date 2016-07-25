var CBLFun = null;
$(document).ready(function() {
	var c = webpath;
	var a = webpath;
	var b = function() {
		var n = false;
		var i = $("#ulLimitGoodsList");
		var h = $("#divLoading");
		var e = 1;
		var d = 0;
		var l = new Object();
		var k = $("#divLotteryList");
		var o = $("#ulLotteryList");
		var g = 0;
		var f = function() {
			var p = 0;
			var q = null,
				s = null;
			var t = function() {
				GetJPData(webpath, "getLimitNewestLottery", "quantity=20&maxSecond=" + g, function(y) {
					if (y.code == 0) {
						g = y.maxSecond;
						var w = y.Rows;
						var u = w.length;
						var v = "";
						for (var x = (u - 1); x >= 0; x--) {
							v += '<li><span>恭喜</span><span class="user-name"><a href="'+webpath;
							v += w[x].userWeb + '" target="_blank" title="' + w[x].userName + '">' + w[x].userName + "</a></span>";
							v += "<span>参与<b>" + w[x].buyNum + '</b>人次获得</span><span class="comm-name"><a href="/lottery/';
							v += w[x].codeID + '.html" target="_blank" title=""' + w[x].goodsName + '">(第' + w[x].codePeriod + "云)" + w[x].goodsName + "</a></span></li>"
						}
						p = o.children("li").length + u;
						var z = p - 5;
						if (z > 0) {
							o.children("li:lt(" + z + ")").remove();
							p -= z
						}
						o.append(v);
						if (s == null) {
							k.show();
							s = setInterval(r, 3000)
						}
					}
				})
			};
			var r = function() {
				if (p > 1) {
					o.animate({
						"margin-top": "-20px"
					}, 800, function() {
						$(this).css("margin-top", "0").children().eq(0).appendTo($(this))
					})
				}
			};
			o.hover(function() {
				clearInterval(s);
				s = null
			}, function() {
				s = setInterval(r, 3000)
			});
			t()
		};
		var m = function() {
			return "FIdx=1&EIdx=50&isCount=" + e
		};
		var j = function() {
			var q = function() {
				h.show();
				GetJPData(webpath, "getLimitGoodsPage", m(), function(s) {
					if (s.code == 0) {
						var r = function() {
							if (e == 1) {
								d = s.totalCount;
								e = 0
							}
							h.hide();
							var t = s.listItems;
							var u = t.length;
							if (u > 0) {
								l.LimitGoodsData = t;
								p()
							}
						};
						if (n) {
							r()
						} else {
							Base.getScript(webpath+"/JS/GoodsComm.js?date=150602", function() {
								n = true;
								r()
							})
						}
					} else {
						h.html("抱歉，加载商品失败，请刷新重试！")
					}
				})
			};
			var p = function() {
				var z = l.LimitGoodsData;
				if (z != null && z != undefined) {
					var s = "";
					var x = "";
					var w = 0;
					var y = 0;
					var r = 0;
					var B = 0;
					var t = 0;
					i.show();
					var A = 235;
					for (var v = 0; v < z.length; v++) {
						s = "";
						x = "(第" + z[v].codePeriod + "云)&nbsp;" + z[v].goodsSName;
						w = parseInt(z[v].codeSales);
						y = parseInt(z[v].codeQuantity);
						r = parseInt(y - w);
						B = parseFloat(w) / y;
						t = parseInt(B * A);
						s += '<div class="productsCon" codeID="' + z[v].codeID + '" limitBuy="' + z[v].codeLimitBuy + '"><div class="proList ' + ((v + 1) % 4 == 0 ? "special" : "") + '"><ul><li class="list-pic"><a href="/products/' + z[v].goodsID + '.html" target="_blank" title="' + x + '"><img name="goodsImg" src="http://goodsimg.1yyg.com/GoodsPic/pic-200-200/' + z[v].goodsPic + '" /></a></li><li class="list-name"><a href="/products/' + z[v].goodsID + '.html" target="_blank" title="' + x + '">' + x + '</a></li><li class="list-value gray6">价值：￥' + formatFloat(z[v].codePrice) + "<span><i></i>限购<b>" + z[v].codeLimitBuy + '</b>人次</span></li><li class="g-progress"><dl class="m-progress"><dt><b style="width:' + (w == 0 ? 0 : (t == 0 ? 1 : t)) + 'px;"></b></dt><dd><span class="orange fl"><em>' + w + '</em>已参与</span><span class="fl gray6"><em>' + y + '</em>总需人次</span><span class="blue fr"><em>' + r + '</em>剩余</span></dd></dl></li><li name="buyBox" class="list-btn" limitBuy="' + z[v].codeLimitBuy + '"><a href="javascript:;" title="立即1元云购" class="u-imm">立即1元云购</a><a href="javascript:;" class="u-carts" title="加入到购物车"></a></li></ul></div></div>';
						var u = $(s);
						i.append(u);
						u.GoodsItemFun()
					}
					i.show()
				}
				h.hide()
			};
			this.initPage = function() {
				q()
			}
		};
		CBLFun = new j();
		CBLFun.initPage();
		f()
	};
	Base.getScript(a + "/JS/AjaxFun.js?v=141103", b)
});