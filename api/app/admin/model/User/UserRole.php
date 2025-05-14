<?php

namespace app\admin\model\User;

use think\Model;
use think\model\concern\SoftDelete;

class UserRole extends Model
{

      use SoftDelete;
      protected $name = "user_role";//表
      protected $pk = "user_role_id";//键
      protected $schema = [
            'user_role_id'=>"int",
            'user_role_guid'=>"varchar",
            'user_role_name'=>"varchar",
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