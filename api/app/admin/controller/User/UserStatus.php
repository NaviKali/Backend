<?php

namespace app\admin\controller\User;

use app\Base;
use app\Request;
use app\admin\verification\User\UserStatus as UserStatusVerification;
use app\admin\model\User\UserStatus as UserStatusModel;

class UserStatus extends Base
{

    /**
     * 添加用户状态
     */
    public function CreateUserStatus(Request $request)
    {
        $params = $request->param();
        $validate = $this->ValidateParams($params, UserStatusVerification::$Add);
        if (!is_bool($validate))
            return $validate;
        $params = $this->BindGuid("user_status_guid", $params);
        UserStatusModel::create($params);
        return $this->Success("添加成功!");
    }


}