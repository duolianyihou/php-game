<?php

/**
 * Description of BaseModel
 * 作用说明：本类为数据验证和处理类，所有继承自本类的子类都会有一个特性
 * 其所有的属性值并且是公有权限的情况下将会在存在POST或GET提交时被自动赋值，但是前提条件是属性值和提交值的字符串值相同
 * 例： public $username   将会在存在$_POST['username']或$_GET['username']的时候自动被赋值
 *
 * @author wangjiacheng
 */
class BaseModel {

    const VAL_REQUIRED = 'required';      // 非空验证
    const VAL_EMAIL = 'email';            // 邮箱验证
    const VAL_URL = 'url';                // URL验证
    const VAL_LENGTH = 'length';          // 长度验证
    const VAL_NUMERICAL = 'numerical';    // 数字验证
    const VAL_MOBILE = 'mobile';        // 手机号验证

    protected $scene;                   // 当前场景
    protected $errors = array();        // 错误信息
    protected $attributeLabel = array(); // 键值映射数组
    protected $_attributes = [];        // 赋值后的数组

    //版本号
    const VERSION = '1.0';

    /**
     * 初始化
     * @param type $scene   传入场景
     */
    public function __construct($scene = '', $result = true) {
        $this->scene = $scene;
        if (method_exists($this, 'attributeLabels'))
            $this->attributeLabel = $this->attributeLabels();

        if ($result) {
            if (!empty($_REQUEST)) {
                foreach (array_keys($_REQUEST) as $key) {

                    if ($this->hasAttributes($key)) {
                        $this->$key = isset($_REQUEST[$key]) ? $_REQUEST[$key] : $this->$key;
                        $this->_attributes[$key] = $this->$key;
                    }
                }
            }
        }

        $this->init();
    }

    /**
     * 解密app端提交过来的数据data，并将值绑定到属性
     */
    public function setAttr() {
        foreach (get_object_vars($this->decrypt($this->data)) as $key => $value)
            if ($this->hasAttributes($key))
                $this->$key = $value;
    }

    /**
     * 初始化方法
     * 如果需要在子类重写该方法
     */
    public function init() {
        
    }

    /**
     * 验证规则
     * @return type
     */
    public function rules() {
        return [];
    }

    /**
     * 属性键值映射
     * @return type
     */
    public function attributeLabels() {
        return [];
    }

    /**
     * 获取搜索条件
     * @return type
     */
    public function getSearchWhere() {
        return [];
    }

    /**
     * 获取join条件
     * @return type
     */
    public function getSearchJoin() {
        return [];
    }

    /**
     * 获取查询字段
     * @return type
     */
    public function getSearchFiled() {
        return [];
    }

    /**
     * 将data中的数据绑定到模型
     * @param type $data
     */
    public function load($data = []) {
        foreach ($data as $k => $v)
            if ($this->hasAttributes($k))
                $this->$k = $v;

        $this->_attributes = $data;
    }

    /**
     * 添加数据
     * @param type $table           表名
     * @param type $additional      需要额外添加进来的字段
     * @param type $filterField     需要过滤字段
     * @return type
     */
    public function save($table, $additional = [], $filterField = []) {
        $insertData = array_merge($this->getAttributes(), $additional);

        if (!empty($filterField)) {
            foreach ($filterField as $field) {
                if (isset($insertData[$field]))
                    unset($insertData[$field]);
            }
        }
        return $this->db->insert($table, $insertData);
    }

    /**
     * 更新数据
     * @param type $table           表名
     * @param type $where           更新条件
     * @param type $additional      需要额外添加进来的字段
     * @param type $filterField     需要过滤字段
     * @return type
     */
    public function update($table, $where, $additional = [], $filterField = []) {
        $insertData = array_merge($this->getAttributes(), $additional);

        if (!empty($filterField)) {
            foreach ($filterField as $field) {
                if (isset($insertData[$field]))
                    unset($insertData[$field]);
            }
        }
        return $this->db->update($table, $insertData, $where);
    }

    /**
     * 获取原始的提交数据
     */
    public function getAttributes() {
        return $this->_attributes;
    }

    /**
     * 设置场景
     * @param type $scene
     */
    public function setScene($scene) {
        $this->scene = $scene;
    }

    /**
     * 获取当前场景
     * @param type $scene
     * @return type
     */
    public function getScene() {
        return $this->scene;
    }

    /**
     * 当前访问对象是否具有该属性
     * @param type $key
     * @return boolean
     */
    public function hasAttributes($key) {
        if (property_exists($this, $key))
            return true;
        else
            return false;
    }

    /**
     * 数据验证
     * @return type
     */
    public function validates() {
        if (method_exists($this, 'rules')) {
            $rules = $this->rules();

            if (!empty($rules) && is_array($rules)) {
                foreach ($rules as $rule) {

                    if (isset($rule['on']) && !empty($rule['on']) && strcmp($rule['on'], $this->scene) == 0)
                        $this->validate($rule);

                    else if (!isset($rule['on']))
                        $this->validate($rule);
                    else
                        continue;
                }
            }
        }

        return $this->hasError();
    }

    /**
     * 验证匹配
     * @param type $rule
     */
    public function validate($rule) {
        $params = explode(',', $rule[0]);
        foreach ($params as $param) {
            if (!$this->hasError())
                return false;

            switch ($rule[1]) {
                case self::VAL_REQUIRED:
                    $this->required($param);

                    break;
                case self::VAL_EMAIL:
                    $this->email($param);

                    break;
                case self::VAL_URL:
                    $this->url($param);

                    break;
                case self::VAL_LENGTH:
                    $this->length($param, $rule);

                    break;
                case self::VAL_NUMERICAL:
                    $this->numerical($param);

                    break;
                case self::VAL_NUMERICAL:
                    $this->mobile($param);

                    break;

                default:
                    $this->$rule[1]($param);
                    break;
            }
        }
    }

    /**
     * 检测错误
     * @return boolean
     */
    public function hasError() {
        if (empty($this->errors) && count($this->errors) == 0)
            return true;
        else
            return false;
    }

    /**
     * 获取所有错误
     * @return type
     */
    public function getErrors() {
        return $this->errors;
    }
    
    /**
     * 获取第一条错误
     * @return type
     */
    public function getFirstError() {
        foreach($this->errors as $error) {
            return array_shift($error);
        }
    }

    /**
     * 获取单条错误
     * @param type $key
     * @return type
     */
    public function getError($key) {
        foreach ($this->errors as $k => $error)
            if (isset($error[$key]))
                return $this->errors[$k][$key];
    }

    /**
     * 添加错误信息
     * @param type $key
     * @param type $error
     */
    public function addError($key, $error) {
        $this->errors[] = array(
            $key => $error,
        );
    }

    /**
     * 非空验证
     * @param type $param
     */
    public function required($param) {
        if (is_array($this->$param)) {
            $this->errors[] = array(
                $param => isset($this->attributeLabel[$param]) ? $this->attributeLabel[$param] . ' 系统非空验证不支持数组类型的验证，请使用自定义方法进行验证' : $param . ' 系统非空验证不支持数组类型的验证，请使用自定义方法进行验证',
            );
        } else {
            if (is_null($this->$param) || $this->$param === '' || $this->$param === false) {
                $this->errors[] = array(
                    $param => isset($this->attributeLabel[$param]) ? $this->attributeLabel[$param] . ' 不能为空' : $param . ' 不能为空',
                );
            }
        }
    }

    /**
     * 邮箱验证
     * @param type $param
     */
    public function email($param) {
        $chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
        if (strpos($this->$param, '@') !== false && strpos($this->$param, '.') !== false) {
            if (preg_match($chars, $this->$param))
                $result = true;
            else
                $result = false;
        } else
            $result = false;

        if (!$result)
            $this->errors[] = array(
                $param => isset($this->attributeLabel[$param]) ? $this->attributeLabel[$param] . ' 格式不正确' : $param . ' 格式不正确',
            );
    }

    /**
     * URL验证
     * @param type $param
     */
    public function url($param) {
        if (preg_match('/^http[s]?:\/\/' .
                        '(([0-9]{1,3}\.){3}[0-9]{1,3}' . // IP形式的URL- 199.194.52.184  
                        '|' . // 允许IP和DOMAIN（域名）  
                        '([0-9a-z_!~*\'()-]+\.)*' . // 域名- www.  
                        '([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\.' . // 二级域名  
                        '[a-z]{2,6})' . // first level domain- .com or .museum  
                        '(:[0-9]{1,4})?' . // 端口- :80  
                        '((\/\?)|' . // a slash isn't required if there is no file name  
                        '(\/[0-9a-zA-Z_!~\'\(\)\[\]\.;\?:@&=\+\$,%#-\/^\*\|]*)?)$/', $this->$param) == 1)
            $this->errors[] = array(
                $param => isset($this->attributeLabel[$param]) ? $this->attributeLabel[$param] . ' 格式不正确' : $param . ' 格式不正确',
            );
    }

    /**
     * 长度验证
     * @param type $param
     * @param type $rule
     */
    public function length($param, $rule) {
        if (empty($this->$param))
            $length = 0;

        if (function_exists('mb_strlen')) {
            $length = mb_strlen($this->$param, 'utf-8');
        } else {
            preg_match_all("/./u", $this->$param, $ar);
            $length = count($ar[0]);
        }
        if ($length < $rule['min'] || $length > $rule['max'])
            $this->errors[] = array(
                $param => isset($this->attributeLabel[$param]) ? $this->attributeLabel[$param] . '长度不能小于' . $rule['min'] . '不能大于' . $rule['max'] : $param . '长度不能小于' . $rule['min'] . '不能大于' . $rule['max'],
            );
    }

    /**
     * 数字验证
     * @param type $param
     * @param type $r
     */
    public function numerical($param) {
        if (!is_numeric($this->$param))
            $this->errors[] = array(
                $param => isset($this->attributeLabel[$param]) ? $this->attributeLabel[$param] . ' 不是数字' : $param . ' 不是数字',
            );
    }

    /**
     * 手机号验证
     * @param type $param
     */
    public function mobile($param) {
        if (preg_match('#^\d{11}$#', $this->$param) == 0)
            $this->errors[] = array(
                $param => isset($this->attributeLabel[$param]) ? $this->attributeLabel[$param] . ' 不是有效的手机号码' : $param . ' 不是有效的手机号码',
            );
    }

}
