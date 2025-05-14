<?php

namespace app\admin\model;

use think\Model;
use think\model\concern\SoftDelete;
use app\Tool;


class Token extends Model
{

    use SoftDelete;
    protected $name = "token";//表
    protected $pk = "token_id";//键
    protected $schema = [
        'token_id' => "int",
        'token_guid' => 'varchar',
        'token_value' => "varchar",
        'token_export_time' => "datetime",
        'user_guid' => "int",
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
     * ?Token是否存在
     * @return bool 是否存在
     * @param array $where 筛选条件 必填
     */
    public static function IsExistToken(array $where): bool
    {
        $find = self::where($where)->find();
        if (!$find)
            return false;
        return true;
    }
    /**
     * ?Token验证是否超时
     * @param string $token Token值 必填
     * @return bool 是否超时
     */
    public static function IsTokenTimeOut(string $token): bool
    {
        //*获取Token
        $getToken = self::where(['token_value'=>$token])->value('token_value');
        if (!$getToken)
            return false;
        return true;
    }
    /** 
     * ?Token验证是否过期
     * @return bool 是否过期
     */
    public static function IsVerifyTokenExpire(string $token): bool
    {
        //*获取当前时间
        $currentTime = Tool::getCurrentTime();
        $getExportTime = self::where(['token_value'=>$token])->value("token_export_time");
        if ($currentTime > $getExportTime)
            return false;
        return true;
    }

}