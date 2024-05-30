<?php

namespace App\Providers;

use Jenssegers\Blade\Blade;

class BladeServiceProvider
{
    protected static ?Blade $bladeInstance = null;

    public static function getBladeInstance(): Blade
    {
        if (self::$bladeInstance === null) {
            $views = __DIR__ . '/../Views';
            $cache = __DIR__ . '/../../storage/cache';
            self::$bladeInstance = new Blade($views, $cache);
        }

        return self::$bladeInstance;
    }

    public static function render($view, $data = []): string
    {
        return self::getBladeInstance()->make($view, $data)->render();
    }
}

if (!function_exists('view')) {
    function view($view, $data = []): string
    {
        return BladeServiceProvider::render($view, $data);
    }
}