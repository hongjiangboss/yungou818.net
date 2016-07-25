<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>网银+</title>
    <link rel="stylesheet" href="../static/css/base.css"/>
</head>
<body>

<div class="nav">
    <span class="arrow goback"><em></em></span>

    <h1>退款结果</h1>
</div>
<div class="nav-wrap"></div>

<div class="grid">

    <div class="noticeWrap grid94">
    提示信息: <?php echo $_SESSION ['errorMsg'] ?>

			<br />

   </div>


</div>


<!--submit btn start-->
<div class="grid94">
    <a href="javascript:history.go(-1);" id="J-next-btn" class="btn btn-actived mt15">返回</a>
</div>
<!--submit btn end-->

</body>
</html>
