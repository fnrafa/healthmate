<?php

namespace App\Providers;

class Request
{
    protected array $data;
    protected array $errors = [];

    public function __construct()
    {
        $this->data = $_POST;
    }

    public function all(): array
    {
        return $this->data;
    }

    public function only(...$keys): array
    {
        return array_filter(
            $this->data,
            fn($key) => in_array($key, $keys),
            ARRAY_FILTER_USE_KEY
        );
    }

    public function __get($key)
    {
        return $this->data[$key] ?? null;
    }

    public function validate(array $rules): bool
    {
        foreach ($rules as $field => $rule) {
            $value = $this->$field;

            if (str_contains($rule, 'required') && empty($value)) {
                $this->errors[$field][] = 'The ' . $field . ' field is required.';
            }

            if (str_contains($rule, 'email') && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $this->errors[$field][] = 'The ' . $field . ' field must be a valid email address.';
            }

            if (str_contains($rule, 'min:')) {
                preg_match('/min:(\d+)/', $rule, $matches);
                $min = $matches[1];
                if (strlen($value) < $min) {
                    $this->errors[$field][] = 'The ' . $field . ' field must be at least ' . $min . ' characters.';
                }
            }

            if (str_contains($rule, 'confirmed')) {
                $confirmationField = $field . '_confirmation';
                if ($value !== $this->$confirmationField) {
                    $this->errors[$field][] = 'The ' . $field . ' field confirmation does not match.';
                }
            }
        }

        return empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
