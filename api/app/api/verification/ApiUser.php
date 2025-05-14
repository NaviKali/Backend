<?php
namespace app\api\verification;

class ApiUser
{
    /**
     * 添加
     */
    public static array $Add = [
        "apiuser_account" => "账号",
        "apiuser_password" => "密码",
    ];
    /**
     * Login
     */
    public static array $Login = [
        "code"=>"验证码",
        "apiuser_account" => "账号",
        "apiuser_password" => "密码",
    ];
    /**
     * 免密登录
     */
    public static array $SecretFreel = [
        "apiuser_account" => "账号",
        "apiuser_phone" => "手机号",
    ];
}