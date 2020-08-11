<?php
namespace app\admin\middleware;

use \think\Request;
use app\util\Token;

class Auth
{
    /**
     * 处理请求
     */
    public function handle(Request $request, \Closure $next)
    {
        $tokenInfo = $request->header();
        if (empty($tokenInfo['token'])) {
            return ajaxReturn('ERR_CODE_LOGIN_OVERDUE');
        } else {
            $token = $tokenInfo['token'];
            $checkTokenRet =  Token::checkToken($token);
           if (!empty($checkTokenRet)) {
               return ajaxReturn('ERR_CODE_LOGIN_OVERDUE');
           }
       }
       return $next($request);
    }
}
