<?php
/**
 * Created by PhpStorm.
 * User: wangsong
 * Date: 2022-01-12
 * Time: 09:38
 */

namespace ws\Container;


//容器，主要用于存储服务
//主要包含两个方法，bind和make

class Container
{
    //容器绑定后存储在数组中
    protected $bindings = [];
    //存储共享实例，即使用singleton 绑定的实例
    protected $instances = [];

    /**
     * 注入，简单绑定实现
     *
     * @param string $abstract   标识，绑定后的类别
     * @param mixed $concrete    对象、闭包函数、回调、字符串
     * @param bool $shared       是否分享共用
     */
    public function bind($abstract,$concrete = null,$shared = false)
    {
        $this->bindings[$abstract]['concrete'] = $concrete;
        $this->bindings[$abstract]['shared'] = $shared;
    }

    public function getBind()
    {
        var_dump($this->bindings);
    }


    /**
     * 根据标识，将容器中对应的类解析出来使用，即为创建对象
     *
     * @param string $abstract      标识
     * @param array $arguments      创建对象时的参数
     * @return object
     */
    public function make($abstract,$arguments = [])
    {
        //门面也是直接用make进行类的解析，
        //判断标识是否存在
//        if (!isset($this->bindings[$abstract]) || !isset($this->instances[$abstract])) {
//            exit('标识不存在，没有进行注册');
//        }


        //判断是否单例模式注册
        if ($this->instances[$abstract]) {
            return $this->instances[$abstract];
        }

//        $obj = $this->bindings[$abstract]['concrete'];

        $obj = $this->getConrete($abstract);

        if ($obj instanceof \Closure) {
            $obj = $obj();
        }

        if (!is_object($obj)) {
            $obj = new $obj(...$arguments);
        }

        if ($this->bindings[$abstract]['shared']) {
            $this->instances[$abstract] = $obj;
        }

        return $obj;
    }


    /**
     * 获取对象
     *
     * @param $abstract
     * @return mixed
     */
    public function getConrete($abstract)
    {
        if(isset($this->bindings[$abstract])){
            return $this->bindings[$abstract]['concrete'];
        }
        return $abstract;
    }

    /**
     * 只实例化一次
     *
     * @param string $abstract  标识，绑定后的类名
     * @param mixed $concrete   对象、闭包函数、回调、字符串
     * @param bool $shared
     */
    public function singleton($abstract,$concrete = null,$shared = true)
    {
        $this->bind($abstract,$concrete,$shared);
    }


    /**
     * 移除绑定的容器
     *
     * @param string $abstract   标识
     */
    public function removeBindings($abstract)
    {
        if (isset($this->bindings[$abstract])) {
            unset($this->bindings[$abstract]);
        }
    }


    /**
     * 绑定实例到容器
     *
     * @param string $abstract    标识
     * @param object $instance    实例化后的对象
     */
    public function instance($abstract,$instance)
    {
        $this->removeBindings($abstract);
        $this->instances[$abstract] = $instance;
    }

}
