<?php
/**
 * Created by PhpStorm.
 * User: wangsong
 * Date: 2022-01-13
 * Time: 01:52
 */

namespace ws\Foundation;

use ws\Container\Container;
use ws\Support\Facades\Facade;

class Application extends Container
{
    protected $basePath;

    protected $booted = false;

    protected $servicesProviders;

    public function __construct($basePath = '')
    {
        //设置根目录
        if ($basePath) {
            $this->setBasePath($basePath);
        }

        //注册app自己本身
        $this->registerBaseBindings();
        //给Facade注入 application类，laravel中是封装在了服务中心，在registerBaseServiders中
        Facade::setFacadeApplication($this);

        //注册核心的容器
        $this->registerCoreContainerAliases();

        //注册时间监听
        $this->registerBaseServicProvider();
    }


    /**
     *
     */
    public function registerBaseServicProvider()
    {

    }


    /**
     * 注册核心容器
     */
    public function registerCoreContainerAliases()
    {
        $binds = [
            'Config' => \ws\Config\Config::class,           //加载配置类
            'Route'  => \ws\Route\Route::class,             //加载路由类
        ];

        foreach ($binds as $name => $class) {
            $this->bind($name,$class);
        }
    }

    /**
     * 设置根目录
     *
     * @param $basePath
     */
    public function setBasePath($basePath)
    {
        $this->basePath = rtrim($basePath,'\/');
    }

    /***
     *
     */

    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * 绑定自己到容器
     */
    public function registerBaseBindings()
    {
        $this->instance('app',$this);
    }


    /**
     * 将配置文件中配置的服务提供者进行注册
     */
    public function registerConfigProviders()
    {
        $providers = $this->make('Config')->get('app.providers');
        //在构造函数中将$this传入，获得App类
        (new ProviderRegistory($this))->load($providers);
    }


    /**
     * 将已经注册过的服务提供者进行标记
     *
     * @param $provider
     */
    public function markAsRegistered($provider)
    {
        $this->servicesProviders[] = $provider;
    }



    /**
     * 启动
     */
    public function boot()
    {
        if ($this->booted) {
            return true;
        }

        if (!empty($servicesProviders)) {
            foreach ($this->servicesProviders as $provider) {
                $provider->boot();
            }
        } else {
            //todo 这里没有服务提供，是否要抛出异常
        }

        $this->booted = true;
    }



}