<?php

namespace app\admin\controller;

use app\Base;
use app\Request;
use app\admin\model\Menu as MenuModel;
use app\admin\verification\Menu as MenuVerification;

class Menu extends Base
{

    /**
     * 添加菜单
     */
    public function CreateMenu(Request $request)
    {
        $params = $request->param();
        $validate = $this->ValidateParams($params, MenuVerification::$Add);
        if (!is_bool($validate))
            return $validate;

        $params = $this->BindGuid("menu_guid", $params);

        //?判断是否传入父级菜单Guid | 判断是否创建子菜单
        if (!isset($params["menu_father_guid"]))
            $params["menu_father_guid"] = null;

        MenuModel::create($params);
        return $this->Success("添加成功!");

    }
    /**
     * 获取菜单列表 | 树
     */
    public function getMenuTree()
    {
        $menulist = MenuModel::field([
            'menu_guid',
            'menu_name',
            'menu_to',
            'create_datetime',
        ])->select()
            ->order('create_datetime', 'asc')
            ->toArray();
        return $this->Success("获取菜单树成功!",$menulist);
    }


}