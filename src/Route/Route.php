<?php
/**
 * Created by PhpStorm.
 * User: wangsong
 * Date: 2022-01-14
 * Time: 02:22
 */

namespace ws\Route;

use ws\Foundation\Application;

class Route
{

    protected $routes = [];
    protected $verbs = ['GET','POST','PUT'];
    protected $action;
    protected $namespace;
    protected $controller;
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function get($url,$action)
    {
        $this->addRoute(['GET'],$url,$action);
    }

    public function any($url,$action)
    {
        $this->addRoute($this->verbs,$url,$action);
    }

    /**
     * method 会传递多个
     *
     * @param $method
     * @param $url
     * @param $action
     */
    protected function addRoute($method,$url,$action)
    {
        foreach ($method as $v) {
            $this->routes[$v][$url] = $action;
        }
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     *  获取路由文件路径
     */
    public function register($route_path)
    {
        require_once "{$route_path}";
    }


    /**
     * 对路由进行匹配执行
     *
     * @param $request
     */
    public function disPatcher($request)
    {
        //匹配路由
        $this->findRoute($request);
        //执行路由
        $this->runRoute($request);
    }


    /**
     * 查找路由
     *
     * @param $request
     */
    public function findRoute($request)
    {
        $this->match($request->getMethod(),$request->getUrl());
    }

    /**
     * 获取匹配的路由规则
     *
     * @param $method
     * @param $path
     * @return $this
     */
    public function match($method,$path)
    {
        $routes = $this->routes;
        foreach ($routes[$method] as $url=>$action) {
            if (trim($url,'/') === trim($path,'/')) {
                $this->action = $action;
                break;
            }
        }
        return $this;
    }

    /**
     * 执行路由
     *
     * @param $request
     */
    public function runRoute($request)
    {
        //如果是闭包，直接执行
        if ($this->action instanceof \Closure) {
            ($this->action)();
        }

        //如果是字符串，这里指对Controller类型进行处理
        if (is_string($this->action)) {
            $this->runController();
        }

    }

    /**
     * 对Controller类型路由执行
     */
    public function runController()
    {
        $class = $this->getController();
        $method = $this->getMethod();
        $class->$method();
    }


    /**
     * 获取Controller对象
     *
     * @return object
     */
    public function getController()
    {
        if (!$this->controller) {
            $class = $this->namespace.'\\'.explode('@',$this->action)[0];
            $this->controller = $this->app->make(ltrim($class,'\\'));
        }
        return $this->controller;
    }

    /**
     * 获取要执行的具体方法
     */
    public function getMethod()
    {
       return explode('@',$this->action)[1];
    }


}