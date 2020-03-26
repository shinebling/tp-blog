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

function sendEamil($toEmail, $captcha = '')
{
    $emailConfig = config('email');
    $mail = new \PHPMailer\PHPMailer\PHPMailer();

    $mail->isSMTP();// 使用SMTP服务
    $mail->CharSet = $emailConfig['charset'];   // 编码格式为utf8，不设置编码的话，中文会出现乱码
    $mail->Host = $emailConfig['host'];// 发送方的SMTP服务器地址
    $mail->SMTPAuth =$emailConfig['smtp_auth'];// 是否使用身份验证
    $mail->Username = $emailConfig['user_name'];// 发送方的163邮箱用户名
    $mail->Password = $emailConfig['password'];// 客户端授权密码
    $mail->SMTPSecure = $emailConfig['smtp_secure'];// 使用ssl协议方式
    $mail->Port = $emailConfig['port'];// 163邮箱的ssl协议方式端口号是465/994

    $mail->setFrom($emailConfig['user_name'],"xuanzhu");// 设置发件人信息
    $mail->addAddress($toEmail,'收件');// 设置收件人信息
    $mail->addReplyTo($toEmail,"回复");// 设置回复人信息

    $mail->Subject = $emailConfig['subject'];// 邮件标题
    if (!empty($captcha)){
        $mail->Body = sprintf($emailConfig['body'], $captcha);// 邮件标题
    }

    if(!$mail->send()){// 发送邮件
        return ajaxReturn(ERR_CODE_SEND_CAPTCHA,$mail->ErrorInfo);
    }
}

