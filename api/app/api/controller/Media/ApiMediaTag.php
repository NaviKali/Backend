<?php

namespace app\api\controller\Media;

use app\Base;
use app\Request;
use think\response\Json;
use app\api\verification\ApiMediaTag as VerificationApiMediaTag;
use app\api\model\ApiMediaTag as ModelApiMediaTag;


class ApiMediaTag extends Base
{
        /**
         * Create ApiMediaTag
         * @access public
         * @author liulei
         * @api Media.ApiMediaTag/CreateApiMediaTag
         * @param Request $request must
         * @see @param app\api\verification\ApiMediaTag::$Add
         * @return Json
         */
        public function CreateApiMediaTag(Request $request):Json
        {
            $params = $request->param();
            $isValidate = $this->ValidateParams($params,VerificationApiMediaTag::$Add);
            if(!is_bool($isValidate))
                return $isValidate;

            $find = ModelApiMediaTag::where("apimedia_tag_content",$params["apimedia_tag_content"])->find();
            if($find)
                return $this->ApiError("该标签已存在!");

            $params = $this->BindGuid("apimedia_tag_guid",$params);

            ModelApiMediaTag::create($params);

            return $this->Success("添加成功!");

        }
        /**
         * Get ApiMediaTag List
         * @access public
         * @author liulei
         * @api Media.ApiMediaTag/GetApiMediaTag
         * @param Request $request must
         * @return Json
         */
        public function GetApiMediaTag(Request $request):Json
        {
            $con = [];
            $list = ModelApiMediaTag::where($con)->field([
                'apimedia_tag_id',
                'apimedia_tag_content',
            ])->select();

            return $this->Success("获取成功!",["data"=>$list]);
        }
}