<?php
// 应用公共文件
/**
 * 规定回复信息的格式的方法
 * @param  integer $code 信息码
 * @param  array  $data [description]
 * @return [type]       [description]
 */
function ajaxReturn($code, $msg = '', $data = []){
    $messageCode = config('message.code');
    if (isset($messageCode[$code])) {
        $code = $messageCode[$code];
    }
    $result = ['code' => $code, 'message' => getMessage($code).(empty($msg) ? $msg : "：".$msg)];
    $result['data'] = empty($data) ? [] : $data;
    return json()->data($result);
}

/**
 * 获取信息码的稍息
 * @param  integer $code信息码
 * @return string       信息对应的稍息
 */
function getMessage($code){
    $messageInfo = config('message.info'); // array 数组
    return (array_key_exists($code, $messageInfo)) ? $messageInfo[$code] : '未知错误';
}

/**
 * @param $param  array
 * 去除参数中的所有空格
 */
function trimParms($param){
    if (!is_array($param)) {
        $param[] = $param;
    }
    foreach ($param as &$item) {
        $item = preg_replace('# #','',$item);
    }
    return $param;
}


