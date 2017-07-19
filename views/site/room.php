<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>象棋对战平台-风居住的地方</title>
<link href="static/xiangqi/css/zzsc.css" type="text/css" rel="stylesheet" />
<link href="static/xiangqi/css/button.css" type="text/css" rel="stylesheet" />
<script>
    var uid = <?= $_SESSION['user']['uid'];   ?>;
    var username = '<?= $_SESSION['user']['username'];   ?>';
    var online = '<?= $this->route(['api/unline']) ?>';
    var deleteRoom = '<?= $this->route(['api/deleteRoom']) ?>';
    var louout = '<?= $this->route(['user/logout'])?>';
    var siteIndex = '<?= $this->route(['site/index']); ?>';
    var roomid = <?= $_GET['room_id'] ?>
</script>
</head>
<body>
<div class="box" id="box">
	<div class="chess_left">
		<canvas id="chess">对不起，您的浏览器不支持HTML5，请升级浏览器至IE9、firefox或者谷歌浏览器！</canvas>
		<div>
			<div class="bn_box" id="bnBox">
                            <input type="button" class="button red" name="ready" id="red_Info" value="<?= isset($room['red_name']) ? '准备中('. $room['red_name']. ')' : '准备中'  ?>" />
                            <input type="button" class="button black" name="ready" id="black_info" value="<?= !empty($room['black_name']) ? '等待加入('. $room['black_name']. ')' : '等待加入'  ?>" />
                            <input type="button" class="button yellow" name="surrender" id="surrender" value="认输" />
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
<script type="text/javascript" src="static/admin/assets/js/libs/jquery-1.10.2.min.js">
</script>

<script src="static/xiangqi/js/common.js"></script> 
<script src="static/xiangqi/js/play.js"></script>

<script src="websoket/jquery.json.js"></script>
<script src="websoket/console.js"></script>
<script src="config.js" charset="utf-8"></script>
<script src="websoket/comet.js" charset="utf-8"></script>
<script src="websoket/room.js" charset="utf-8"></script>
</body>
</html>