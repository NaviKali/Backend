<?php

namespace app;

use Fiber;
use think\Model;
use app\Tool;


class Base extends BaseController
{
    /**
     * Page包装器
     * @access public
     * @param mixed $query Query 查询句段 必填
     * @param array $params 请求参数 必填
     * @return mixed
     */
    final public function PagePacka(mixed $query,array $params): mixed
    {
        $params["page"] = empty($params["page"]) ? 1 : $params["page"];
        $params["limit"] = empty($params["limit"]) ? 10 : $params["limit"];

        return $query->page($params["page"], $params["limit"]);
    }
    /**
     * 验证规则
     * @access public
     * @param array $params 参数 必填
     * @param array $validate 验证 必填
     * @return mixed
     */
    final public function ValidateParams(array $params, array $validate): mixed
    {
        foreach ($validate as $k => $v) {
            if (empty($params[$k])) {
                return $this->ApiError("{$v}不能为空!");
            }
        }
        return true;
    }
    /**
     * 前台错误
     * @access public
     * @param string $message 提示内容 必填
     * @return mixed
     */
    final public function ApiError(string $message): mixed
    {
        return $this->Message(444, $message);
    }
    /**
     * 强制扔出错误
     * @access public
     * @param string $message 提示内容 必填
     * @return mixed
     */
    final public function Error(string $message): mixed
    {
        return abort(500, $message);
    }
    /**
     * 成功提示
     * @access public
     * @param string $message 返回提示内容 必填
     * @param array  $data 返回数据内容 选填 默认为 []
     * @return mixed
     */
    final public function Success(string $message, array $data = []): mixed
    {
        return $this->Message(200, $message, $data);
    }
    /**
     * 警告提示
     * @access public
     * @param string $message 返回提示内容 必填
     * @return mixed
     */
    final public function Warning(string $message): mixed
    {
        return $this->Message(404, $message);
    }
    /**
     * 获取当前执行函数名称
     * @access public
     * @static
     * @return string
     */
    final public static function getCurrentFunctionName(): string
    {
        $Url = $_SERVER['REQUEST_URI'];
        //*去除GET模式的参数
        $Url = explode('?', $Url)[0];
        //*得到当前调用函数名
        $Url = explode('/', $Url);
        $Url = $Url[count($Url) - 1];
        return $Url;
    }
    /**
     * 登录超时
     * @return mixed
     */
    final public  function LoginTimeOut(): mixed
    {
        return $this->Message(300, "登录超时");
    }
    /**
     * Message提示框
     * @access public
     * @static
     * @param int $code 参码 必填
     * @param string $message 返回提示内容 必填
     * @param array  $data 返回数据内容 选填 默认为 []
     * @return mixed
     */
    public static function Message(int $code, string $message, array $data = []): mixed
    {
        //*获取路由函数
        switch ($code) {
            case 200:
                return json(["code" => $code, "message" => $message, "data" => $data, "count" => count($data), "datetime" => date("Y-m-d h-i-s"), "options" => self::getMessageOptions()]);
            case 300:
                return json(["code" => $code, "message" => "登录超时!", "datetime" => date("Y-m-d h-i-s"), "options" => self::getMessageOptions()]);
            case 404:
                return json(["code" => $code, "message" => $message, "datetime" => date("Y-m-d h-i-s"), "options" => self::getMessageOptions()]);
            case 444:
                return json(["code" => $code, "message" => $message, "datetime" => date("Y-m-d h-i-s"), "options" => self::getMessageOptions()]);
            case 500:
                return abort(500, $message);
            default:
                return abort(500, "参码错误！");
        }
    }
    /**
     * 获取MessageOptions列表
     * @access private
     * @return array
     */
    final private static function getMessageOptions(): array
    {
        return [
            "php" => PHP_VERSION,
            "fucntion" => self::getCurrentFunctionName(),
            "workdir" => getcwd(),
            "user" => gethostname(),
            "ip" => gethostbyname(gethostname()),
        ];
    }
    /**
     * 获取当前IP
     * @access public
     * @return string IPv4
     */
    final public function getIP(): string
    {
        return gethostbyname(gethostname());
    }
    /**
     * 创建init纤程
     * *参数格式: [类=>函数名字],[类=>函数名字]...
     * TODO 对同时请求多个接口的时候，即可使用纤程
     * !函数要求必须是static(静态)，返回的内容要规范
     * @access public
     * @static
     * @param mixed ...$function 执行函数 必填
     * @return mixed
     */
    final public static function initFiber(mixed ...$function): mixed
    {
        $fiber = new Fiber(function (mixed $function): void {
            $arr = [];
            foreach ($function as $k => $v) {
                foreach ($v as $k => $v) {
                    array_push($arr, [$v => $k::$v()]);
                }
            }
            Fiber::suspend($arr);
        });
        return $fiber->start($function);
    }
    /**
     * 发送请求
     * @access public
     * @static
     * @param string $url 请求路径 必填
     * @return mixed
     */
    final public static function SendRequest(string $url): mixed
    {
        $init = curl_init();
        curl_setopt($init, CURLOPT_URL, $url);
        $request = curl_exec($init);
        curl_close($init);
        return $request;
    }
    /**
     * 快速定义Guid
     * TODO用来快速添加时定义Guid
     * @access public
     * @example Base
     * @param string $field Guid字段 必填
     * @param array|string $params 接受参数 必填
     * @return array|string 添加Guid后的参数数据
     */
    final public function BindGuid(string $field, array|string $params): array|string
    {
        $salt = "";
        if (is_array($params)) {
            foreach ($params as $v) {
                if (!empty($v)) {
                    $salt .= $v;
                }
            }
            $params[$field] = Tool::Salt($salt);
        } else if (is_string($params)) {
            $salt = Tool::Salt($params);
        }
        return is_array($params) ? $params : $salt;
    }
}
