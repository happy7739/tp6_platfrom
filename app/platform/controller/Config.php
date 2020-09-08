<?php
declare (strict_types = 1);

namespace app\platform\controller;


class Config extends BaseController
{
    public function config(){
        halt('config');
    }

    public function version(){
        halt('version');
    }
}
