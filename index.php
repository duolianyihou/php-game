<?php

defined('BEGIN_TIME') or define('BEGIN_TIME',microtime(true));

defined('BASE_PATH') or define('BASE_PATH',dirname(__FILE__));

$config = require BASE_PATH. '/config/main.php';

require BASE_PATH. '/components/bootstrap.php';


(new Bootstrap($config))->run();
