<?php

namespace App\Providers;

use Exception;

/**
 * @property $name
 * @property $email
 * @property $password
 */
class Request
{
    protected array $data;

    public function __construct()
    {
        $this->data = array_merge($_GET, $_POST);
    }

    public function only(...$keys): array
    {
        return array_filter(
            $this->data,
            function ($key) use ($keys) {
                return in_array($key, $keys);
            },
            ARRAY_FILTER_USE_KEY
        );
    }

    public function all(): array
    {
        return $this->data;
    }

    public function input($key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    public function has($key): bool
    {
        return isset($this->data[$key]);
    }

    /**
     * @throws Exception
     */
    public function validate(array $rules): void
    {
        foreach ($rules as $key => $rule) {
            if (!isset($this->data[$key])) {
                throw new Exception("Validation failed: $key is required");
            }
        }
    }
}
