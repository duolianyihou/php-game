<?php

class Tools {

	static public function currentTime() {
		$h = date('H');
		if ($h > 6 && $h < 12) {
			return '早上好';
		} else if ($h > 12 && $h < 18) {
			return '下午好';
		} else {
			return '晚上好';
		}
	}
}
