<?php

namespace app\api\controller\Media;

use app\Base;
use app\Request;
use think\response\Json;
use app\api\model\ApiMediaType as ModelApiMediaType;
use app\api\verification\ApiMediaType as VerificationApiMediaType;



class ApiMediaType extends Base
{
    /**
     * Create ApiMediaType
     * @access public
     * @api Media.ApiMediaType/CreateApiMediaType
     * @author liulei
     * @param Request $request must
     * @see @param app\api\verification\ApiMediaType::$Add
     * @return Json
     */
    public function CreateApiMediaType(Request $request):Json
    {
        $params = $request->param();
        $isValidate = $this->ValidateParams($params,VerificationApiMediaType::$Add);
        if(!is_bool($isValidate))
            return $isValidate;

        $params = $this->BindGuid('apimedia_type_guid',$params);

        $find = ModelApiMediaType::where([
            'apimedia_type_name'=>$params["apimedia_type_name"],
        ])->find();
        if($find)
            return $this->ApiError("该类型已存在!");

        ModelApiMediaType::create($params);

        return $this->Success("添加成功!");
    }
    /**
     * Get ApiMediaType List
     * @access public
     * @author liulei
     * @api Media.ApiMediaType/GetApiMediaTypeList
     * @param Request $request must
     * @see @param app\api\verification\ApiMediaType::$GetApiMediaTypeList
     * @return Json
     */
    public function GetApiMediaTypeList(Request $request):Json
    {
        $con = [];
        $list = ModelApiMediaType::where($con)->field([
            'apimedia_type_guid',
            'apimedia_type_name',
        ])->select()->toArray();

        return $this->Success("获取成功!",$list);

    }
}