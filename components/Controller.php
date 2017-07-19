<?php

/**
 * Description of Request
 *
 * @author 风居住的地方
 */
class Controller {
    
    /**
     * 默认路由
     * @var type 
     */
    public $defaultController;
    
    /**
     * 控制器
     * @var type 
     */
    public $c;
    
    /**
     * 动作
     * @var type 
     */
    public $a;
    
    /**
     * 构造
     * @param type $config
     */
    public function __construct($config = []) {
        foreach($config as $k => $v) {
            $this->$k = $v;
        }
        
        $this->init();
    }
    
    public function init() {
        
    }
    
    /**
     * 返回规范化的路由
     * @param type $route
     * @return type
     */
    public function normalizeRoute($route) {
        if (strpos($route, '-') === false) {
            return ucfirst($route);
        } else {
            $tmp = explode('-', $route);
            $className = '';
            foreach($tmp as $r) {
                $className .= ucfirst($r);
            }
            return $className;
        }
    }
    
    /**
     * 获取控制器
     * @return type
     */
    public function getController() {
        return isset($_GET['c']) ? $_GET['c'] : false;
    }
    
    /**
     * 获取动作
     * @return type
     */
    public function getAction() {
        return isset($_GET['a']) ? $_GET['a'] : false;
    }
    
    /**
     * 执行应用
     */
    public function run() {
        $requestC = $this->getController();
        $requestA = $this->getAction();
        if (!$requestC || !$requestA) {
            list($c, $a) = explode('/', $this->defaultController);
            $requestC = strtolower($this->normalizeRoute($c));
            $requestA = strtolower($this->normalizeRoute($a));
            $this->redirect([$requestC. '/'. $requestA], $params);
        }
        
        if ($requestC && $requestA) {
            $requestC = $this->normalizeRoute($requestC);
            $requestA = $this->normalizeRoute($requestA);  
        } else {
            list($c, $a) = explode('/', $this->defaultController);
            $requestC = $this->normalizeRoute($c);
            $requestA = $this->normalizeRoute($a);
        }
        
        $this->c = $requestC;
        $this->a = $requestA;
        
        $class = $this->c. 'Controller';
        $action = 'action'. $this->a;
        
        $class = new $class();
        $class->$action();exit;
    }
    
    /**
     * 跳转页面
     * @param type $url             路由
     * @param type $params          参数
     * @param type $result
     * @param type $statusCode
     */
    public function redirect($url = [], $params = [], $result = true, $statusCode = 302) {
        header('Location: '.$this->route($url, $params), $result, $statusCode);
        exit;
    }
    
    /**
     * 创建跳转url
     * @param type $route
     * @param type $params
     * @return type
     */
    public function route($route = [], $params = []) {
        $route = explode('/', $route[0]);
        $url = [];
        $url['c'] = $route[0];
        $url['a'] = $route[1];
        
        $params = empty($params) ? '' : '&'. http_build_query($params);
        
        return 'index.php?'. http_build_query($url). $params;
    }
    
    /**
     * 渲染模板
     * @param type $view    
     * @param type $_params_    参数
     */
    public function render($view, $_params_ = []) {
        $c = $this->getController();
        $a = $this->getAction();
        
        if (is_array($_params_)) {
            extract($_params_, EXTR_PREFIX_SAME, 'params');
        } else {
            $params = $_params_;
        }
        
        $file = BASE_PATH. '/views/'. $c .'/'. $view .'.php';
        if (!file_exists($file)) {
            throw new Exception("模板文件 '$file' 不存在.", 500);
        }
        
        ob_start();
        ob_implicit_flush(false);
        require($file);
        echo ob_get_clean();
    }
    
    /**
     * 获取请求方式
     * @return type
     */
    public function getIsPostRequest() {
        return isset($_SERVER['REQUEST_METHOD']) && !strcasecmp($_SERVER['REQUEST_METHOD'],'POST');
    }
    
    /**
     * 响应输出为json
     * @param type $status      状态码
     * @param type $msg         消息提示
     * @param type $data        响应数据
     */
    public function renderJson($status = 0, $msg = '', $data = []) {
        echo json_encode(['status' => $status, 'data' => $data, 'msg' => $msg], JSON_UNESCAPED_UNICODE);
        exit;
    }
}
