<?php

namespace app\api\model;

use think\Model;
use think\model\concern\SoftDelete;
use app\Tool;


class ApiMediaTag extends Model
{

    use SoftDelete;
    protected $name = "apimedia_tag";//表
    protected $pk = "apimedia_tag_id";//键
    protected $schema = [
        'apimedia_tag_id' => "int",
        'apimedia_tag_guid' => 'varchar',
        'apimedia_tag_content'=>"varchar",
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