<?php

/**
 * Description of BaseModel
 * 模型基类
 * @author 风居住的地方
 */
class BaseModel {
    
    const SUCCESS = 'alert alert-success fade in';
    const DANGER = 'alert alert-danger fade in';
    
    static public $server;

    static public $db;
    
    /**
     * 用户上线通知
     */
    const USER_ONLINE = 'userOnline';
    
    /**
     * 用户下线通知
     */
    const OFFLINE = 'offline';
    
    /**
     * 创建房间消息通知
     */
    const CREATE_ROOM_INFO = 'createRoomInfo';
    
    /**
     * 消息提示模板配置
     * @var type 
     */
    static public $msgTemplate = [
        'createRoomInfo' => '用户{username}创建了【房间{id}】,赶快加入对战吧!',
        
        'userOnline' => '用户【{username}】上线了!',
        
        'offline' => '用户【{username}】下线了!',
    ];

    /**
     * 初始化
     * @param type $server
     * @param type $db
     */
    public function __construct($server, $db) {
        self::$server = $server;
        self::$db = $db;
    }
    
    /**
     * 用户下线通知
     * @param type $fd
     */
    public function offline($fd) {
        $uid = self::$db->get('bind', 'uid', ['fd' => $fd]);
        $user = self::$db->get('user', '*', ['uid' => $uid]);


        self::$db->delete('online', ['fd' => $fd]);
        self::$db->delete('bind', ['fd' => $fd]);

        $fds = self::$db->select('online', '*');

        if (!empty($fds)) {
            foreach ($fds as $v) {

                $this->sendJson($v['fd'], [
                    'cmd' => 'offline',
                    'uid' => $uid,
                    'msg' => self::parseTemplate(self::OFFLINE, $user, self::DANGER)
                ]);
            }
        }
    }
    
    /**
     * 返回json化数据
     * @param type $data
     * @return type
     */
    static public function getJson($data) {
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * 发送JSON数据
     * @param $client_id
     * @param $array
     */
    public function sendJson($client_id, $array) {
        self::$server->push($client_id, self::getJson($array));
    }
    
    /**
     * 广播数据
     * @param type $fd          当前连接fd
     * @param type $data        发送数据
     * @param type $result      是否发送广播给当前用户. true:是,false:否
     */
    public function broadcast($fd, $data, $result = false) {
        $online = $this->getOnline();
       
        if (!empty($online)) {

            foreach ($online as $line) {
                if ($result) {
                    $this->sendJson($line['fd'], $data);
                } else {
                    $fd != $line['fd'] && $this->sendJson($line['fd'], $data);
                }
            }
        }
    }
    
    /**
     * 获取在线用户
     * @return type
     */
    public function getOnline() {

        return $data = self::$db->select('online', [
            '[>]user' => ['uid' => 'uid'],
                ], [
            'online.id',
            'online.fd',
            'online.uid',
            'online.data',
            'user.ltime',
        ], ['ORDER' => 'user.ltime ASC']);
    }
    
    /**
     * 解析消息模板
     * @param type $key     索引
     * @param type $data    数据
     * @param type $type    消息颜色
     * @return type
     */
    static public function parseTemplate($key , $data = [], $type = self::SUCCESS) {
        $template = self::$msgTemplate[$key];

        preg_match_all('/\{(.*?)\}/', $template, $matches);

        if (!empty($matches[1])) {
            $matches = array_unique($matches[1]);

            foreach($matches as $match) {

                if (strpos($template , '{'.$match.'}') !== false) {

                    $template = str_replace('{'.$match.'}', $data[$match], $template);
                }
            }
        }

        return self::alert($template, $type);
    }
    
    /**
     * 系统消息提示
     * @param type $msg     消息内容
     * @return type         提示类型颜色
     */
    static public function alert($msg, $type = self::SUCCESS) {
        return '<div class="' . $type . '">
                            <strong>【系统消息】</strong>
                            ' . $msg . '
                        </div>';
    }
    
    /**
     * 获取用户信息
     * @param type $uid     用户uid
     * @return type
     */
    static public function getUserInfo($uid) {
        return self::$db->get('user', '*', ['uid' => $uid]);
    }
    
    /**
     * 查询用户是否在游戏中
     * @param type $uid
     * @return type
     */
    static public function queryUserIsGame($uid) {
        $room = self::$db->get('room', '*', [
                'OR' => [
                    'owner_uid' => $uid,
                    'guest_uid' => $uid
            ]]);
        
        return $room === false ? false : true;
    }
}
