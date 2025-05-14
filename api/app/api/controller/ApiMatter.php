<?php

namespace app\api\controller;

use app\Base;
use app\Request;
use think\response\Json;
use app\api\model\ApiMatter as ModelApiMatter;
use app\api\verification\ApiMatter as VerificationApiMatter;

class ApiMatter extends Base
{
    /**
     * Create ApiMatter
     * @access public
     * @author liulei
     * @api ApiMatter/CreateApiMatter
     * @param Request $request must
     * @see @param app\api\verification\ApiMatter::$Add
     * @return Json
     */
    public function CreateApiMatter(Request $request): Json
    {
        $params = $request->param();
        $isValidate = $this->ValidateParams($params, VerificationApiMatter::$Add);
        if (!is_bool($isValidate))
            return $isValidate;
        $params["apimatter_guid"] = $this->BindGuid("apimatter_guid", date("Y-m-d H:i:s"));

        ModelApiMatter::create($params);

        return $this->Success("添加成功!");
    }
    /**
     * Get ApiMatter List
     * @access public
     * @author liulei
     * @api ApiMatter/GetAPiMatterList
     * @param Request $request must
     * @return Json
     */
    public function GetAPiMatterList(Request $request): Json
    {
        $params = $request->param();
        $con = [];


        $count = ModelApiMatter::count();

        $list = $this->PagePacka(ModelApiMatter::field([
            'apimatter_guid',
            'apimatter_content',
            'apimatter_img',
        ]),$params)->select()
            ->filter(function ($v) {
                $item = explode('&&', $v["apimatter_content"]);
                $v["title"] = $item[0];
                $v["content"] = $item[1];
                return $v;
            })->toArray();


        return $this->Success("获取成功!", ["list" => $list, "count" => $count]);
    }
}
