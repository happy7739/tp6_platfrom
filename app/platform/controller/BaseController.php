<?php
declare (strict_types = 1);

namespace app\platform\controller;

use app\common\controller\StatusCode;
use app\middleware\Domain;
use app\middleware\Power;
use org\Rsa;
use think\App;
use think\exception\ValidateException;
use think\facade\Event;
use think\Validate;
/**
 * 控制器基础类
 */
abstract class BaseController
{
    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;
    /**
     * 请求参数
     * @var
     */
    protected $param;
    /**
     * 当前语言标识
     * @var
     */
    protected $lang;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;


    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {
        $this->lang = cookie(config('lang.cookie_var'));
        $this->param = $this->request->param();
        //密码字段解密
        foreach (array_keys($this->param) as $key){
            if(is_numeric(strpos($key,'password'))){
                $this->param[$key] = Rsa::decode($this->param[$key]);
            }
        }
    }

    /**
     * 验证数据
     * @access protected
     * @param  array        $data     数据
     * @param  string|array $validate 验证器名或者验证规则数组
     * @param  array        $message  提示信息
     * @param  bool         $batch    是否批量验证
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                [$validate, $scene] = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v     = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }

}
