<?php

namespace app\admin\controller\Login;

use app\Base;
use app\Request;
use app\admin\model\Login\LoginType as LoginTypeModel;
use app\admin\verification\Login\LoginType as LoginVerificationModel;


class LoginType extends Base
{
    /**
     * 添加登录类型
     */
    public function CreateLoginType(Request $request)
    {
        $params = $request->param();
        $validate = $this->ValidateParams($params, LoginVerificationModel::$Arr);
        if (!is_bool($validate))
            return $validate;
        $params = $this->BindGuid('login_type_guid', $params);
        LoginTypeModel::create($params);
        return $this->Success('添加成功!');
    }

}