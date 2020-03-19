<?php
// 应用公共文件
use think\Loader;
Loader::addNamespace('data', Loader::getRootPath() . 'data' . DIRECTORY_SEPARATOR);
/**
 * 规定回复信息的格式的方法
 * @param  integer $code 信息码
 * @param  array  $data [description]
 * @return [type]       [description]
 */
function ajaxRuturn($code, $data = [])
{
    $result = ['code' => $code, 'msg' => getMessage($code)];
    $result = (!empty($data)) ? $result['data'] = $data : $result;
    return json($result);
}

/**
 * 获取信息码的稍息
 * @param  integer $code信息码
 * @return string       信息对应的稍息
 */
function getMessage($code){
    $info = config('message.info'); // array 数组
    return (array_key_exists($code, $info)) ? $info[$code] : '操作失败';
}

