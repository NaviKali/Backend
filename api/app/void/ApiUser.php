<?php

namespace app\void;

use app\api\model\ApiUser as ModelApiUser;
use app\admin\model\Token as ModelToken;
use app\Tool;
use app\api\model\ApiUserFans as ModelApiUserFans;
use app\api\model\ApiUserMedia as ModelApiUserMedia;
use app\api\model\ApiMedia as ModelApiMedia;

// 应用请求对象类
class ApiUser
{
    public string $token = "";
    public string $userGuid = "";
    public function __construct()
    {
        $this->token = Tool::getToken();
        $this->userGuid = ModelToken::where(["token_value" => $this->token])->value("user_guid");
    }
    /**
     * Get ApiUser's Information
     * @access public
     * @author liulei
     * @return object|array
     */
    public function getApiUser(): object|array
    {
        return ModelApiUser::where(["apiuser_guid" => $this->userGuid])->find()->toArray();
    }
    /**
     * ApiUser信息-追加处理划
     * @access public
     * @author liulei
     * @param object|array $ApiUserInfo ApiUser信息 必填
     * @return object|array 追加后的数据
     */
    public function StartAppend(object|array $ApiUserInfo): object|array
    {

        /**
         * 获取视频总点赞量
         */
        $ApiUserInfo["videolikecount"] = (function () use ($ApiUserInfo) {
            return ModelApiMedia::join("apiuser_media", "apimedia.apimedia_guid = apiuser_media.apimedia_guid")
                ->join("apimedia_thumbs", "apimedia.apimedia_guid = apimedia_thumbs.apimedia_guid")
                ->where([
                    'apiuser_media.apiuser_guid' => $ApiUserInfo["apiuser_guid"],
                    ['apimedia_thumbs.delete_datetime',"=",null],
                ])
                ->field([
                    'COUNT(apimedia_thumbs.apimedia_guid) as count'
                ])
                ->group("apimedia_thumbs.apimedia_guid")
                ->find();
        })();


        /**
         * 获取粉丝和关注 总数
         * @var array
         */
        $ApiUserInfo["userfans"] = [
            'fanscount' => (function () use ($ApiUserInfo) {
                return ModelApiUserFans::getUserFansCount($ApiUserInfo["apiuser_guid"]);
            })(),
            'followcount' => (function () use ($ApiUserInfo) {
                return ModelApiUserFans::where([
                    'apiuserfans_current' => $ApiUserInfo["apiuser_guid"]
                ])->count();
            })(),
        ];

        return @$ApiUserInfo;
    }
}
