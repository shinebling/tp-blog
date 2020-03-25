<?php
namespace app\controller;

use app\BaseController;
use think\Request;
use app\util\Token;
use app\service\Auth;
use app\model\Login as LoginModel;
use app\validate\LoginValidate;
use think\exception\ValidateException;
use \PHPMailer\PHPMailer;

class Login extends BaseController
{
    /**
     * @var \think\Request Request实例
     */
    protected $request;
    protected $param;

    /**
     * 构造方法
     * @param Request $request Request对象
     * @access public
     */
    public function __construct(Request $request)
    {
		$this->request = $request;
		$this->header = $request->header();
		$this->param = trimParms($this->request->param());
    }

    public function login()
    {
        try {
            validate(LoginValidate::class)->scene('login')->check($this->param);
            $user = LoginModel::where('account', $this->param['account'])->find();
            if (empty($user)) {
                return ajaxReturn(ERR_CODE_LOGIN,'用户不存在');
            } else {
                if(Auth::getMd5($this->param['password']) != $user->password) {
                    return ajaxReturn(ERR_CODE_LOGIN,'密码不正确');
                }
            }
            $userToken = Token::createToken($user->id);
            LoginModel::update(['rememberToken' =>  $userToken], ['id' => $user->id]);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            return ajaxReturn(ERR_CODE_LOGIN,$e->getError());
        }
        return ajaxReturn(SUCCESS,'',['token'=>Token::createToken($user->id)]);
    }

    public function register()
    {
        try {
            validate(LoginValidate::class)->check($this->param);
            $this->param['password'] = Auth::getMd5($this->param['password']);
            unset($this->param['confirmPassword']);
            $ret = LoginModel::register($this->param);
            if (!empty($ret)) {
                return ajaxReturn(ERR_CODE_REGISTER,$ret);
            }
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            return ajaxReturn(ERR_CODE_REGISTER,$e->getError());
        }
        return ajaxReturn(SUCCESS);
    }

    public function sendEmailCpatcha()
    {
        try {
            validate(LoginValidate::class)->scene('captcha')->check($this->param);
            $emailConfig = config('email');
            $captcha = substr(str_shuffle($emailConfig['captcha_character']),rand(0, strlen($emailConfig['captcha_character']) - 1),$emailConfig['captcha_length']);
            $toEmail = $this->param['email'];
            $emailCaptcha = time().'_'.$captcha;

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
            $mail->Body = sprintf($emailConfig['body'], $captcha);// 邮件标题

            //if(!$mail->send()){// 发送邮件
              //  return ajaxReturn(ERR_CODE_SEND_CAPTCHA,$mail->ErrorInfo);
            //}
            LoginModel::saveEmailCaptcha($this->param['account'], $emailCaptcha);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            return ajaxReturn(ERR_CODE_GET_CAPTCHA,$e->getError());
        } catch (Exception $e) {  //其他错误
            return ajaxReturn(ERR_CODE_SEND_CAPTCHA,$e->getMessage());
        }
        return ajaxReturn(SUCCESS);
    }

    /*
    * 找回密码
    */
    public function retrievePassword()
    {
        try {
             validate(LoginValidate::class)->scene('captcha')->check($this->param);
        // 验证有效期
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            return ajaxReturn(ERR_CODE_GET_CAPTCHA,$e->getError());
        } catch (Exception $e) {  //其他错误
            return ajaxReturn(ERR_CODE_SEND_CAPTCHA,$e->getMessage());
        }
        return ajaxReturn(SUCCESS);
    }
}
