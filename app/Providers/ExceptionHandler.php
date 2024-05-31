<?php

namespace App\Providers;

use Jenssegers\Blade\Blade;
use JetBrains\PhpStorm\NoReturn;
use Throwable;

class ExceptionHandler
{
    protected static Blade $blade;

    public static function init(): void
    {
        self::$blade = new Blade(__DIR__ . '/../Views', __DIR__ . '/../../storage/cache');
        set_exception_handler([self::class, 'handleException']);
    }

    #[NoReturn] public static function handleException(Throwable $e): void
    {
        http_response_code(500);

        echo self::$blade->make('errors.500', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ])->render();

        exit();
    }
}
