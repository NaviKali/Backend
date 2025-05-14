<?php

namespace app\admin\verification\User;


class User 
{
      /**
       * 添加
       */
      public static array $Add = [
            "user_name"=>"用户名字",
            "user_sex"=>"用户性别",
            "user_role_guid"=>"用户职位Guid",
            "user_status_guid"=>"用户状态Guid"
      ];
      /**
       * 获取当前用户信息
       */
      public static array $getCurrentUserinformation = [
            "user_guid"=>"用户Guid",
      ];

}