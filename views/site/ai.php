<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>象棋对战平台-风居住的地方</title>
<link href="static/ai/css/zzsc.css" type="text/css" rel="stylesheet" />
<link href="static/ai/css/button.css" type="text/css" rel="stylesheet" />
<script>
    var uid = <?= $_SESSION['user']['uid'];   ?>;
    var username = '<?= $_SESSION['user']['username'];   ?>';
</script>
</head>
<body>
<div class="box" id="box">
	<div class="chess_left">
		<canvas id="chess">对不起，您的浏览器不支持HTML5，请升级浏览器至IE9、firefox或者谷歌浏览器！</canvas>
		<div>
			<div class="bn_box" id="bnBox">
				<input type="button" class="button yellow" name="offensivePlay" id="tyroPlay" value="新手水平" />
				<input type="button" class="button green" name="offensivePlay" id="superPlay" value="中级水平" />
                                <!--<input type="button" class="button black" name="offensivePlay" id="masterPlay" value="大师级水平" />-->
				<input type="button" class="button black" name="regret" id="regretBn" value="悔棋" />
				<input type="button" class="button red" name="billBn" id="billBn" value="棋谱" />
                                <input type="button" class="button blue" name="return" id="return" value="返回大厅" />
			</div>
		</div>
	</div>
	<div class="chess_right" id="chessRight">
		<select name="billList" id="billList">
		</select>
		<ol id="billBox" class="bill_box">
		</ol>
	</div>
	<div id="moveInfo" class="move_info"> </div>
</div>
<script src="static/ai/js/common.js"></script> 
<script src="static/ai/js/play.js"></script> 
<script src="static/ai/js/AI.js"></script> 
<script src="static/ai/js/bill.js"></script> 
<script src="static/ai/js/gambit.js"></script>
<script type="text/javascript" src="static/admin/assets/js/libs/jquery-1.10.2.min.js">
</script>
<script>
    $('#return').on('click', function() {
        if (confirm('确定要返回大厅吗?')) {
            location.href = '<?= $this->route(['site/index']); ?>';
        }
        return false;
    })
</script>
</body>
</html>