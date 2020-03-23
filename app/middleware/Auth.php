<?php
namespace app\middleware;

use \think\Request;
use app\util\Token;

class Auth
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
//       $tokenInfo = Request::header();
//       $token = $tokenInfo['token'];
//       if (empty($token)) {
//           return ajaxRuturn('ERR_CODE_LOGIN_OVERDUE');
//       } else {
//           $checkTokenRet =  Token::checkToken($token);
//           if (!empty($checkTokenRet)) {
//               return ajaxRuturn('ERR_CODE_LOGIN_OVERDUE');
//           }
//       }
       return $next($request);
    }
}
