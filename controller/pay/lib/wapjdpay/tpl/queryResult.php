<?php if (!session_id()) session_start();?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>网银+</title>
<link rel="stylesheet" href="../static/css/base.css" />
</head>
<body>

	<div class="nav">
		<span class="arrow goback"><em></em></span>

		<h1>查询结果</h1>
	</div>
	<div class="nav-wrap"></div>

	<div class="grid">

		<div class="noticeWrap grid94">
		    提示信息:<?php echo $_SESSION['errorMsg'] ?>
			<br />

  <?php
		if ($_SESSION['queryDatas']) {
			foreach ( $_SESSION['queryDatas'] as $val ) {
				
				echo "交易币种:" . $val ['tradeCurrency']."\n";
				echo "交易日期:" . $val ['tradeDate']."\n";
				echo "交易时间:" . $val ['tradeTime']."\n";
				echo "交易金额:" . $val ['tradeAmount']."分\n";
				echo "交易备注:" . $val ['tradeNote']."\n";
				echo "交易号:" .$val ['tradeNum']."\n";
				echo "交易状态:" . $val ['tradeStatus']."\n";
				?>
				<br>
				<?php 
			}
		}
		?>
    </div>
	</div>


	<!--submit btn start-->
	<div class="grid94">
		<a href="javascript:history.go(-1);" id="J-next-btn"
			class="btn btn-actived mt15">返回</a>
	</div>
	<!--submit btn end-->

</body>
</html>