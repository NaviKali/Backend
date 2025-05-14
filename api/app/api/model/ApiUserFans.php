<?php

namespace app\api\model;

use think\Model;
use think\model\concern\SoftDelete;
use app\Tool;


class ApiUserFans extends Model
{

    use SoftDelete;
    protected $name = "apiuserfans"; //表
    protected $pk = "apiuserfans_id"; //键
    protected $schema = [
        'apiuserfans_id' => "int",
        'apiuserfans_guid' => 'varchar',
        'apiuserfans_current' => "varchar",
        'apiuserfans_target' => "varchar",
        'create_datetime' => "datetime",
        'update_datetime' => "datetime",
        'delete_datetime' => "datetime",

    ]; //定义字段信息

    protected $type = []; //字段转换类型
    protected $disuse = []; //废弃字段
    protected $json = []; //JSON字段
    protected $readonly = []; //只读字段
    protected $jsonAssoc = true; //JSON数据返回数组
    protected $autoWriteTimestamp = 'datetime';
    protected $deleteTime = "delete_datetime";


    /**
     * 获取用户下的粉丝总数
     * @access public
     * @static
     * @author liulei
     * @param string $apiuser_guid API用户Guid 必填
     * @return int
     */
    public static function getUserFansCount(string $apiuser_guid): int
    {
        return self::where([
            'apiuserfans_target' => $apiuser_guid,
        ])->count();
    }
}
