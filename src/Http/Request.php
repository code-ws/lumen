<?php
/**
 * Created by PhpStorm.
 * User: wangsong
 * Date: 2022-01-17
 * Time: 08:53
 */


namespace ws\Http;

class Request
{
    //请求方式
    protected $method;
    //请求参数
    protected $urlPath;


    public static function capture()
    {
        $request = self::createBase();

        //请求方式
        $request->method = $_SERVER['REQUEST_METHOD'];

        //请求参数
        $request->urlPath = empty($_SERVER['PATH_INFO'])? $_SERVER['REQUEST_URI']:$_SERVER['PATH_INFO'];

        return $request;
    }



    public static function createBase()
    {
        return new static();
    }


    public function getMethod()
    {
        return $this->method;
    }


    public function getUrlPath()
    {
        return $this->urlPath;
    }


}