<?php

namespace app\controller;

use think\worker\Server;
use Workerman\Lib\Timer;


class Worker extends Server
{
    protected $socket = 'websocket://127.0.0.1:8282';

    public $con;

    public function onConnect($connection)
    {
        $connection->send('链接成功！');
    }

    public function onMessage($connection,$data)
    {

        $connection->send("\n".'服务器接收成功!'."\n");
        $this->con = $connection;
        echo $data;

        // $this->qianwen($data);
    }

    public function onClose($connection)
    {

    }

    public function onError($connection,$code,$msg)
    {
        echo 'error' . $code  . $msg;

    }

    public function onWorkerStart($worker)
    {
        Timer::add(1,function()use($worker)
        {
            // $time_now = time();
            // foreach ($worker->connections as $connection) {
            //     if($time_now - $connection->lastMessageTime > 55)
            //     {
            //         $connection->close();
            //     }else{
            //         $connection->send("xtiao!");
            //     }
            // }
        });
    }
}