<?php

/**
 * Description of UserModel
 * 用户模型
 * @author 风居住的地方
 */
class UserModel extends BaseModel{
    
    /**
     * 绑定用户
     * @param type $fd      连接fd
     * @param type $data    客户端数据
     */
    public function bindUser($fd, $data) {
        $user = self::getUserInfo($data['uid']);
        $this->bindUserClient($fd, $data, $user);
        
        // 绑定成功，响应客户端
        $this->sendJson($fd, $data);
        
        
        // 发送上线通知
        $resp = [];
        $resp['msg'] = self::parseTemplate(self::USER_ONLINE, $user);
        $resp['cmd'] = 'newInfo';
        $resp['class'] = 'system_success';
        
        // 如果当前用户不在游戏中，才会发送上线通知
        !self::queryUserIsGame($data['uid']) && $this->broadcast($fd, $resp);
    }
    
    /**
     * 初始化用户列表
     * @param type $fd
     * @param type $data
     */
    public function initUserList($fd, $data = []) {
        $online = $this->getOnline();

        if (!empty($online)) {
            foreach ($online as $line) {

                $data['online'] = $online;
                $this->sendJson($line['fd'], $data);
            }
        }
    }
    
    /**
     * 绑定连接
     * @param type $fd      当前连接fd
     * @param type $data    客户端数据
     * @param type $user    用户信息
     */
    public function bindUserClient($fd, $data, $user) {
        $oldFd = self::$db->get('bind', 'fd', ['uid' => $data['uid']]);
        
        if ($oldFd === false) {
            self::$db->insert('bind', [
                'fd' => $fd,
                'uid' => $data['uid'],
                'ctime' => time(),
            ]);
        } else {
            if ($oldFd != $fd) {
                self::$db->update('bind', ['fd' => $fd], ['uid' => $data['uid']]);
            }
        }
        
        self::regOnline($fd, $data, $user);
    }
    
    /**
     * 注册在线状态
     * @param type $fd      当前连接fd
     * @param type $data    客户端数据
     * @param type $user    用户信息
     */
    static public function regOnline($fd, $data, $user) {
        $online = self::$db->get('online', 'fd', ['uid' => $data['uid']]);
        
        if ($online === false) {
            self::$db->insert('online', [
                'fd' => $fd,
                'uid' => $data['uid'],
                'data' => self::getJson($user),
            ]);
        } else {
            self::$db->update('online', ['fd' => $fd], ['uid' => $data['uid']]);
        }
    }
}
