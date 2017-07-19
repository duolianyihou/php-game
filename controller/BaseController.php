<?php

/**
 * Description of ApiController
 * 基础控制器
 * @author 风居住的地方
 */
class BaseController extends Controller{
    
    public function init() {
        empty($_SESSION['user']) && $this->redirect(['user/login']);
    }
}
