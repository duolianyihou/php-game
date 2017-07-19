<?php

/**
 *  登陆注册模型
 * @author wangjiacheng
 */
class UserModel extends BaseModel{

    public $username;
    public $password;
    public $password2;
    
    static public $userInfo;
    
    const LOGIN_SCENE = 'login';
    const REG_SCENE = 'reg';

    public function rules() {
        return [
            ['username,password', self::VAL_REQUIRED, 'on' => 'login'],
            ['username', 'checkUserName', 'on' => 'login'],
            ['password', 'checkPassWord', 'on' => 'login'],
            
            ['username,password,password2', self::VAL_REQUIRED, 'on' => 'reg'],
            ['username', 'checkUserName', 'on' => 'reg'],
            ['password', 'checkPassWord2', 'on' => 'reg'],
        ];
    }
    
    public function getUserInfo() {
        if (self::$userInfo === null) {
            self::$userInfo = Bootstrap::$db->get('user', '*', ['username' => $this->username]);
        }
        return self::$userInfo;
    }


    public function checkUserName($attributes) {
        $userInfo = $this->getUserInfo();
        $scene = $this->getScene();
        
        if ($scene == self::LOGIN_SCENE) {
            !$userInfo && $this->addError($attributes, '用户不存在!'); 
            
        } elseif ($scene == self::REG_SCENE) {
            $userInfo && $this->addError($attributes, '用户名已存在!'); 
        }   
    }
    
    public function checkPassWord($attributes) {
        $userInfo = $this->getUserInfo();
        
        $this->password != $userInfo['password'] && $this->addError($attributes, '密码错误!');
    }
    
    public function checkPassWord2($attributes) {
        $this->password != $this->password2 && $this->addError($attributes, '两次输入的密码不一致!');
    }
    
    public function insert() {
        $userInfo = [
            'username' => $this->username,
            'password' => $this->password,
            'ctime' => time(),
            'ltime' => time()
        ];
        
        Bootstrap::$db->insert('user', $userInfo);
    }
}
