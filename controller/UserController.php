<?php

/**
 * Description of UserController
 * 用户控制器
 * @author 风居住的地方
 */
class UserController extends Controller{
    
    /**
     * 登陆
     */
    public function actionLogin() {
        $model = new UserModel(UserModel::LOGIN_SCENE);
        
        if ($this->getIsPostRequest()) {
            
            if ($model->validates()) {
                
                $userInfo = $model->getUserInfo();
                $_SESSION['user'] = $userInfo;
                Bootstrap::$db->update('user', ['ltime' => time()], ['uid' => $userInfo['uid']]);
                
                $this->renderJson(0, '登陆成功!');
            } else {
                $this->renderJson(1, $model->getFirstError());
            }
        }
        
        $this->render('index');
    }
    
    /**
     * 注册
     */
    public function actionReg() {
    	$model = new UserModel(UserModel::REG_SCENE);
        
        if ($this->getIsPostRequest()) {
            
            if ($model->validates()) {
                
                $model->insert();
                $userInfo = Bootstrap::$db->get('user', '*', ['username' => $model->username]);
                $_SESSION['user'] = $userInfo;
                $this->renderJson(0, '注册成功!');
                
            } else {
                $this->renderJson(1, $model->getFirstError());
            }
        }
        
        $this->render('index');
    }

    /**
     * 退出登陆
     */
    public function actionLogout() {
        unset($_SESSION['user']);
        $this->redirect(['user', 'login']);
    }
}
