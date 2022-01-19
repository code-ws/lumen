<?php
/**
 * Created by PhpStorm.
 * User: wangsong
 * Date: 2022-01-13
 * Time: 09:45
 */

namespace ws\Config;

//获取配置类
class Config
{
    public $items;

    public function phpParser($config_path)
    {
        $files = scandir($config_path);
        $data = [];
        foreach ($files as $file){
            if(in_array($file,['.','..'])){
                continue;
            }
            $filename = pathinfo($file)['filename'];
            $data[$filename] = require_once "$config_path".DIRECTORY_SEPARATOR."$file";
        }

        $this->items = $data;

        return $data;
    }

    /**
     * 获取全部配置
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * 获取指定配置
     *
     * @param $key
     * @return string
     */
    public function get($key)
    {
        //这里巧妙用foreach 代替了递归，层层赋值进入
        $data = $this->items;
        $arr_keys = explode('.',$key);
        foreach ($arr_keys as $key) {
            $data = $data[$key];
        }
        return $data;
    }


}