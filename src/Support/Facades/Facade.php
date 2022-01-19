<?php
/**
 * Created by PhpStorm.
 * User: wangsong
 * Date: 2022-01-13
 * Time: 02:21
 */

namespace ws\Support\Facades;

use ws\Foundation\Application;

class Facade
{
    //用户保存已经实例化过的门面，防止多次实例化
    protected static $resolvedInstance = [];

    protected static $app;

    /**
     * 解析实例
     *
     * @param $obj
     * @return mixed
     */
    public static function resolveFacadeInstance($obj)
    {
        if (is_object($obj)) {
            return $obj;
        }

        if (isset(static::$resolvedInstance[$obj])) {
            return static::$resolvedInstance[$obj];
        }

        //门面也是用 容器的make方法进行解析
        return static::$resolvedInstance[$obj] = static::$app->make($obj);
    }


    /**
     * 获取实例
     *
     * @return mixed
     */
    public function getFacadeRoot()
    {
        return static::resolveFacadeInstance(static::getFacadeAccessor());
    }

    public static function getFacadeAccessor(){}

    /**
     * 静态调用找不到方法时调用
     *
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        $instance = static::getFacadeRoot();
        return $instance->$method($arguments);
    }

    public static function setFacadeApplication(Application $app){
        static::$app = $app;
    }

}