<?php 
use wepay\join\demo\common\ConfigUtil;
include '../common/ConfigUtil.php';
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8"/>
    <title>模拟商户--交易查询</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0, user-scalable=no"/>
    <link rel="stylesheet" href="../static/css/base.css"/>
</head>
<body>
<div class="nav">
    <span class="arrow goback"><em></em></span>

    <h1>交易查询</h1>
</div>
<div class="nav-wrap"></div>

<div class="grid">

<form method="post" action="../api/WebQuerySign.php" id="queryTradeForm">

    <ul class="form-wrap" id="J-form-wrap">

        <li class="form-item form-item-border clearfix">
            <label>接口版本</label>

            <div class="form-field">
                <input type="text" class="" name="version" value="1.0" autocomplete="off"
                        minlength="15" maxlength="18" data-callback="input.status"/>
                <span class="clear-btn J-clear-btn">×</span>
            </div>
        </li>


        <li class="form-item form-item-border clearfix">
            <label>商户号</label>

            <div class="form-field">
                <input type="text" class="" name="merchantNum" value="<?php echo ConfigUtil::get_val_by_key('merchantNum');?>" autocomplete="off"
                       placeholder="请输入商户号" minlength="15" maxlength="50" data-callback="input.status"/>
                <span class="clear-btn J-clear-btn">×</span>
            </div>
        </li>


        <li class="form-item form-item-border clearfix">
            <label>交易号</label>

            <div class="form-field">
                <input type="text" class="" name="tradeNum"  autocomplete="off" placeholder="请输入交易号" minlength="15" maxlength="50"/>
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
            $('#queryTradeForm').submit();
        });

    })(Zepto);
</script>
</body>
</html>
