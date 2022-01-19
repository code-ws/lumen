<?php
/**
 * Created by PhpStorm.
 * User: wangsong
 * Date: 2022-01-13
 * Time: 03:22
 */

namespace ws\Foundation\Http;

//父类文件，完成一些服务启动

use ws\Foundation\Application;

class Kernel
{
    protected $bootstrappers = [
        \ws\Foundation\Bootstrap\RegisterFacade::class,     //注册门面
        \ws\Foundation\Bootstrap\LoadConfig::class,         //注册配置文件
        \ws\Foundation\Bootstrap\RegisterProviders::class,  //服务提供者注册
        \ws\Foundation\Bootstrap\BootProviders::class,      //服务提供者启动
    ];

    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * 处理请求
     * 入口
     */
    public function handle($request = null)
    {
        $this->sendRequestThroughRouter($request);
    }

    /**
     * 通过路由发送请求
     */
    public function sendRequestThroughRouter($request)
    {
        //引导类启动
        $this->bootstrap();

        //请求绑定
        $this->app->instance('request',$request);

        //路由分发请求
        $this->app->make('Route')->dispatcher($request);
    }

    /**
     * 加载服务
     */
    public function bootstrap()
    {
        foreach ($this->bootstrappers as $bootstrapper) {
            $this->app->make($bootstrapper)->bootstrap($this->app);
        }
    }



}