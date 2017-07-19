<?php

/**
 * Description of RoomModel
 * 房间模型
 * @author 风居住的地方
 */
class RoomModel extends BaseModel {
    
    /**
     * 房间状态定义
     * @var type 
     */
    static public $_status = [
        0 => '<span class="label label-default">等待中</span>',
        1 => '<span class="label label-success">准备中</span>',
        2 => '<span class="label label-danger">游戏中</span>'
    ];

    /**
     * 创建房间
     * @param type $fd
     * @param array $data
     */
    public function createRoom($fd, $data) {
        $room = self::$db->get('room', '*', ['owner_uid' => $data['uid']]);
        if ($room === false) {
            $info = [
                'owner_uid' => $data['uid'],
                'red_name' => $data['username'],
                'status' => 1,
                'ctime' => time()
            ];

            self::$db->insert('room', $info);
            $room = self::$db->get('room', '*', ['owner_uid' => $data['uid']]);
        }
        
        // 广播消息提醒
        $this->broadcast($fd, [
            'cmd' => 'newInfo',
            'class' => 'system_room',
            'msg' => self::parseTemplate(self::CREATE_ROOM_INFO, ['username' => $data['username'], 'id' => $room['id']])
        ]);
        
        // 房间创建成功，初始化房间列表
        $data['cmd'] = 'initRoomList';
        $this->initRoomList($fd, $data, $data['uid']);
        
        // 返回房间数据
        $room = self::$db->get('room', '*', ['owner_uid' => $data['uid']]);
        $room['cmd'] = 'roomInfo';
        $this->sendJson($fd, $room);
    }
    
    /**
     * 初始化房间列表
     * @param type $fd
     * @param type $data
     */
    public function initRoomList($fd, $data, $uid = 0) {
        $room = $this->getRoomList();
        $online = $this->getOnline();
        if ($uid > 0) {
            $bind = self::$db->get('bind', '*', ['uid' => $uid]);
        }
        if (!empty($online)) {
            foreach ($online as $line) {
                if (isset($bind)) {
                    if ($line['fd'] != $bind['fd']) {
                        $data['room'] = !empty($room) ? $room : false;
                        $this->sendJson($line['fd'], $data);
                    }
                } else {
                    $data['room'] = !empty($room) ? $room : false;
                    $this->sendJson($line['fd'], $data);
                }
            }
        }
    }
    
    public function getRoomInfo($fd, $data) {
        $room = self::$db->get('room', '*', ['id' => $data['roomid']]);
        
        $where['uid'] = [$room['owner_uid'], $room['guest_uid']];
        
        $bind =self::$db->select('bind', '*', $where);
        
        $room['cmd'] = 'getRoomInfo';
        foreach($bind as $line) {
            $this->sendJson($line['fd'], $room);
        }
    }
    
    /**
     * 获取房间列表
     * @return type
     */
    public function getRoomList() {
        $room = self::$db->select('room', '*');

        !empty($room) && array_walk($room, function(&$value) {
            $value['status_info'] = self::$_status[$value['status']];
        });

        return $room;
    }
    
    /**
     * 退出房间
     * @param type $fd
     * @param type $data
     */
    public function outRoom($fd, $data) {
        $room = self::$db->get('room', '*', [
                'OR' => [
                    'owner_uid' => $data['uid'],
                    'guest_uid' => $data['uid']
            ]]);
        
        if ($room['owner_uid'] == $data['uid']) {
            $this->ownerOut($fd, $data, $room);
        } else if ($room['guest_uid'] == $data['uid']) {
            $this->guestOut($fd, $data, $room);
        }
    }
    
    /**
     * 房主退出
     */
    public function ownerOut($fd, $data, $room) {
        $msg = '【系统消息】房主:'. $data['username']. '已经退出当前对战,您将成为房主!';
        
        if ($room['guest_uid'] > 0) {
            $roomid = $room['id'];
            $guest_uid = $room['guest_uid'];
            unset($room['id']);

            $room['owner_uid'] = $room['guest_uid'];
            $room['guest_uid'] = 0;
            $room['red_name'] = $room['black_name'];
            $room['black_name'] = '';
            $room['status'] = 1;

            self::$db->update('room', $room, ['id' => $roomid]);

            $bind = self::$db->get('bind', '*', ['uid' => $guest_uid]);

            $room['msg'] = $msg;
            $room['cmd'] = 'closeRoom';

            $this->sendJson($bind['fd'], $room);
        } else {
            self::$db->delete('room', ['id' => $room['id']]);
        }
    }
    
    /**
     * 挑战者退出
     */
    public function guestOut($fd, $data, $room) {
        $msg = '【系统消息】挑战者:'. $data['username']. '已经退出当前对战!';
        
        $roomid = $room['id'];
        $owner_uid = $room['owner_uid'];
        unset($room['id']);
        
        $room['guest_uid'] = 0;
        $room['black_name'] = '';
        $room['status'] = 1;
        
        self::$db->update('room', $room, ['id' => $roomid]);
        
        $bind = self::$db->get('bind', '*', ['uid' => $owner_uid]);
        
        $room['msg'] = $msg;
        $room['cmd'] = 'closeRoom';
        
        $this->sendJson($bind['fd'], $room);
    }
}
