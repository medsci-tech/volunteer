<?php

namespace App\Providers;

use App\Werashop\Message\LuosimaoMessageSender;
use Illuminate\Support\ServiceProvider;

class MessageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('message_sender', function ($app) {
            return new LuosimaoMessageSender();
        });
    }
}