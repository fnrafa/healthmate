<?php

use App\Middlewares\Auth;
use App\Models\Message;
use App\Models\User;
use App\Providers\AppProvider;
use App\Providers\BladeServiceProvider;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Hashing\BcryptHasher;
use JetBrains\PhpStorm\NoReturn;

if (!function_exists('auth')) {
    function auth(): ?User
    {
        return Auth::user();
    }
}

if (!function_exists('provider')) {
    function provider(): ?AppProvider
    {
        return AppProvider::provider();
    }
}

if (!function_exists('old')) {
    function old($key)
    {
        return $_POST[$key] ?? '';
    }
}

if (!function_exists('view')) {
    function view($view, $data = []): string
    {
        return BladeServiceProvider::render($view, $data);
    }
}

if (!function_exists('hash_make')) {
    function hash_make($value): string
    {
        $hash = new BcryptHasher();
        return $hash->make($value);
    }
}

if (!function_exists('getUnreadMessageCount')) {
    function getUnreadMessageCount($id): int
    {
        if (\auth()->role === 'doctor') {
            return Message::whereHas('consultation', function ($query) use ($id) {
                $query->where('doctor_id', $id);
            })
                ->where('sender_id', '!=', $id)
                ->where('is_read', false)
                ->count();
        } else {
            return Message::whereHas('consultation', function ($query) use ($id) {
                $query->where('patient_id', $id);
            })
                ->where('sender_id', '!=', $id)
                ->where('is_read', false)
                ->count();
        }

    }
}

if (!function_exists('showAlert')) {
    function showAlert(int $code, string $message = null): void
    {
        $defaultMessages = [
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error'
        ];

        $_SESSION['alert'] = [
            'alert' => true,
            'message' => $message ?? ($defaultMessages[$code] ?? 'An error occurred'),
            'status' => $defaultMessages[$code] ?? 'Error',
            'code' => $code
        ];
    }
}

if (!function_exists('redirect')) {
    #[NoReturn] function redirect($url = ''): void
    {
        header("Location: $url");
        exit();
    }
}

if (!function_exists('response')) {
    /**
     * Create a new response.
     *
     * @param mixed $content
     * @param int $status
     * @param array $headers
     * @return void
     */
    #[NoReturn] function response(null|string|array $content = '', int $status = 200, array $headers = []): void
    {
        http_response_code($status);
        $isJson = is_array($content) || is_object($content);
        $defaultHeaders = [
            'Content-Type' => $isJson ? 'application/json' : 'text/html; charset=UTF-8'
        ];
        $headers = array_merge($defaultHeaders, $headers);
        foreach ($headers as $header => $value) {
            header("$header: $value");
        }
        if ($isJson) {
            echo json_encode($content);
        } else {
            echo $content;
        }
        exit();
    }
}

if (!function_exists('db')) {
    /**
     * Get the database connection instance.
     *
     * @return Capsule
     */
    function db(): Capsule
    {
        return new Capsule;
    }
}

