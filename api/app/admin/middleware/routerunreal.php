<?php

namespace app\admin\middleware;

use app\Tool;
use app\Base;
use app\void\header;


class RouterUnreal extends Base
{

    /**
     * 生成路由虚幻
     */
    public function handle($request, \Closure $next)
    {
        $url = Tool::getCompleteCurrentUrl();
        if ($request->param('isStartUnreal') == null) {
            if (str_contains($url, "?")) {
                $url = explode("?", $url);
                Tool::Redirect($url[0]);
                call_user_func($this->handle($request, $next));
            }
            Tool::Redirect("Location:" . Tool::RouterUnreal());
        }
        return $next($request);
    }
}