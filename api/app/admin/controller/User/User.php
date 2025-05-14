<?php

namespace app\admin\controller\User;

use app\Base;
use app\Request;
use app\admin\model\User\User as UserModel;
use app\admin\verification\User\User as UserVerification;
use app\void\User as UserVoid;

class User extends Base
{
      /**
       * 新建用户
       */
      public function CreateUser(Request $request)
      {
            $params = $request->param();
            $validate = $this->ValidateParams($params, UserVerification::$Add);
            if (!is_bool($validate))
                  return $validate;
            $params = $this->BindGuid('user_guid', $params);
            UserModel::create($params);
            return $this->Success('添加成功!');
      }
      /**
       * 获取当前用户信息
       */
      public function getCurrentUserInformation()
      {
            return $this->Success("当前用户信息获取成功！", (new UserVoid)->getCurrentUserInformation());
      }

}