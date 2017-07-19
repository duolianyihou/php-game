<?php

/**
 * Description of Request
 *
 * @author 风居住的地方
 */
class Request {

    public function __construct() {

        $this->normalizeRequest();
    }

    /**
     * 规范数据格式
     */
    protected function normalizeRequest() {
        if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
            if (isset($_GET))
                $_GET = $this->stripSlashes($_GET);
            if (isset($_POST))
                $_POST = $this->stripSlashes($_POST);
            if (isset($_REQUEST))
                $_REQUEST = $this->stripSlashes($_REQUEST);
            if (isset($_COOKIE))
                $_COOKIE = $this->stripSlashes($_COOKIE);
        }
    }

    /**
     * 过滤输入
     * @param type $data    
     * @return type
     */
    public function stripSlashes(&$data) {
        if (is_array($data)) {
            if (count($data) == 0)
                return $data;
            $keys = array_map('stripslashes', array_keys($data));
            $data = array_combine($keys, array_values($data));
            return array_map(array($this, 'stripSlashes'), $data);
        } else
            return stripslashes($data);
    }

}
