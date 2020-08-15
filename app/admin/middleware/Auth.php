<?php
namespace app\admin\middleware;

use \think\Request;
use app\util\Token;
use think\facade\Log;

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
            list($dealRet, $checkTokenRet) =  Token::checkToken($token);
            if (!$dealRet) {
                return ajaxReturn(ERR_CODE_LOGIN_OVERDUE);
            }
            $request->userId = $checkTokenRet;
       }
       return $next($request);
    }
}
