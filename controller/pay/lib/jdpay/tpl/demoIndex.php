<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8"/>
    <title>模拟商户--DEMO</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0, user-scalable=no"/>
    <link rel="stylesheet" href="../static/css/base.css"/>
</head>
<body>

<div class="nav">
    <span class="arrow goback"><em></em></span>

    <h1>商户接入DEMO</h1>
</div>
<div class="nav-wrap"></div>

<div class="grid">

    <ul class="form-wrap" id="J-form-wrap">

        <div class="grid96">
            <a href="payIndex.php"class="btn btn-disabled mt15 btn-actived">交易请求</a>
        </div>

        <div class="grid96">
            <a href="queryIndex.php"class="btn btn-disabled mt15 btn-actived">交易查询</a>
        </div>

        <div class="grid96">
            <a href="refundIndex.php"class="btn btn-disabled mt15 btn-actived">退款请求</a>
        </div>

    </ul>
</div>


<script src="../static/js/zepto.js"></script>
<script>
    ;(function ($) {
        var Dom = {
            nextBtn: $('#J-next-btn')
        }
    })(Zepto);
</script>
</body>
</html>
