<?php

namespace app\api\model;

use think\Model;
use think\model\concern\SoftDelete;
use app\Tool;


class ApiMediaThumbs extends Model
{

    use SoftDelete;
    protected $name = "apimedia_thumbs";//表
    protected $pk = "apimedia_thumbs_id";//键
    protected $schema = [
        'apimedia_thumbs_id' => "int",
        'apimedia_thumbs_guid' => 'varchar',
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

   
}