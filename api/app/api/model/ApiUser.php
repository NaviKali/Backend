<?php

namespace app\api\model;

use think\Model;
use think\model\concern\SoftDelete;
use app\Tool;


class ApiUser extends Model
{

    use SoftDelete;
    protected $name = "apiuser";//表
    protected $pk = "apiuser_id";//键
    protected $schema = [
        'apiuser_id' => "int",
        'apiuser_guid' => 'varchar',
        'apiuser_account'=>"varchar",
        'apiuser_password'=>"varchar",
        'apiuser_name'=>"varchar",
        'apiuser_phone'=>"varchar",
        'apiuser_email'=>"varchar",
        'apiuser_status'=>"varchar",
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