<?php
declare (strict_types = 1);

namespace app\common\service;

use Swoole\Server;
use think\swoole\coroutine\Context;
use think\swoole\Websocket;


class SocketService
{
    protected $websocket = null;     //websocket对象
    protected $server = null;     //server对象
    protected $message = '';

    /**
     * 建立服务链接
     * SocketService constructor.
     * @param Server $server
     * @param Websocket $websocket
     */
    public function __construct(Server $server, Websocket $websocket)
    {
        $this->websocket = $websocket;//依赖注入的方式
        $this->server = $server;
    }

    /**
     * 准备推送信息
     * @param $fd
     * @return bool|mixed
     * Date: 2020/8/31 14:52
     */
    public function send($fd = null) : bool{
        $res = false;
        if(is_array($fd)){
            //数组形式，多用户端推送
            $res = true;
            foreach ($fd as $id){
                $res = $this->doSend(intval($id));
                if($res === false) break;
            }
        }else if(is_numeric($fd)){
            //数字形式，单用户推送
            $res = $this->doSend(intval($fd));
        }else if(is_null($fd)){
            //默认null,当前链接用户推送
            $fd = $this->getFd();
            $res = is_numeric($fd) ? $this->doSend(intval($fd)) : false;
        }
        return $res;
    }

    /**
     * 设置推送内容
     * @param $message
     * @return $this
     * Date: 2020/8/31 14:50
     */
    public function setMessage($message){
        is_array($message) and $message = json_encode($message);
        $this->message = $message;
        return $this;
    }

    /**
     * 开始推送
     * @param int $fd
     * @return mixed
     * Date: 2020/8/31 14:50
     */
    private function doSend(int $fd){
        return $this->server->push($fd, $this->message);
    }

    /**
     * 获取当前链接FD
     * @param null | string $uid
     * @return mixed|null
     * Date: 2020/8/31 17:24
     */
    public function getFd($uid = null){
        if(!is_null($uid)){
           return cache($uid);
        }
        return $this->websocket->getSender();
    }


}
