<?php

namespace app\admin\middleware;

use app\admin\model\Token as TokenModel;
use app\Base;
use think\App;
use app\Tool;

class token extends Base
{

      /**
       * Token验证器
       */
      public function handle($request, \Closure $next)
      {
            $isNotHave = array_merge(explode(",", env("LOGIN_FUNCTION")), [env("SOCKET_CLIENT_FUNCTION"), env("SOCKET_SERVER_FUNCTION"), "IsValidCurrentToken"]);
            foreach ($isNotHave as $k => $v) {
                  //?排除登录
                  if (Tool::getCurrentFunction() == $v)
                        return $next($request);
            }

            try {
                  $token = $request->header("Token") ?? "";
                  if (!$token)
                        return $this->Warning("请携带Token!");


                  $isTokenTimeOut = TokenModel::IsTokenTimeOut($token);

                  if (!$isTokenTimeOut)
                        return $this->LoginTimeOut();
                  

                  $isVerifyTokenExpire = TokenModel::IsVerifyTokenExpire($token);
                  if (!$isVerifyTokenExpire)
                        return $this->Warning("Token过期,请重新登录!");
            } catch (\Exception $e) {
                  dump($e);
            }
            return $next($request);
      }
}
