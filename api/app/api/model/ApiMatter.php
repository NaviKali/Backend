<?php

namespace app\api\model;

use think\Model;
use think\model\concern\SoftDelete;
use app\Tool;


class ApiMatter extends Model
{

    use SoftDelete;
    protected $name = "apimatter";//表
    protected $pk = "apimatter_id";//键
    protected $schema = [
        'apimatter_id' => "int",
        'apimatter_guid' => 'varchar',
        'apimatter_content' => 'varchar',
        'apimatter_img' => 'varchar',
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

   
}