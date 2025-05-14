<?php

namespace app\admin\controller;

use app\Base;
use app\Request;
use app\Tool;
use app\admin\model\Token as TokenModel;
use app\admin\verification\Token as TokenVerification;

class Token extends Base
{
    /**
     * 创建Token | 登录成功后使用该函数
     * @access public
     * @param string $userguid 用户Guid 必填
     * @return void
     */
    public static function CreateToken(string $userguid): void
    {
        $token = Tool::Salt(uniqid(time()));

        $exportTime = date("Y-m-d h:i:s");
        $exportTime = date("Y-m-d h:i:s", strtotime($exportTime . "+2 day"));

        $data = ['token_value' => $token, "token_export_time" => $exportTime, "token_guid" => md5($token), "user_guid" => $userguid];

        $isExistToken = TokenModel::IsExistToken(['user_guid' => $userguid]);
        if (!$isExistToken)
            TokenModel::create($data);
        TokenModel::where(['user_guid' => $userguid])->save($data);
    }
    /**
     * 判断当前Token是否有效
     */
    public function IsValidCurrentToken(Request $request)
    {
        $params = $request->param();
        $validate = $this->ValidateParams($params, TokenVerification::$isValid);
        if (!is_bool($validate))
            return $validate;
        /**
         * 判断当前Token是否超时
         */
        $isTimeOutToken = TokenModel::IsTokenTimeOut($params['token_value']);
        if ($isTimeOutToken) {
            return $this->Success('当前Token有效!');
        } else {
            return $this->ApiError('当前Token不可用!');
        }
    }

}