/**
 * Created by pan.wang on 2015-06-19.
 */

$.extend($.fn,{
    fnTimeCountDown:function(d){
        this.each(function(){
            var $this = $(this);
            var o = {
                hm: $this.find(".hm"),
                sec: $this.find(".sec"),
                mini: $this.find(".mini"),
                hour: $this.find(".hour"),
                day: $this.find(".day"),
                month:$this.find(".month"),
                year: $this.find(".year")
            };
            var f = {
                haomiao: function(n){
                    if(n < 10)return "0" + n.toString();
                    if(n < 100)return "" + n.toString();
                    if(n >= 100)return n.toString().substring(0,2);
                    return n.toString();
                },
                zero: function(n){
                    var _n = parseInt(n, 10);//解析字符串,返回整数
                    if(_n > 0){
                        if(_n <= 9){
                            _n = "0" + _n
                        }
                        return String(_n);
                    }else{
                        return "00";
                    }
                },
                dv: function(){
                    //d = d || Date.UTC(2050, 0, 1); //如果未定义时间，则我们设定倒计时日期是2050年1月1日
                    var _d = $this.data("end") || d;
                    var now = new Date(),
                        endDate = new Date(_d);
                    //现在将来秒差值
                    //alert(future.getTimezoneOffset());
                    var dur = (endDate - now.getTime()) / 1000 , mss = endDate - now.getTime() ,pms = {
                        hm:"00",
                        sec: "00",
                        mini: "00",
                        hour: "00",
                        day: "00",
                        month: "00",
                        year: "0"
                    };
                    if(mss > 0){
                        pms.hm = f.haomiao(mss % 1000);
                        pms.sec = f.zero(dur % 60);
                        pms.mini = Math.floor((dur / 60)) > 0? f.zero(Math.floor((dur / 60)) % 60) : "00";
                        pms.hour = Math.floor((dur / 3600)) > 0? f.zero(Math.floor((dur / 3600)) % 24) : "00";
                        pms.day = Math.floor((dur / 86400)) > 0? f.zero(Math.floor((dur / 86400)) % 30) : "00";
                        //月份，以实际平均每月秒数计算
                        pms.month = Math.floor((dur / 2629744)) > 0? f.zero(Math.floor((dur / 2629744)) % 12) : "00";
                        //年份，按按回归年365天5时48分46秒算
                        pms.year = Math.floor((dur / 31556926)) > 0? Math.floor((dur / 31556926)) : "0";
                    }else{
                        pms.year=pms.month=pms.day=pms.hour=pms.mini=pms.sec="00";
                        pms.hm = "00";
                        //alert('结束了');
                        return;
                    }
                    return pms;
                },
                ui: function(){
                    if(o.hm){
                        o.hm.html(f.dv().hm);
                    }
                    if(o.sec){
                        o.sec.html(f.dv().sec);
                    }
                    if(o.mini){
                        o.mini.html(f.dv().mini);
                    }
                    if(o.hour){
                        o.hour.html(f.dv().hour);
                    }
                    if(o.day){
                        o.day.html(f.dv().day);
                    }
                    if(o.month){
                        o.month.html(f.dv().month);
                    }
                    if(o.year){
                        o.year.html(f.dv().year);
                    }
                    setTimeout(f.ui, 1);
                }
            };
            f.ui();
        });
    }
});

Date.prototype.DateAdd =function(strInterval, Number) {
    var dtTmp = this;
    switch (strInterval) {
        case 's' :return new Date(Date.parse(dtTmp) + (1000 * Number));
        case 'n' :return new Date(Date.parse(dtTmp) + (60000 * Number));
        case 'h' :return new Date(Date.parse(dtTmp) + (3600000 * Number));
        case 'd' :return new Date(Date.parse(dtTmp) + (86400000 * Number));
        case 'w' :return new Date(Date.parse(dtTmp) + ((86400000 * 7) * Number));
        case 'q' :return new Date(dtTmp.getFullYear(), (dtTmp.getMonth()) + Number*3, dtTmp.getDate(), dtTmp.getHours(), dtTmp.getMinutes(), dtTmp.getSeconds());
        case 'm' :return new Date(dtTmp.getFullYear(), (dtTmp.getMonth()) + Number, dtTmp.getDate(), dtTmp.getHours(), dtTmp.getMinutes(), dtTmp.getSeconds());
        case 'y' :return new Date((dtTmp.getFullYear() + Number), dtTmp.getMonth(), dtTmp.getDate(), dtTmp.getHours(), dtTmp.getMinutes(), dtTmp.getSeconds());
    }
}

function init_srolltext(){
    scrollElem.scrollTop = 0;
    setInterval('scrollUp()', 15); //滚动速度
}
function scrollUp(){
    if(stopscroll) return;
    currentTop += 1;
    if(currentTop == 19) { //滚动距离
        stoptime += 1;
        currentTop -= 1;
        if(stoptime == 220) { //停顿时间
            currentTop = 0;
            stoptime = 0;
        }
    }else{
        preTop = scrollElem.scrollTop;
        scrollElem.scrollTop += 1;
        if(preTop == scrollElem.scrollTop){
            scrollElem.scrollTop = 0;
            scrollElem.scrollTop += 1;
        }
    }
}

function showPop() {
    var t = $(document).height();

    if ($('#pop-share').length <= 0) {
        $('body').append(' <div class="mod-pop" id="pop-share" onclick="hidePop(\'#pop-share\')"> <span class="text-share"></span> </div>');
    };
    var $obj=$('#pop-share');
    $($obj).height(t).fadeIn();
}
function hidePop(e) {
    $(e).fadeOut()
}

$.fn.tips=function(content){
    var tips=$(this);
    $(tips).append('<div class="ui-poptips ui-poptips-success am-animation-fade" > <span class="ui-poptips-cnt">'+content+'</span> </div>');
    setTimeout(function(){$("div.ui-poptips").remove();},3000);
}

$(".icon_251").on("click",function(){
    $('html, body').animate({scrollTop: 0}, '500');
})

window.onscroll = function() {
    $(".icon_251").css("display",(document.body.scrollTop >= 200) ? "block" : "none");
}
