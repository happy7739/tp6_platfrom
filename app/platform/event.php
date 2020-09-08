<?php
/**
 * Created by PhpStorm.
 * User: xiaoziyan
 * Date: 2020/8/31
 * Time: 13:00
 */

return [
    'bind'      => [

    ],

    'listen'    => [
        'UserLogin'  => ['app\platform\listener\UserLogin'],
        'Sms'  => ['app\common\listener\SendSms'],
    ],

    'subscribe' => [
        'app\platform\subscribe\User'
    ],
];
