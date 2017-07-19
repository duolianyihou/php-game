<?php

/**
 * Description of SiteController
 * 主控制器
 * @author 风居住的地方
 */
class SiteController extends BaseController{
    
    /**
     * 网站首页
     */
    public function actionIndex() {
        $data = '你好';
        
        
        $this->render('index', [
            'data' => $data
        ]);
    }
    
    /**
     * 对战房间
     */
    public function actionRoom() {
        $roomid = $_GET['room_id'];
        $room = Bootstrap::$db->get('room', '*', ['id' => $roomid]);
        
        empty($room) && $this->redirect(['site/index']);
        
        $this->render('room', [
            'room' => $room
        ]);
    }
    
    /**
     * 加入对战
     */
    public function actionPlay() {
        $user = $_SESSION['user'];
        $roomid = $_GET['room_id'];
        
        Bootstrap::$db->update('room', [
            'guest_uid' => $user['uid'],
            'black_name' => $user['username'],
            'status' => 2
        ], ['id' => $roomid]);
        
        $this->redirect(['site/room'], ['room_id' => $roomid]);
    }
    
    /**
     * 人机对战
     */
    public function actionAi() {
        
        $this->render('ai');
    }
}
