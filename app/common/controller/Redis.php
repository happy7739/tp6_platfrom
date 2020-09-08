<?php
/**
 * Created by PhpStorm.
 * User: 华尚集团
 * Date: 2019/1/16
 * Time: 9:19
 */

namespace app\common\controller;
use think\facade\Config;

class Redis
{
    protected $redis;
    function __construct()
    {
        $this->redis = new \Redis();
        $this->connect();
    }
    function getConnect(): \Redis
    {
        return $this->redis;
    }
    function connect()
    {
        $conf = Config::get('redis.');
        $this->redis->connect($conf['host'], $conf['port']);
        if (!empty($conf['auth'])) {
            $this->redis->auth($conf['auth']);
        }
        return $this;
    }
}