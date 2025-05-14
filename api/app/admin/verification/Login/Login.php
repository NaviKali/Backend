<?php

namespace app\admin\verification\Login;


class Login
{
    /**
     * 添加时候
     */
    public static array $Add = [
        "login_account"=>"用户账号",
        "login_password"=>"用户密码",
        "login_status"=>"账号状态",
        "login_type_guid"=>"账号类型Guid",
        "user_guid"=>"账号所属用户Guid",
    ];
    /**
     * 登录时
     */
    public static array $Login = [
        "account"=>"用户账号",
        "password"=>"用户密码",
    ];
}