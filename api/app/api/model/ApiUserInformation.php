<?php

namespace app\api\model;

use think\Model;
use think\model\concern\SoftDelete;
use app\Tool;
use app\api\model\ApiUserInformation as ModelApiUserInformation;
use app\Base;

class ApiUserInformation extends Model
{

    use SoftDelete;
    protected $name = "apiuser_information";//表
    protected $pk = "apiuser_information_id";//键
    protected $schema = [
        'apiuser_information_id' => "int",
        'apiuser_information_guid' => 'varchar',
        'apiuser_guid' => "varchar",
        'apiuser_information_thumbsup' => "int",
        'apiuser_information_follow' => "varchar",
        'apiuser_information_fans' => "varchar",
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
     * Create ApiUser's Information
     * @param string $apiuser_guid must
     * @return void
     */
    public static function CreateApiUserInformation(string $apiuser_guid): void
    {
        $data = [];
        $data["apiuser_information_guid"] = (new Base())->BindGuid('apiuser_information_guid', $apiuser_guid);
        $data["apiuser_guid"] = $apiuser_guid;
        ModelApiUserInformation::create($data);
    }


}