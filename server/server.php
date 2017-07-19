<?php

spl_autoload_register(array('Server','autoload'));

$server = new Server('0.0.0.0', 9501);
$server->run();

/**
 * Description of BaseModel
 * 基础模型类
 * @author 风居住的地方
 */
class Server {
    /**
     * 命令 => 模型 映射
     * @var type 
     */
    static public $cmds = [
        'bindUser' => 'UserModel',
        
        'initUserList' => 'UserModel',
        
        'createRoom' => 'RoomModel',
        
        'initRoomList' => 'RoomModel',
        
        'getRoomInfo' => 'RoomModel',
        
        'outRoom' => 'RoomModel'
    ];
    
    /**
     * server对象
     * @var type 
     */
    static public $server;
    
    /**
     * 数据库连接
     * @var type 
     */
    static public $db;
    
    /**
     * 
     * @var type 
     */
    static public $object = [];

    /**
     * 数据库配置
     * @var type 
     */
    static public $dbConfig = [
        'server' => '127.0.0.1',
        'username' => 'root',
        'password' => '123456',
        'database_name' => 'youxi'
    ];
    
    /**
     * 自动加载文件目录
     * @var type 
     */
    static public $authDir = [
        'db',
        'models'
    ];

    /**
     * 构造
     * @param type $host        主机地址
     * @param type $port        端口号
     */
    public function __construct($host, $port) {
        self::$server === null && self::$server = new swoole_websocket_server($host, $port);
        self::$db === null && self::$db = new medoo(self::$dbConfig);
        
        $this->adapter();
    }
    
    /**
     * 启动服务
     */
    public function run() {

        self::$server->start();
    }
    
    /**
     * 获取处理模型
     * @param type $cmd
     * @return type
     */
    static public function getModel($cmd) {
        !isset(self::$object[$cmd]) && self::$object[$cmd] = new self::$cmds[$cmd](self::$server, self::$db);
        
        return self::$object[$cmd];
    }
    
    /**
     * 事件响应开始
     */
    public function adapter() {
        $this->open();
        $this->message();
        $this->close();
    }
    
    /**
     * 连接打开
     */
    public function open() {
        self::$server->on('open', function (swoole_websocket_server $server, $request) {
            echo "消息: 服务器连接成功,连接id:{$request->fd}\n";
        });
    }
    
    /**
     * 响应
     */
    public function message() {
        self::$server->on('message', function (swoole_websocket_server $server, $frame) {
            $data = json_decode($frame->data, true);
            
            foreach(self::$cmds as $cmd => $class) {
                $model = self::getModel($cmd);
                
                echo $data['cmd']. "\n";
                switch ($data['cmd']) {
                    case $cmd:
                        $model->$cmd($frame->fd, $data);
                        break;
                }
            }
        });
    }
    
    /**
     * 连接关闭
     */
    public function close() {
        self::$server->on('close', function ($server, $fd) {
            
            echo "消息: $fd  连接已断开\n";
            
            $this->afterClose($fd);
        });
    }
    
    /**
     * 连接关闭后操作
     */
    public function afterClose($fd) {
        $userModel = new UserModel(self::$server, self::$db);
        
        $data = self::getFdByUser($fd);
        if ($data) {
            $isGame = BaseModel::queryUserIsGame($data['uid']);

            // 如果是在游戏房间内刷新，不发送下线通知，不进行用户列表初始化
            if (!$isGame) {
                $userModel->offline($fd);
                $userModel->initUserList($fd, ['cmd' => 'initUserList']);
            }
        }
        
    }


    /**
     * 自动加载
     * @param type $className
     */
    static public function autoload($className) {
        array_walk(self::$authDir, function($dir) use ($className){
            $file = $dir. '/'. $className. '.php';
            file_exists($file) && include $file;
        });
    }
    
    /**
     * 通过fd查询用户信息
     * @param type $fd
     * @return type
     */
    static public function getFdByUser($fd) {
        $user = false;
        
        $bind = self::$db->get('bind', '*', ['fd' => $fd]);
        
        !empty($bind) && $user = self::$db->get('user', '*', ['uid' => $bind['uid']]);
        
        return $user;
    }
}
