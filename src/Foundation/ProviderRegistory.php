<?php
/**
 * Created by PhpStorm.
 * User: wangsong
 * Date: 2022-01-14
 * Time: 01:41
 */

namespace ws\Foundation;


class ProviderRegistory
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function load($providers)
    {
        if (!empty($providers)) {
            foreach ($providers as $provider) {
                $this->register($provider);
            }
        } else {
            //todo 这里是否要报个异常，没有配置服务
        }
    }


    /**
     * 根据不同类型进行分发、注册
     *
     * @param string $provider  需要进行注册的服务
     */
    public function register($provider)
    {
        //检测服务是否标量，比如字符串、布尔值、int、float这些是。数组、对象、资源不是。
        if (is_scalar($provider)) {
            $provider = $this->resolveProvider($provider);
        }

        //利用Provider初始化以后，调用服务的register方法
        $provider->register();

        //服务提供者可以定义bindings数组，进行一些类的绑定
        //属性如果存在进行绑定
        if (property_exists($provider,'bindings')) {
            foreach ($provider->bindings as $key=>$val) {
                $this->app->make($key,$val);
            }
        }

        $this->app->markAsRegistered($provider);

    }


    /**
     * 服务器提供者注册，核心方法
     */
    protected function resolveProvider($provider)
    {
        return new $provider($this->app);
    }



}