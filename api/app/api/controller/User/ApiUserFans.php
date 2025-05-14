<?php

namespace app\api\controller\User;

use app\Base;
use app\Request;
use think\response\Json;
use app\api\model\ApiUserFans as ModelApiUserFans;
use app\api\verification\ApiUserFans as VerificationApiUserFans;
use app\void\ApiUser as VoidApiUser;
use app\api\model\ApiUser as ModelApiUser;
use think\db\Query;

class ApiUserFans extends Base
{
    /**
     * 关注对方 | 取消关注
     * @access public
     * @author liulei
     * @api User.ApiUserFans/Follow
     * @param Request $request must
     * @see @param app\api\verification\ApiUserFans::$Follow
     * @return Json
     */
    public function Follow(Request $request): Json
    {
        $params = $request->param();
        $isValidate = $this->ValidateParams($params, VerificationApiUserFans::$Follow);
        if (!is_bool($isValidate))
            return $isValidate;

        //!不能关注自己
        if ($params["apiuserfans_current"] == $params["apiuserfans_target"])
            return $this->ApiError("不能关注自己哦!");


        $isFollow = ModelApiUserFans::where([
            "apiuserfans_current" => $params["apiuserfans_current"],
            "apiuserfans_target" => $params["apiuserfans_target"],
        ])->find();
        if ($isFollow) {
            $isFollow->delete();
            return $this->Success("取消关注成功!");
        }

        ModelApiUserFans::create($params);


        return $this->Success("关注成功!");
    }
    /**
     * 获取用户粉丝列表
     * @access public
     * @author liulei
     * @api User.ApiUserFans/FansUserList
     * @param Request $request must
     * @see @param app\api\verification\ApiUserFans::$Follow
     * @return Json
     */
    public function FansUserList(Request $request): Json
    {
        $params = $request->param();
        $con = [];

        //*获取当前用户Guid
        $CurrentUserGuid = (new VoidApiUser)->userGuid;

        $query = ModelApiUserFans::where($con)->where([
            'apiuserfans_target' => $CurrentUserGuid,
        ]);

        $count = $query->count();

        $list =  $this->PagePacka($query,$params)
            ->field([
                'apiuserfans_current',
                'apiuserfans_target',
                'create_datetime',
            ])
            ->select()
            ->filter(function ($v) {
                $v["target_info"] = ModelApiUser::where("apiuser_guid", $v["apiuserfans_target"])->field([
                    'apiuser_id',
                    'apiuser_guid',
                    'apiuser_name',
                    'apiuser_avater',
                ])->find() ?? [];

                if ($v["target_info"]) {
                    //*获取该用户下的粉丝总数
                    $v["target_info"]["fansCount"] = ModelApiUserFans::getUserFansCount($v["target_info"]["apiuser_guid"]);
                }
                return $v;
            })
            ->toArray();

        return $this->Success("获取成功!", ["list" => $list, "count" => $count]);
    }
    /**
     * 获取用户关注列表
     * @access public
     * @author liulei
     * @api User.ApiUserFans/FollowUserList
     * @param Request $request must
     * @see @param app\api\verification\ApiUserFans::$Follow
     * @return Json
     */
    public function FollowUserList(Request $request): Json
    {
        $params = $request->param();
        $con = [];

        //*获取当前用户Guid
        $CurrentUserGuid = (new VoidApiUser)->userGuid;


        $query = ModelApiUserFans::where($con)->where([
            'apiuserfans_current' => $CurrentUserGuid,
        ]);

        $count = $query->count();

        $list =  $this->PagePacka($query,$params)
            ->field([
                'apiuserfans_current',
                'apiuserfans_target',
                'create_datetime',
            ])
            ->select()
            ->filter(function ($v) {
                $v["target_info"] = ModelApiUser::where("apiuser_guid", $v["apiuserfans_target"])->field([
                    'apiuser_id',
                    'apiuser_guid',
                    'apiuser_name',
                    'apiuser_avater',
                ])->find() ?? [];

                if ($v["target_info"]) {
                    //*获取该用户下的粉丝总数
                    $v["target_info"]["fansCount"] = ModelApiUserFans::getUserFansCount($v["target_info"]["apiuser_guid"]);
                }
                return $v;
            })
            ->toArray();

        return $this->Success("获取成功!", ["list" => $list, "count" => $count]);
    }
}
