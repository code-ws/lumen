<?php
/**
 * Created by PhpStorm.
 * User: wangsong
 * Date: 2022-01-13
 * Time: 09:41
 */

namespace ws\Foundation\Bootstrap;

use ws\Foundation\Application;

class LoadConfig
{

    public function bootstrap(Application $app)
    {
        $configs = $app->make('Config')->phpParser($app->getBasePath().DIRECTORY_SEPARATOR.'Config'.DIRECTORY_SEPARATOR);
        //获取到的数据信息，重新注入覆盖，
        $app->instance('Config',$configs);
    }

}