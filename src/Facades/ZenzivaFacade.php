<?php

namespace TuxDaemon\ZenzivaNotificationChannel\Facades;

use Illuminate\Support\Facades\Facade;

class ZenzivaFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'zenzivasms';
    }
}
