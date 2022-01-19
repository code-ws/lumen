<?php
/**
 * Created by PhpStorm.
 * User: wangsong
 * Date: 2022-01-18
 * Time: 02:43
 */



namespace ws\Event;


class Event
{
    protected $listener;    //如果有多个监听器，只会监听最后一个

    protected $events;      //记录所有


    public function listener($listener,$callback)
    {
        $this->listener = strtolower($listener);
        $this->events[$listener] = [$callback];
    }


    public function dispatch($listener,$param = [])
    {
        $listener = strtolower($listener);

        if ($this->events[$listener]) {
            ($this->events[$listener][0])(...$param);
            return true;
        }

    }


}