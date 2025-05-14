<?php
namespace app\admin\model\Login;

use app\admin\controller\Token;
use app\Request;
use think\Model;
use think\model\concern\SoftDelete;
use app\Base;
use app\admin\model\Token as TokenModel;

class Login extends Model
{
    use SoftDelete;
    protected $name = "login";//表
    protected $pk = "login_id";//键
    protected $schema = [
        'login_id' => "int",
        'login_guid' => "varchar",
        'login_account' => "varchar",
        'login_password' => "varchar",
        'login_status' => "int",
        'login_type_guid' => "varchar",
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
    /**
     * ?判断当前账号是否处于禁止登录状态->(超级管理除外)
     * @access public
     * @static
     * @param string $userguid 用户Guid 必填
     * @return bool|string
     */
    public static function isCurrentUserProhibitLoginStatus(string $userguid): bool|string
    {
        //!超级管理员不能为进行禁止
        //*获取当前用户
        $getCurrentUser = self::alias("a")
            ->LeftJoin('user us', 'us.user_guid = a.user_guid')
            ->LeftJoin('user_role rl', "rl.user_role_guid = us.user_role_guid")
            ->where("a.user_guid", $userguid)
            ->find();

        if ($getCurrentUser['user_role_guid'] == env('ADMIN_GUID'))
            return true;

        if ($getCurrentUser['login_status'] == 1)
            return "该账号被禁止,请联系管理员进行解开!";

        return true;
    }
    /**
     * 是否携带Token
     * @access public
     * @static
     * @param array $user 查询对应用户 必填
     * @return array 赋予Token后的参数数据
     */
    public static function getToken(array $user): array
    {
        Token::CreateToken($user['user_guid']);

        $token = TokenModel::where(['user_guid' => $user['user_guid']])->value("token_value");
        $user['token'] = $token;
        return $user;
    }
}