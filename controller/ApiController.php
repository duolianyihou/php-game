<?php

/**
 * Description of ApiController
 * 接口控制器
 * @author 风居住的地方
 */
class ApiController extends BaseController{
    
    /**
     * 监控服务器状态，解除绑定关系
     */
    public function actionUnline() {
        
        $this->getIsPostRequest() && Bootstrap::$db->delete('bind') && Bootstrap::$db->delete('online');

        $this->renderJson(0, '操作成功!');
    }

    /**
     * 删除房间
     */
    public function actionDeleteRoom() {
        $this->getIsPostRequest() && Bootstrap::$db->delete('room');
    }
}
