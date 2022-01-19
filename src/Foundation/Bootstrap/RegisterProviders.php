<?php
/**
 * Created by PhpStorm.
 * User: wangsong
 * Date: 2022-01-14
 * Time: 01:39
 */


namespace ws\Foundation\Bootstrap;

use ws\Foundation\Application;

class RegisterProviders
{

    public function bootstrap(Application $app)
    {
        $app->registerConfigProviders();
    }

}