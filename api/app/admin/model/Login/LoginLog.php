<?php
namespace app\admin\model\Login;

use think\Model;
use think\model\concern\SoftDelete;

class LoginLog extends Model
{
    use SoftDelete;
    protected $name = "login_log";//表
    protected $pk = "login_log_id";//键
    protected $schema = [
        'login_log_id' => "int",
        'login_log_guid' => "varchar",
        'login_log_ip' => "varchar",
        'user_guid' => "varchar",
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