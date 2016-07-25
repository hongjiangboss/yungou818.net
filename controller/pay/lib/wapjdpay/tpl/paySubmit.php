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

    <h1>交易信息</h1>
</div>
<div class="nav-wrap"></div>

<div class="grid">

    <form method="post" action="<?php echo ConfigUtil::get_val_by_key('serverPayUrl');?>" id="payForm">
        <!--交易信息 start-->
        <?php if($_SESSION ['tradeInfo']!=null){?>
        <input type="hidden" name="version" value="<?php echo $_SESSION ['tradeInfo']['version'];?>"/>
        <input type="hidden" name="token" value="<?php echo $_SESSION ['tradeInfo']['token'];?>"/>
        <input type="hidden" name="merchantSign" value="<?php echo $_SESSION ['tradeInfo']['merchantSign'];?>"/>
        <input type="hidden" name="merchantNum" value="<?php echo $_SESSION ['tradeInfo']['merchantNum'];?>"/>
        <input type="hidden" name="merchantRemark" value="<?php echo $_SESSION ['tradeInfo']['merchantRemark'];?>"/>
        <input type="hidden" name="tradeNum" value="<?php echo $_SESSION ['tradeInfo']['tradeNum'];?>"/>
        <input type="hidden" name="tradeName" value="<?php echo $_SESSION ['tradeInfo']['tradeName'];?>"/>
        <input type="hidden" name="tradeDescription" value="<?php echo $_SESSION ['tradeInfo']['tradeDescription'];?>"/>
        <input type="hidden" name="tradeTime" value="<?php echo $_SESSION ['tradeInfo']['tradeTime'];?>"/>
        <input type="hidden" name="tradeAmount" value="<?php echo $_SESSION ['tradeInfo']['tradeAmount'];?>"/>
        <input type="hidden" name="currency" value="<?php echo $_SESSION ['tradeInfo']['currency'];?>"/>
        <input type="hidden" name="notifyUrl" value="<?php echo $_SESSION ['tradeInfo']['notifyUrl'];?>"/>
        <input type="hidden" name="successCallbackUrl" value="<?php echo $_SESSION ['tradeInfo']['successCallbackUrl'];?>"/>
        <input type="hidden" name="failCallbackUrl" value="<?php echo $_SESSION ['tradeInfo']['failCallbackUrl'];?>"/>
        <?php }?>
        <!--交易信息 end-->

        <ul class="form-wrap" id="J-form-wrap">

            <li class="form-item form-item-border clearfix">
                <label>交易名称</label>

                <div class="form-field">
                    <input type="text" class="" value="<?php echo $_SESSION['tradeName'];?>" autocomplete="off"
                           minlength="15" maxlength="18" data-callback="input.status"/>
                    <span class="clear-btn J-clear-btn">×</span>
                </div>
            </li>
            <li class="form-item form-item-border clearfix">
                <label>交易金额</label>

                <div class="form-field">
                    <input type="text" class="" value="<?php echo $_SESSION['tradeAmount'];?>分" autocomplete="off"
                           placeholder="请输入身份证号" minlength="15" maxlength="18" data-callback="input.status"/>
                    <span class="clear-btn J-clear-btn">×</span>
                </div>
            </li>

            <div class="grid96">
                <a href="javascript:;" id="J-next-btn" class="btn btn-disabled mt15 btn-actived">去支付</a>
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
            $('#payForm').submit();
        });
    })(Zepto);
</script>
</body>
</html>
