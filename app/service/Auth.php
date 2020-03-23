<?php
namespace app\service;

class Auth
{
    public static function getMd5($password)
    {
        $str = config('app.login_md5');
        return md5(md5($password) . $str);
    }


    /**
     * 检测用户是否登录
     *
     * @return mixed
     */
    public static function is_login()
    {
        $user = self::sessionGet('user');
        if (empty($user)) {
            return false;
        } else {
            return self::sessionGet('user_sign') == self::data_auth_sign($user) ? $user : false;
        }
    }

    /**
     * 权限认证
     *
     * @access public
     * @return mixed
     */
    public function auth()
    {
        $uid = self::sessionGet('user.uid');
        $controller = Loader::parseName($this->controller, 1); // 字符串命名风格转换
        $rule = strtolower("{$this->module}/{$controller}/{$this->action}");
        // 如果用户角色是1，则无需判断
        if (empty($uid)) {
            return false;
        }
        if ($uid == 1) {
            return true;
        }
        // 需要过滤掉的页面
        $filterRule = config('shield');
        if (in_array($rule, $filterRule)) {
            return true;
        }
        return self::authCheck($rule, 'or');
    }

    /**
     * 读取session
     *
     * @access private static
     * @param string $path 被认证的数据
     * @return mixed
     */
    private static function sessionGet($path = '')
    {
        $session_prefix = Config::get('thinkcms.session_prefix');
        $user = Session::get($session_prefix . $path);
        return $user;
    }

    /**
     * 注销
     *
     * @access private static
     * @return bool
     */
    public static function logout()
    {
        $session_prefix = Config::get('thinkcms.session_prefix');
        Session::delete($session_prefix . 'user');
        Session::delete($session_prefix . 'user_sign');
        return true;
    }

    /**
     * 数据签名认证
     *
     * @access private static
     * @param array $data 被认证的数据
     * @return string 签名
     */
    private static function data_auth_sign($data)
    {
        $code = http_build_query($data); // url编码并生成query字符串
        $sign = sha1($code); // 生成签名
        return $sign;
    }
}