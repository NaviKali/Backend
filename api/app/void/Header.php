<?php

namespace app\void;

use app\Base;
use app\Tool;
use app\void\User;
use think\exception\HttpException;

class Header
{
    /**
     * 参码
     */
    public int $code = 250;

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->InitHeader();
    }
    /**
     * init Header
     * @author LL
     */
    public function InitHeader()
    {
        header("Content-Type:application/json");
        header("Access-Control-Allow-Origin:*");
        header("Access-Control-Allow-Methods:*");
        header("Access-Control-Request-Headers:*");
    }
    /**
     * 设置请求头
     * @access public
     * @author LL
     * @example header
     * @param string $key 请求头键 必填
     * @param string $value 请求头值 必填
     * @return mixed
     */
    public function SetHeader(string $key, string $value): mixed
    {
        $arr = [];
        $args = func_get_args();
        foreach ($args as $k => $v) {
            $arr[$k] = trim($v);
        }

        $isCodeAllow = $this->isCodeAllow($this->code);
        if (is_string($isCodeAllow))
            return(new Base)->Warning($isCodeAllow);
        if (!$isCodeAllow)
            return(new Base)->Warning("该用户没有权限修改!");

        header("$arr[0] : $arr[1]");

        return(new Base())->Warning("修改成功!");
    }
    /**
     * ?判断当前参码是否允许
     * @access public
     * @author LL
     * @example header
     * @param int $code 参码 必填
     * @return bool|string
     */
    protected function isCodeAllow(int $code): bool|string
    {
        $isCurrentUserHasHighestPower = (new User())->isCurrentUserHasHighestPower();
        if (!$isCurrentUserHasHighestPower) {
            return false;
        }
        if ($code != 200) {
            return "请求错误!";
        }
        return true;
    }

}