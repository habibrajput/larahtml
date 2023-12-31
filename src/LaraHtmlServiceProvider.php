<?php

namespace Habibrajput\Larahtml;

use Illuminate\Support\ServiceProvider;

class LaraHtmlServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->mergeConfigFrom(
            __DIR__ . '/config/larahtml.php',
            'larahtml'
        );
        $this->publishes([
            __DIR__ . '/config/larahtml.php' => config_path('larahtml.php'),
        ],
        ['lara-html']);
    }

    public function register() 
    {
         
    }

}
