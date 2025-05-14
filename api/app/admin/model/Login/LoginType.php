<?php
namespace app\admin\model\Login;

use app\Base;
use app\Request;
use think\Model;
use think\model\concern\SoftDelete;

class LoginType extends Model
{
    use SoftDelete;
    protected $name = "login_type";//表
    protected $pk = "login_type_id";//键
    protected $schema = [
        'login_type_id' => "int",
        'login_type_guid' => "varchar",
        'login_type_name' => "varchar",
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
    public static function onAfterRead()
    {

    }
    public static function onAfterUpdate()
    {

    }
    public static function onAfterWrite()
    {

    }
    public static function onAfterDelete()
    {

    }

}