<?php
/**
 * Created by PhpStorm.
 * User: wangsong
 * Date: 2022-01-18
 * Time: 02:43
 */



namespace ws\Event;


use ws\Foundation\Application;

class Listener
{
    protected $name = 'listener';

    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function handle(){}

    public function getname()
    {
        return $this->name;
    }


}