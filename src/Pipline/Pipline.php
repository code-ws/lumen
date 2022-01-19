<?php
/**
 * Created by PhpStorm.
 * User: wangsong
 * Date: 2022-01-17
 * Time: 09:14
 */

namespace ws\Pipline;

class Pipline
{

    protected $pipes;

    protected $passable;

    protected $app;

    protected $method = 'handle';

    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     *
     *
     * @param \Closure $closure 闭包函数，作为发入array_reduce函数的初始值
     * @return mixed
     */
    public function then(\Closure $closure)
    {
        $res = array_reduce(
            $this->pipes,

            //第一个参数是上一个函数的结果，第二个参数是传进来的参数
            function ($stack,$pipe) {

                return function ($passable) use ($stack,$pipe) {

                    if (is_object($passable)) {
                        return $pipe($passable,$stack);
                    } elseif(!is_object($pipe)) {
                        //类先实例化
                        $pipe = $this->app->make($pipe);
                        $params = [$passable,$stack];
                    }
                    return method_exists($pipe,$this->method) ? $pipe->{$this->method}(...$params) : $pipe(...$params);
                };

            },

            $closure
        );
        return $res[$this->passable];

    }


    public function through($pipes)
    {
        $this->pipes = $pipes;
        return $this;
    }

    public function send($passable)
    {
        $this->passable = $passable;
        return $this;
    }
    
    public function carry()
    {
        return function () {







        };
    }


}