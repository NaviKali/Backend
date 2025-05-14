<?php

namespace app\api\model;

use app\Base;
use think\Model;
use think\model\concern\SoftDelete;
use app\Tool;


class ApiUserMedia extends Model
{

    use SoftDelete;
    protected $name = "apiuser_media";//表
    protected $pk = "apiuser_media_id";//键
    protected $schema = [
        'apiuser_media_id' => "int",
        'apiuser_media_guid' => 'varchar',
        'apiuser_guid'=>"varchar",
        'apimedia_guid'=>"varchar",
        'create_datetime' => "datetime",
        'update_datetime' => "datetime",
        'delete_datetime' => "datetime",

    ];//定义字段信息

    protected $type = [];//字段转换类型
    protected $disuse = [];//废弃字段
    protected $json = [];//JSON字段
    protected $readonly = [];//只读字段
    protected $jsonAssoc = true;//JSON数据返回数组
    protected $autoWriteTimestamp = 'datetime';
    protected $deleteTime = "delete_datetime";

    /**
     * Create ApiUserMedia
     */
    public static function CreateApiUserMedia(string $apiUserGuid,string $mediaGuid):void
    {
        self::create([
            'apiuser_media_guid'=>(new Base)->BindGuid('apiuser_media_guid',$mediaGuid),
            'apiuser_guid'=>$apiUserGuid,
            'apimedia_guid'=>$mediaGuid,
        ]);
    }

   
}