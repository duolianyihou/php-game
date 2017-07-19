<?php
session_start();

/**
 * Description of Request
 *
 * @author 风居住的地方
 */
class Bootstrap {
    /**
     * 应用配置
     * @var type 
     */
    public $config;
   
    /**
     * 数据库连接对象
     * @var type 
     */
    static public $db;
    
    /**
     * 自动加载文件目录
     * @var type 
     */
    static public $authDir = [
        '/controller/',
        '/models/',
        '/helps/',
    ];

    /**
     * 核心类文件
     * @var type 
     */
    static protected $coreClass = [
        '/components/Controller.php',
        '/components/Request.php',
        '/db/medoo.php',
    ];

    /**
     * 构造
     * @param type $config
     */
    public function __construct($config) {
        $this->config = $config;
        $this->init();
        $this->initDb();
    }
    
    /**
     * 初始化包含文件
     */
    public function init() {
        foreach(self::$coreClass as $class) {
            include BASE_PATH. $class;
        }
    }
    
    /**
     * 初始化数据库连接
     * @return type
     */
    public function initDb() {
        self::$db === null && self::$db = new medoo($this->config['db']);
        
        return self::$db;
    }
    
    /**
     * 执行应用
     */
    public function run() {
        $request = new Request();
        $controll = new Controller($this->config);
        
        $controll->run();
    }
    
    /**
     * 自动加载
     * @param type $className
     */
    static public function autoload($className) {
        array_walk(self::$authDir, function($dir) use ($className){
            $file = BASE_PATH. $dir. $className. '.php';
            file_exists($file) && include $file;
        });
    }
}

spl_autoload_register(array('Bootstrap','autoload'));
