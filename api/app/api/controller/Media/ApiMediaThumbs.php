<?php

namespace app\api\controller\Media;

use app\Base;
use app\Request;
use app\api\verification\ApiMediaThumbs as VerificationApiMediaThumbs;
use app\api\model\ApiMediaThumbs as ModelApiMediaThumbs;
use think\response\Json;


class ApiMediaThumbs extends Base
{
    /**
     * Thumbs | Close Thumbs
     * @access public
     * @api Media.ApiMediaThumbs/Thumbs
     * @return Json
     */
    public function Thumbs(Request $request): Json
    {
        $params = $request->param();
        $isValidate = $this->ValidateParams($params, VerificationApiMediaThumbs::$Thumbs);
        if (!is_bool($isValidate))
            return $isValidate;

        $params["apimedia_thumbs_guid"] = $this->BindGuid("apimedia_thumbs_guid", date("YmdHis"));


        /**
         * 点赞原则：一个人只能点赞一个视频，如果存在则认为是取消点赞
         * @var mixed
         */
        $find = ModelApiMediaThumbs::where(
            ["apiuser_guid" => $params["apiuser_guid"], "apimedia_guid" => $params["apimedia_guid"]]
        )->find();
        if ($find) {
            $find->delete();
            return $this->Success("取消成功!");
        }

        ModelApiMediaThumbs::create($params);

        return $this->Success("点赞成功!");
    }
}
