<?php

namespace app\api\controller\Media;

use app\Base;
use app\Request;
use app\api\model\ApiMedia as ModelApiMedia;
use app\api\model\ApiUserMedia as ModelApiUserMedia;
use app\api\model\ApiMediaType as ModelApiMediaType;
use app\api\model\ApiMediaTag as ModelApiMediaTag;
use app\api\verification\ApiMedia as VerificationApiMedia;
use think\response\Json;
use app\DictionaryMap;
use app\void\ApiUser as VoidApiUser;

class ApiMedia extends Base
{
    /**
     * Create ApiMeida
     * @access public
     * @api Media.ApiMedia/CreateApiMedia
     * @author liulei
     * @param Request $request must
     * @see @param app\api\verification\ApiMedia::$Add
     * @return Json
     */
    public function CreateApiMedia(Request $request):Json
    {
        $params = $request->param();
        $isValidate = $this->ValidateParams($params,VerificationApiMedia::$Add);
        if (!is_bool($isValidate)) 
            return $isValidate;

        //?Ver Have Type 
        $findType = ModelApiMediaType::where("apimedia_type_guid",$params["apimedia_type_guid"])->find();
        if(!$findType)
            return $this->ApiError("媒体类型不存在!");

        //*get ApiUser's Guid
        $ApiUserGuid = (new VoidApiUser)->userGuid;

        $params["apimedia_guid"] = $this->BindGuid("apimedia_guid",date("YmdHis"));

        
        $params["apimedia_type"] = $params["apimedia_type_guid"];

        $media = ModelApiMedia::create($params);

        //*Add ApiUserMedia
        ModelApiUserMedia::CreateApiUserMedia($ApiUserGuid,$media["apimedia_guid"]);

        return $this->Success("发布成功!");
    }
    /**
     * Get User's All Media 
     * @access public
     * @author liulei
     * @api Media.ApiMedia/GetUserAllMediaList
     * @param Request $request
     * @see @param app\api\verification\ApiMedia::$Add
     * @return Json
     */
    public function GetUserAllMediaList(Request $request)
    {
        $params = $request->param();
        $con = [];

        //*get ApiUser's Guid
        $UserGuid = (new VoidApiUser)->userGuid;

        $list = ModelApiUserMedia::join("apimedia","apiuser_media.apimedia_guid = apimedia.apimedia_guid")
        ->where([
            'apiuser_guid'=>$UserGuid,
        ])->field([
            'apimedia.apimedia_guid',
            'apiuser_guid',
            'apimedia_type',
            'apimedia_video',
            'apimedia_image',
            'apimedia_content',
            'apimedia_url',
            'apimedia_examined',
            'apimedia_examined_date',
            'apimedia_tag_id',
            'create_datetime',
        ])
        ->order('create_datetime','desc')
        ->select()
        ->filter(function($v)
        {
            //*审核状态
            $v["apimedia_examined_text"] = DictionaryMap::getApiDictionaryMap()["apimedia"][$v["apimedia_examined"]];

            //*get type
            $v["apimedia_type_text"] = ModelApiMediaType::where([
                'apimedia_type_guid'=>$v["apimedia_type"]
            ])->value("apimedia_type_name") ?? null;

            //*get tag
            $v["apimedia_tag_text"] = ModelApiMediaTag::whereIn('apimedia_tag_id',explode(',',$v['apimedia_tag_id']))
            ->column('apimedia_tag_content');


            return $v;
        })
        ->toArray();


        return $this->Success("获取成功!",$list);

    }

}
