<?php

namespace app\admin\controller\Login;

use app\Base;
use app\Request;
use app\admin\verification\Login\LoginLog as LoginLogVerification;
use app\admin\model\Login\LoginLog as LoginLogModel;
use app\admin\model\User\User as UserModel;


class LoginLog extends Base
{
    /**
     * 添加登录日志 | 登录成功时执行
     */
    public function CreateLoginLog(Request $request)
    {
        $params = $request->param();
        $validate = $this->ValidateParams($params, LoginLogVerification::$Add);
        if (!is_bool($validate))
            return $validate;
        $params = $this->BindGuid("login_log_guid", $params);
        $data = array_merge([
            "login_log_ip" => $this->getIP(),
        ], $params);
        LoginLogModel::create($data);
        return $this->Success("日志写入成功!");
    }
    /**
     * 统计登录日志人数 | 首页饼形图
     */
    public function GroupLoginLogPeople()
    {
        $logs = LoginLogModel::alias("ll")
            ->group("user_guid")
            ->field([
                "user_guid",
                "COUNT(user_guid)"=>"value",
            ])
            ->select()
            ->toArray();
        for ($v=0; $v < count($logs); $v++) { 
            $logs[$v]["name"] = UserModel::where("user_guid",$logs[$v]["user_guid"])->value("user_name");
        }
        return $this->Success("统计成功!", $logs);
    }
}