<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (!function_exists('stringToColor')) {
            function stringToColor($string)
            {
                $code = dechex(crc32($string));
                return '#' . substr($code, 0, 6);
            }
        }
    }
}
