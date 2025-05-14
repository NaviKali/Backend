<?php

namespace app\admin\controller\Login;

use app\Base;
use app\Request;
use app\admin\verification\Login\Login as LoginVerification;
use app\admin\model\Login\Login as LoginModel;
use app\Tool;
use think\cache\Driver;

class Login extends Base
{
    /**
     * 账号密码登录
     * TODO管理员帐号密码登录
     * !输入的错误过多会导致禁止登录
     */
    public function AccountLogin(Request $request)
    {
        $params = $request->param();
        $validate = $this->ValidateParams($params, LoginVerification::$Login);
        if (!is_bool($validate))
            return $validate;

        //TODO密码格式验证
        $PassWordVerification = Tool::ForcePassWordVerification($params['password']);
        if (!is_bool($PassWordVerification))
            return $PassWordVerification;

        //*查找是否存在当前用户
        $con[] = ['login_account', '=', $params['account']];
        $con[] = ['login_password', '=', $params['password']];

        $findCurrentUser = LoginModel::where($con)->field([
            'login_guid',
            'user_guid',
            'login_status'
        ])->find();

        if (!$findCurrentUser)
            return $this->ApiError('当前用户不存在!');

        $findCurrentUser = $findCurrentUser->toArray();
        //?判断当前用户是否处于禁止登录状态
        $isLoginStatus = LoginModel::isCurrentUserProhibitLoginStatus($findCurrentUser['user_guid']);
        if ($isLoginStatus != true)
            return $this->Warning($isLoginStatus);

        $data = LoginModel::getToken($findCurrentUser);
        return $this->Success('登录成功!', $data);
    }
    /**
     * [用户状态默认为允许，0->允许,1->禁止]
     * 添加后台系统帐号
     */
    public function createAccountLogin(Request $request)
    {
        $params = $request->param();
        $params['login_status'] = 0;
        $validate = $this->ValidateParams($params, LoginVerification::$Add);
        if (!is_bool($validate))
            return $validate;
        $params = $this->BindGuid("login_guid", $params);
        LoginModel::create($params);
        return $this->Success("添加成功!");
    }



}