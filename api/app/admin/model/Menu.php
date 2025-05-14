<?php

namespace app\admin\model;

use think\Model;
use think\model\concern\SoftDelete;
use app\Tool;


class Menu extends Model
{

    use SoftDelete;
    protected $name = "menu";//表
    protected $pk = "menu_id";//键
    protected $schema = [
        'menu_id' => "int",
        'menu_guid' => 'varchar',
        'menu_father_guid'=>"varchar",
        'menu_name'=>"varchar",
        'menu_to'=>"varchar",
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