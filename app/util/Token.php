<?php
namespace app\util;

use \Firebase\JWT\JWT;

class Token
{
    /**
     * 生成token
     */
    public static function createToken($userId)
    {
        $time = time();
        $token = array(
            "iss" => config('app.token_iss'),  //签发组织
            "aud" => config('app.token_aud'),  //签发作者
            "iat" => $time,
            "nbf" => $time,
            "exp" => $time + config('app.token_expire_time'),
            "data"=> [
                'id' => $userId
            ]
        );
        $jwt = JWT::encode($token, config('app.token_key'));
        return $jwt;
    }

    /**
     * 校验token
     */
    public static function checkToken($jwt)
    {
        $key = config('app.token_key');
        try {
            JWT::$leeway = 60;//当前时间减去60，把时间留点余地
            $decoded = JWT::decode($jwt, $key, ['HS256']); //HS256方式，这里要和签发的时候对应
            $arr = (array)$decoded;
        } catch(\Firebase\JWT\SignatureInvalidException $e) {  //签名不正确
            return $e->getMessage();
        }catch(\Firebase\JWT\BeforeValidException $e) {  // 签名在某个时间点之后才能用
            return $e->getMessage();
        }catch(\Firebase\JWT\ExpiredException $e) {  // token过期
            return $e->getMessage();
        }catch(Exception $e) {  //其他错误
            return $e->getMessage();
        }
        return[];
    }
}