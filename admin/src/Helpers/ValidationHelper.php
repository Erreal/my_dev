<?php

namespace App\Helpers;

class ValidationHelper
{
    /**
     * Validate a required string field.
     */
    public static function required(?string $value, string $fieldName): ?string
    {
        if (empty(trim($value ?? ''))) {
            return "Поле '{$fieldName}' обязательно для заполнения.";
        }
        return null;
    }

    /**
     * Validate a slug format.
     */
    public static function slug(?string $value, string $fieldName): ?string
    {
        if (empty($value)) {
            return "Поле '{$fieldName}' обязательно для заполнения.";
        }
        if (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $value)) {
            return "Поле '{$fieldName}' должно содержать только латинские буквы, цифры и дефисы.";
        }
        return null;
    }

    /**
     * Validate email format.
     */
    public static function email(?string $value, string $fieldName): ?string
    {
        if (empty($value)) {
            return null; // Not required
        }
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return "Поле '{$fieldName}' имеет неверный формат email.";
        }
        return null;
    }

    /**
     * Validate URL format.
     */
    public static function url(?string $value, string $fieldName): ?string
    {
        if (empty($value)) {
            return null; // Not required
        }
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            return "Поле '{$fieldName}' имеет неверный формат URL.";
        }
        return null;
    }

    /**
     * Validate a date string.
     */
    public static function date(?string $value, string $fieldName): ?string
    {
        if (empty($value)) {
            return "Поле '{$fieldName}' обязательно для заполнения.";
        }
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return "Поле '{$fieldName}' должно быть в формате ГГГГ-ММ-ДД.";
        }
        return null;
    }

    /**
     * Validate multiple fields and return array of errors.
     */
    public static function validate(array $rules, array $data): array
    {
        $errors = [];

        foreach ($rules as $field => $validators) {
            $value = $data[$field] ?? null;

            foreach ($validators as $validator) {
                if (is_string($validator)) {
                    $error = self::$validator($value, $field);
                } elseif (is_callable($validator)) {
                    $error = $validator($value, $field);
                } else {
                    continue;
                }

                if ($error !== null) {
                    $errors[$field] = $error;
                    break;
                }
            }
        }

        return $errors;
    }

    /**
     * Sanitize a string for output.
     */
    public static function sanitize(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }

    /**
     * Truncate text to a specified length.
     */
    public static function truncate(?string $text, int $length = 100): string
    {
        if (empty($text)) {
            return '';
        }
        if (mb_strlen($text) <= $length) {
            return $text;
        }
        return mb_substr($text, 0, $length) . '...';
    }
}