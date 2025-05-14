<?php
namespace app\admin\controller;

use app\Base;
use app\Request;
use app\void\header;
use app\Tool;

class Index extends Base
{
    /**
     * 测试接口
     */
    public function index(Request $request)
    {
        //   header("Location:".Tool::RouterUnreal());
        //   die();

    }
    /**
     * 获取前台切换背景的所有图片
     * @static
     * @return array 所有图片的数组
     */
    public function getApiChangeBackgroundIamgeAll()
    {
        $url = root_path() . "public/upload/LoginBackgroundImg/";
        $allImage = glob($url . "*");
        $arr = [];
        foreach ($allImage as $k => $v) {
            $v = str_replace($url, "", $v);
            array_push($arr, $v);
        }
        return $this->success("获取成功!",$arr);
    }
    /**
     * 获取前台背景所有图片
     */
    public function getApiBackgroundImage()
    {
        $url = root_path() . "public/upload/BackgroundImg/";
        $allImage = glob($url . "*");
        $arr = [];
        foreach ($allImage as $k => $v) {
            $v = str_replace($url, "", $v);
            array_push($arr, $v);
        }
        return $this->success("获取成功!",$arr);
    }

}