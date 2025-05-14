<?php

// +----------------------------------------------------------------------
// | DictionaryMap
// | Date:2024-11-12
// | By:LIULIE
// +----------------------------------------------------------------------


namespace app;

class DictionaryMap
{
    use \app\common\trait\DictionaryMap;
    /**
     * Get DictionaryMap [admin . api]
     * @author liulei
     * @static
     * @access public
     * @var array
     */
    public static array $dictionaryMap = [
        "admin" => [],
        "api" => [
            "apiuser" => [
                1 => "在线",
                2 => "离线",
                3 => "自定义",
            ],
            "apimedia"=>[
                0 => "未审核",
                1 => "已审核",
                2 => "审核不通过",
            ],
            "apimedia_type_status"=>[
                1 =>"可用",
                2 => "不可用",
            ],
        ],
    ];

}