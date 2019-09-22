<?php

namespace TuxDaemon\ZenzivaNotificationChannel;

use Illuminate\Support\ServiceProvider;

class ZenzivaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->registerArtisanCommands();
        }
    }

    /**
     * Register artisan command for sending SMS.
     */
    protected function registerArtisanCommands()
    {
        $this->commands([
            'TuxDaemon\ZenzivaNotificationChannel\Commands\ZenzivaSendCommand',
            'TuxDaemon\ZenzivaNotificationChannel\Commands\ZenzivaCheckCommand',
        ]);
    }

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

        $this->app->singleton('zenzivasms', function ($app) {
            $config = config('services.zenziva');

            return new ZenzivaClient(
                $config['userkey'],
                $config['passkey'],
                $config['masking']
            );
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['zenzivasms'];
    }
}
