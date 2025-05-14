<?php

namespace app\void;

use app\Tool;
use app\Base;
use app\admin\model\User\User as UserModel;
use app\admin\model\Token as TokenModel;

class User
{
    /**
     * 用户ID
     */
    public int $UserId;
    /**
     * 用户Guid
     */
    public string $UserGuid;
    /**
     * 用户所有信息
     */
    public array $UserInformation;
    /**
     * 用户职位
     */
    public string $UserRole;
    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->varGetUser();
    }
    /**
     * var给予数据
     * @author LL
     */
    protected function varGetUser()
    {
            $this->UserInformation = $this->getCurrentUserInformation();
            $this->UserId = $this->UserInformation['user_id'];
            $this->UserGuid = $this->UserInformation['user_guid'];
            $this->UserRole = $this->UserInformation["user_role_guid"];
    }
    /**
     * 当前用户是否有最高权限(超级管理员)
     * @author LL
     * @return bool
     */
    public function isCurrentUserHasHighestPower(): bool
    {
        return $this->UserRole == env("ADMIN_GUID") ? true : false;
    }
    /**
     * 获取当前用户所有信息
     * @author LL
     * @return mixed
     */
    public function getCurrentUserInformation(): mixed
    {
        $token = Tool::getToken();

        $UserGuid = TokenModel::where(['token_value' => $token])->value("user_guid");
        if (!$UserGuid)
            return(new Base)->Error("未获取到对应用户!");

        $User = UserModel::alias("us")
        ->join(["user_role" => "ur"],"ur.user_role_guid = us.user_role_guid","LEFT")
        ->join(["user_status"=>"ust"],"ust.user_status_guid = us.user_status_guid")
        ->where(['user_guid' => $UserGuid])->find()->toArray();
        return $User;
    }

}