<?php

namespace app\api\controller;

use app\DictionaryMap;
use app\Base;
use app\Request;
use app\api\model\ApiUser as ModelApiUser;
use app\api\verification\ApiUser as VerificationApiUser;
use app\admin\controller\Token as TokenController;
use app\admin\model\Token as TokenModel;
use app\api\model\ApiUserInformation as ModelApiUserInformation;
use app\Tool;
use think\captcha\facade\Captcha;
use think\facade\Session;
use think\response\Json;
use app\void\ApiUser as VoidApiUser;

class ApiUser extends Base
{
    /**
     * VerifyCode
     * @access public
     * @author liulei
     * @api ApiUser/VerifyCode
     * @return array|\think\Response
     */
    public function VerifyCode(): array|\think\Response
    {
        return Captcha::create();
    }
    /**
     * 免密登录
     * @access public
     * @author liulei
     * @api ApiUser/SecretFreeLogin
     * @param Request $request must
     * @see @param app\api\verification\ApiUser::$SecretFreel
     * @return Json
     */
    public function SecretFreeLogin(Request $request): Json
    {
        $params = $request->param();

        $isValidate = $this->ValidateParams($params, VerificationApiUser::$SecretFreel);
        if (!is_bool($isValidate))
            return $isValidate;

        //?是否存在对应账号
        $find = ModelApiUser::where([
            'apiuser_account' => $params['apiuser_account'],
            'apiuser_phone' => $params['apiuser_phone'],
        ])->find();

        if (!$find)
            return $this->ApiError("该账号不存在!");


        //*Create Token
        TokenController::CreateToken($find["apiuser_guid"]);
        $token = TokenModel::where(['user_guid' => $find["apiuser_guid"]])->value("token_value");

        return $this->Success("登录成功!",["token" => $token, "isADMIN" => false]);
    }
    /**
     * Create ApiUser
     * @access public
     * @param Request $request
     * @api ApiUser\CreateApiUser
     * @author liulei
     * @return Json
     */
    public function CreateApiUser(Request $request): Json
    {
        $params = $request->param();
        $isValidate = $this->ValidateParams($params, VerificationApiUser::$Add);
        if (!is_bool($isValidate))
            return $isValidate;


        //?Ver ApiUser is Have
        //!账号唯一性
        if (
            ModelApiUser::where([
                ["apiuser_account", "=", $params["apiuser_account"]],
            ])->find()
        ) {
            return $this->ApiError("该账号已被注册!");
        }

        /**
         * ?Ver Password
         */
        $isForce = Tool::ForcePassWordVerification($params["apiuser_password"]);
        if (!is_bool($isForce))
            return $isForce;


        $params = $this->BindGuid('apiuser_guid', $params);

        $user = ModelApiUser::create($params);
        ModelApiUserInformation::CreateApiUserInformation($params["apiuser_guid"]);

        return $this->Success("创建成功!");
    }
    /**
     * Get ApiUser Information
     * @access public
     * @param Request $request must
     * @api ApiUser/getApiUserInformation
     * @author liulei
     * @return Json
     */
    public function getApiUserInformation(Request $request): Json
    {
        $getUser = (new VoidApiUser)->getApiUser();
        $getUser = (new VoidApiUser)->StartAppend($getUser);
        return $this->Success("获取成功!", $getUser);
    }
    /**
     * Login ApiUser
     * @access public
     * @param Request $request 
     * @api ApiUser\LoginApiUser
     * @author liulei
     * @return Json
     */
    public function LoginApiUser(Request $request): Json
    {
        $params = $request->param();

        $isValidate = $this->ValidateParams($params, VerificationApiUser::$Login);
        if (!is_bool($isValidate))
            return $isValidate;

        //*Get Phone's ADMIN Config
        $EnvAccount = env("PHONE_ADMIN_ACCOUNT");
        $EnvPassword = env("PHONE_ADMIN_PASSWORD");


        //?Ver Code
        if (!captcha_check($params["code"]))
            return $this->ApiError("验证码错误!");



        //?Ver Phone's ADMIN
        if (
            $params["apiuser_account"] == $EnvAccount and $params["apiuser_password"] == $EnvPassword
        ) {
            $findAdmin = ModelApiUser::where([
                'apiuser_account' => $EnvAccount,
                'apiuser_password' => $EnvPassword,
            ])->value("apiuser_guid");
            //*Create Token
            TokenController::CreateToken($findAdmin);
            $token = TokenModel::where(['user_guid' => $findAdmin])->value("token_value");

            return $this->Success("登录成功!", ["token" => $token, "isADMIN" => true]);
        }

        //?Ver ApiUser is Have
        $find = ModelApiUser::where([
            ["apiuser_account", "=", $params["apiuser_account"]],
            ["apiuser_password", "=", $params["apiuser_password"]],
        ])->find();

        if (!$find)
            return $this->ApiError("账号密码错误!");


        //*Create Token
        TokenController::CreateToken($find["apiuser_guid"]);
        $token = TokenModel::where(['user_guid' => $find["apiuser_guid"]])->value("token_value");

        return $this->Success("登录成功!", ["token" => $token, "isADMIN" => false]);
    }
}
