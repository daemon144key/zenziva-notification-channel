<?php

namespace TuxDaemon\ZenzivaNotificationChannel;

use Illuminate\Support\ServiceProvider;

class ZenzivaServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->when(ZenzivaChannel::class)
            ->needs(ZenzivaClient::class)
            ->give(function () {
                $config = config('services.zenziva');

                return new ZenzivaClient(
                    $config['userkey'],
                    $config['passkey'],
                    $config['masking']
                );
            });
    }
}
