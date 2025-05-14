<?php

namespace app\admin\controller\User;

use app\Base;
use app\admin\model\User\UserRole as UserRoleModel;
use app\Request;
use app\admin\verification\User\UserRole as UserVerificationModel;
use app\Tool;


class UserRole extends Base
{
    /**
     * 添加用户职位
     * @example
     * @author LL
     */
    public function CreateUserRole(Request $request)
    {
        $params = $request->param();
        $validate = $this->ValidateParams($params, UserVerificationModel::$Add);
        if (!is_bool($validate))
            return $validate;
        $params = $this->BindGuid('user_role_guid', $params);
        UserRoleModel::create($params);
        return $this->Success('添加成功!');
    }


}