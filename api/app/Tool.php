<?php

namespace app;

use app\void\header;
use app\Base;
use think\response\Json;

/**
 * 工具类
 */
class Tool
{
    /**
     * 参码
     */
    protected int $code = 200;
    /**
     * 重定向
     * @param string $url 必填
     * @param int $code 参码 选填 默认为 200
     * @return mixed
     */
    public static function Redirect(string $url, int $code = 200): mixed
    {
        $header = (new header());

        $header->code = $code;
        return $header->SetHeader("Location", $url);
    }
    /**
     * 获取Token
     * @return mixed Token值
     */
    public static function getToken(): mixed
    {
        return apache_request_headers()['Token'] ?? (new Base())->Error("请求头没有携带Token!");
    }
    /**
     * 获取完整当前Url
     * @return string 完整当前Url
     */
    public static function getCompleteCurrentUrl(): string
    {
        $host = $_SERVER['HTTP_HOST'];
        return "http://" . $host . $_SERVER['REQUEST_URI'];
    }
    /**
     * 获取当前函数
     * @return string 当前执行的函数名称
     */
    public static function getCurrentFunction(): string
    {
        $Url = $_SERVER['REQUEST_URI'];
        //*去除GET模式的参数
        $Url = explode('?', $Url)[0];
        //*得到当前调用函数名
        $Url = explode('/', $Url);
        $functionName = $Url[count($Url) - 1];
        return $functionName;
    }
    /**
     * 获取当前时间
     * @access public
     * @static
     * @return string
     */
    public static function getCurrentTime(): string
    {
        return date("Y-m-d h-i-s");
    }
    /**
     * 解析路由式传参
     * @static
     * @access public
     * @param string|array $params 参数 必填
     * @return string|array 解析后的参数
     */
    public static function UrlDecode(string|array $params): string|array
    {
        $value = &$params;
        $con = [];
        if (is_string($value))
            return urldecode($value);
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                array_push($con, urldecode($v));
            }
            return $con;
        }
    }
    /**
     * Base64解码(推荐使用)
     * @static
     * @access public
     * @param string|array $params 参数 必填
     * @return string|array 解析后的参数
     */
    public static function BaseDecode(string|array $params): string|array
    {
        $value = &$params;
        $con = [];
        if (is_string($value))
            return base64_decode($value);

        if (is_array($value)) {
            foreach ($value as $k => $v) {
                array_push($con, base64_decode($v));
            }
            return $con;
        }
    }
    /**
     * 加密盐
     * @access public
     * @static
     * @param string $data 值 选填 默认为 当前时间
     * @param bool $isStartLimitation 是否开启限制 默认为 false
     * @param int $max 最大值 选填 默认为 200
     * @return string 盐
     */
    public static function Salt(string $data, bool $isStartLimitation = false, int $max = 200): string
    {
        return self::MakeSalt($data, $isStartLimitation, $max);
    }
    /**
     * 强制密码类型格式验证
     * @static
     * @access public
     * @param string $password 密码 必填
     * @return mixed
     */
    public static function ForcePassWordVerification(string $password): mixed
    {
        //?判断密码是否长度是否合理
        if (strlen($password) <= 8)
            return Base::Message(404, "密码长度过短！");
        //?判断密码是否遵循条件
        if (!preg_match("/[0-9]/", $password) or !preg_match("/[a-z]/", $password))
            return Base::Message(404, "弱密码禁止登录！");
        return true;
    }
    /**
     * 生成盐
     * @access private
     * @static
     * @param string $data 值 选填 默认为 当前时间
     * @param bool $isStartLimitation 是否开启限制 默认为 false
     * @param int $max 最大值 选填 默认为 200
     * @return string 盐
     */
    private static function MakeSalt(string $data = '', bool $isStartLimitation = false, int $max = 200): string
    {
        if (!$data)
            $data = self::getCurrentTime();
        $salt = md5($data) . '&&' . trim($data);
        $salt = base64_encode(urlencode($salt));
        return $isStartLimitation ? substr($salt, 0, $max) : $salt;
    }
    /**
     * 解析盐
     * @access public 
     * @static
     * @param string $salt 盐 必填
     * @return string
     */
    public static function SaltDecode(string $salt): string
    {
        $salt = base64_decode($salt);
        $salt = urldecode($salt);
        //?判断是否盐中是否存在 &&
        if (!str_contains($salt, '&&'))
            return Base::Message(500, '解析失败！(盐出现问题)');
        $salt = explode('&&', $salt)[1];
        return trim($salt);
    }
    /**
     * 路由虚幻
     * TODO生成随机GET请求参数[障眼法]
     * @return string
     */
    public static function RouterUnreal(): string
    {
        $before = self::MakeRouterParams(isStartUnreal: true);
        return self::getCompleteCurrentUrl() . $before;
    }
    /**
     * 路由后缀参数生成
     * @access public
     * @param array $data 参数+值 键值对 选填
     * @param bool $isStartUnreal 是否开启虚幻模式 选填 默认为 false
     * @return string
     */
    public static function MakeRouterParams(array $data = [], bool $isStartUnreal = false): string
    {
        $str = '';
        if (!$isStartUnreal) {

            foreach ($data as $k => $v) {
                $str .= "$k=$v&";
            }
        } else {

            $length = rand(5, 10);
            $str = Tool::Salt(time());
            $url = "";
            for ($i = 0; $i < $length; $i++) {
                $keyStart = rand(1, 5);
                $valueStart = rand(6, 15);
                $StartStr = substr($str, $keyStart, $valueStart);
                $keyEnd = rand(1, 5);
                $valueEnd = rand(6, 15);
                $EndStr = substr($str, $keyEnd, $valueEnd);
                $url .= "$StartStr=$EndStr&";
            }
        }

        $end = $isStartUnreal ? $url . "isStartUnreal=true" : substr($str, 0, strlen($str) - 1);
        return "?" . $end;

    }
    /**
     * 文件生成
     * @access public
     * @static 
     * @param string $filename 文件路径或文件名称 必填
     * @param string $data 写入内容 选填 默认为""
     * @return bool
     */
    public static function MakeFile(string $filename, string $data = ""): bool
    {
        if (!file_exists($filename)) {
            $file = fopen($filename, "w");
            fwrite($file, $data);
            return true;
        } else {
            return false;
        }
    }

}