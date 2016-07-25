<?php
use wepay\join\demo\common\ConfigUtil;
include '../common/ConfigUtil.php';
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8"/>
    <title>模拟商户--订单支付页面</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0, user-scalable=no"/>
    <link rel="stylesheet" href="../static/css/base.css"/>
</head>
<body>

<div class="nav">
    <span class="arrow goback"><em></em></span>

    <h1>支付请求</h1>
</div>
<div class="nav-wrap"></div>

<div class="grid">

<form method="post" action="../api/WebPaySign.php" id="paySignForm">

    <ul class="form-wrap" id="J-form-wrap">

        <li class="form-item form-item-border clearfix">
            <label>服务URL</label>

            <div class="form-field">
                <input type="text" name="serverUrl" value="<?php echo ConfigUtil::get_val_by_key('serverPayUrl');?>" autocomplete="off"
                      maxlength="50" data-callback="input.status"/>
                <span class="clear-btn J-clear-btn">×</span>
            </div>
        </li>

        <li class="form-item form-item-border clearfix">
            <label>接口版本</label>

            <div class="form-field">
                <input type="text"  name="version" value="1.0" autocomplete="off"
                         maxlength="18" data-callback="input.status"/>
                <span class="clear-btn J-clear-btn">×</span>
            </div>
        </li>


        <li class="form-item form-item-border clearfix">
            <label>令牌</label>

            <div class="form-field">
                <input type="text" class="" name="token" value="" autocomplete="off" placeholder="请输入用户交易令牌"
                        maxlength="50" data-callback="input.status"/>
                <span class="clear-btn J-clear-btn">×</span>
            </div>
        </li>

        <li class="form-item form-item-border clearfix">
            <label>商户号</label>

            <div class="form-field">
                <input type="text" class="" name="merchantNum" value="<?php echo ConfigUtil::get_val_by_key('merchantNum');?>" autocomplete="off" placeholder="请输入商户号" minlength="15" maxlength="50" data-callback="input.status"/>
                <span class="clear-btn J-clear-btn">×</span>
            </div>
        </li>

        <li class="form-item form-item-border clearfix">
            <label>商户备注</label>

            <div class="form-field">
                <input type="text" class="" name="merchantRemark" value="生产环境-测试商户号" autocomplete="off"
                       placeholder="请输入商户备注"  maxlength="50" data-callback="input.status"/>
                <span class="clear-btn J-clear-btn">×</span>
            </div>
        </li>

        <li class="form-item form-item-border clearfix">
            <label>交易号</label>

            <div class="form-field">
                <input type="text" class="" name="tradeNum" value="<?php echo ConfigUtil::get_trade_num();?>" autocomplete="off"
                       placeholder="请输入交易号" maxlength="50" data-callback="input.status"/>
                <span class="clear-btn J-clear-btn">×</span>
            </div>
        </li>

        <li class="form-item form-item-border clearfix">
            <label>交易名称</label>

            <div class="form-field">
                <input type="text" class="" name="tradeName" value="交易名称" autocomplete="off"
                       placeholder="请输入交易名称" minlength="15" maxlength="225" data-callback="input.status"/>
                <span class="clear-btn J-clear-btn">×</span>
            </div>
        </li>

        <li class="form-item form-item-border clearfix">
            <label>交易描述</label>

            <div class="form-field">
                <input type="text" class="" name="tradeDescription" value="交易描述" autocomplete="off"
                       placeholder="请输入交易描述" minlength="15" maxlength="50" data-callback="input.status"/>
                <span class="clear-btn J-clear-btn">×</span>
            </div>
        </li>

        <li class="form-item form-item-border clearfix">
            <label>交易时间</label>

            <div class="form-field">
                <input type="text" class="" name="tradeTime" value="<?php echo date('Y-m-d H:i:s', time());?>" autocomplete="off" placeholder="请输入交易时间" minlength="15" maxlength="50" data-callback="input.status"/>
                <span class="clear-btn J-clear-btn">×</span>
            </div>
        </li>

        <li class="form-item form-item-border clearfix">
            <label>交易金额</label>

            <div class="form-field">
                <input type="text" class="" name="tradeAmount" value="1" autocomplete="off"
                       placeholder="请输入交易金额" minlength="15" maxlength="50" data-callback="input.status"/>
                <span class="clear-btn J-clear-btn">×</span>
            </div>
        </li>

        <li class="form-item form-item-border clearfix">
            <label>货币种类</label>

            <div class="form-field">
                <input type="text" class="" name="currency" value="CNY" autocomplete="off"
                       placeholder="请输入交易币种" minlength="15" maxlength="50" data-callback="input.status"/>
                <span class="clear-btn J-clear-btn">×</span>
            </div>
        </li>

        <li class="form-item form-item-border clearfix">
            <label>异步通知</label>

            <div class="form-field">
                <input type="text" class="" name="notifyUrl" value="<?php echo ConfigUtil::get_val_by_key('notifyUrl');?>"
                       autocomplete="off" placeholder="请输入异步通知地址" minlength="15" maxlength="200"
                       data-callback="input.status"/>
                <span class="clear-btn J-clear-btn">×</span>
            </div>
        </li>

        <li class="form-item form-item-border clearfix">
            <label>成功通知</label>

            <div class="form-field">
                <input type="text" class="" name="successCallbackUrl" value="<?php echo ConfigUtil::get_val_by_key('successCallbackUrl');?>"
                       autocomplete="off" placeholder="请输入交易成功通知地址" minlength="15" maxlength="200"
                       data-callback="input.status"/>
                <span class="clear-btn J-clear-btn">×</span>
            </div>
        </li>

        <li class="form-item form-item-border clearfix">
            <label>失败通知</label>

            <div class="form-field">
                <input type="text" class="" name="failCallbackUrl" value="<?php echo ConfigUtil::get_val_by_key('failCallbackUrl');?>"
                       autocomplete="off" placeholder="请输入交易失败通知地址" minlength="15" maxlength="200"
                       data-callback="input.status"/>
                <span class="clear-btn J-clear-btn">×</span>
            </div>
        </li>

        <div class="grid96">
            <a href="javascript:;" id="J-next-btn" class="btn btn-disabled mt15 btn-actived">提交</a>
        </div>
    </ul>
</form>
</div>




<script src="../static/js/zepto.js"></script>
<script>
    ;(function ($) {
        var Dom = {
            nextBtn: $('#J-next-btn')
        }
        /*点击下一步的操作*/
        Dom.nextBtn.on('click', function () {
            $('#paySignForm').submit();
        });

    })(Zepto);
</script>
</body>
</html>
