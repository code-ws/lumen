<?php
/**
 * Created by PhpStorm.
 * User: wangsong
 * Date: 2022-01-13
 * Time: 09:35
 */

namespace ws\Foundation\Bootstrap;

use ws\Foundation\Application;

class BootProviders
{
    public function bootstrap(Application $app)
    {
        $app->boot();
    }

}