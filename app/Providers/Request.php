<?php

namespace App\Providers;

use Illuminate\Database\Capsule\Manager as Capsule;

class Request
{
    protected array $data;
    protected array $files;
    protected array $errors = [];

    public function __construct()
    {
        // Capture JSON data from php://input
        $inputData = json_decode(file_get_contents('php://input'), true);
        $this->data = is_array($inputData) ? $inputData : $_POST;
        $this->files = $_FILES;

        // Debug: Log captured data
        error_log("Captured data: " . print_r($this->data, true));
    }

    public function all(): array
    {
        return array_merge($this->data, $this->files);
    }

    public function only(...$keys): array
    {
        return array_filter(
            array_merge($this->data, $this->files),
            fn($key) => in_array($key, $keys),
            ARRAY_FILTER_USE_KEY
        );
    }

    public function __get($key)
    {
        return $this->data[$key] ?? $this->files[$key] ?? null;
    }

    public function validate(array $rules): bool
    {
        foreach ($rules as $field => $rule) {
            $value = $this->$field;
            $ruleParts = explode('|', $rule);

            $nullable = false;
            foreach ($ruleParts as $rulePart) {
                if ($rulePart === 'nullable') {
                    $nullable = true;
                    if (is_null($value)) {
                        continue 2; // Skip further validation for nullable fields if they are null
                    }
                }

                if ($rulePart === 'required' && empty($value) && !$nullable) {
                    $this->errors[$field][] = 'The ' . $field . ' field is required.';
                    continue;
                }

                if (str_starts_with($rulePart, 'required_without:')) {
                    $otherField = str_replace('required_without:', '', $rulePart);
                    if (empty($value) && empty($this->$otherField)) {
                        $this->errors[$field][] = 'The ' . $field . ' field is required when ' . $otherField . ' is not present.';
                    }
                }

                if ($rulePart === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field][] = 'The ' . $field . ' field must be a valid email address.';
                }

                if (preg_match('/min:(\d+)/', $rulePart, $matches)) {
                    $min = $matches[1];
                    if (strlen($value) < $min) {
                        $this->errors[$field][] = 'The ' . $field . ' field must be at least ' . $min . ' characters.';
                    }
                }

                if ($rulePart === 'confirmed') {
                    $confirmationField = $field . '_confirmation';
                    if ($value !== $this->$confirmationField) {
                        $this->errors[$field][] = 'The ' . $field . ' field confirmation does not match.';
                    }
                }

                if ($rulePart === 'file' && (!$nullable || $value)) {
                    if (!isset($this->files[$field]) || $this->files[$field]['error'] !== UPLOAD_ERR_OK) {
                        $this->errors[$field][] = 'The ' . $field . ' field must be a valid file upload.';
                    }
                }

                if (preg_match('/max:(\d+)/', $rulePart, $matches)) {
                    $max = $matches[1];
                    if (is_string($value) && strlen($value) > $max) {
                        $this->errors[$field][] = 'The ' . $field . ' field must be less than ' . $max . ' characters.';
                    }
                    if (is_array($value) && $value['size'] > $max * 1024) {
                        $this->errors[$field][] = 'The ' . $field . ' file size must be less than ' . $max . ' kilobytes.';
                    }
                }

                if (preg_match('/exists:([\w]+),([\w]+)/', $rulePart, $matches)) {
                    $table = $matches[1];
                    $column = $matches[2];
                    if (!Capsule::table($table)->where($column, $value)->exists()) {
                        $this->errors[$field][] = 'The selected ' . $field . ' is invalid.';
                    }
                }
            }
        }

        error_log("Validation errors: " . print_r($this->errors, true));

        return empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function validated(): array
    {
        if (empty($this->errors)) {
            return $this->only(...array_keys($this->data));
        }
        return [];
    }

    public function hasFile($key): bool
    {
        return isset($this->files[$key]) && $this->files[$key]['error'] === UPLOAD_ERR_OK;
    }

    public function file($key)
    {
        return $this->files[$key] ?? null;
    }
}
