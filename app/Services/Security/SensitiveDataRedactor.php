<?php

namespace App\Services\Security;

use Throwable;

class SensitiveDataRedactor
{
    public const REDACTED = '[REDACTED]';
    public const REDACTED_TOKEN = '[REDACTED_TOKEN]';
    public const REDACTED_HEADER = '[REDACTED_HEADER]';

    /**
     * Exact or substring keys that should always have their values redacted.
     */
    protected static array $sensitiveKeyPatterns = [
        'password',
        'passwd',
        'pwd',
        'secret',
        'token',
        'auth',
        'key',
        'credential',
        'cookie',
        'session',
        'csrf',
        'xsrf',
        'credit_card',
        'card_number',
        'cvv',
        'cvc',
        'ssn',
        'pin',
    ];

    /**
     * Regex patterns for string content scrubbing.
     */
    protected static array $stringPatterns = [
        // Bearer tokens in headers or logs
        '/(bearer\s+)[A-Za-z0-9\-\._~\+\/]+=*/i' => '$1' . self::REDACTED_TOKEN,
        // Basic auth headers
        '/(basic\s+)[A-Za-z0-9\+\/]+=*/i' => '$1' . self::REDACTED_TOKEN,
        // Common query parameter or json inline secrets
        '/(["\']?(?:password|passwd|secret|token|api_key|access_token)["\']?\s*[:=]\s*["\'])([^"\'\s&]+)(["\']?)/i' => '$1' . self::REDACTED . '$3',
    ];

    /**
     * Redact sensitive information from any data structure (array, scalar, object, or Throwable).
     */
    public static function redact(mixed $data, int $depth = 0): mixed
    {
        if ($depth > 8) {
            return '[MAX_DEPTH_REACHED]';
        }

        if (is_null($data) || is_bool($data) || is_int($data) || is_float($data)) {
            return $data;
        }

        if (is_string($data)) {
            return self::redactString($data);
        }

        if ($data instanceof Throwable) {
            return self::redactThrowable($data, $depth);
        }

        if (is_array($data)) {
            $sanitized = [];
            foreach ($data as $key => $value) {
                if (self::isSensitiveKey((string)$key)) {
                    $sanitized[$key] = self::REDACTED;
                } else {
                    $sanitized[$key] = self::redact($value, $depth + 1);
                }
            }
            return $sanitized;
        }

        if (is_object($data)) {
            if (method_exists($data, 'toArray')) {
                return self::redact($data->toArray(), $depth + 1);
            }

            // Convert stdClass or generic object properties to array and sanitize
            $properties = get_object_vars($data);
            if (!empty($properties)) {
                return (object)self::redact($properties, $depth + 1);
            }

            return '[' . get_class($data) . ']';
        }

        return $data;
    }

    /**
     * Determine if a given array key or property name is sensitive.
     */
    public static function isSensitiveKey(string $key): bool
    {
        $normalizedKey = strtolower(trim($key));

        // Direct common exact matches
        if (in_array($normalizedKey, [
            'password',
            'password_confirmation',
            'current_password',
            'new_password',
            'old_password',
            'token',
            'api_token',
            'access_token',
            'refresh_token',
            'remember_token',
            'secret',
            'app_key',
            'authorization',
            'cookie',
            'set-cookie',
            'php-auth-pw',
            'x-csrf-token',
            'x-xsrf-token',
        ], true)) {
            return true;
        }

        // Substring / pattern check
        foreach (self::$sensitiveKeyPatterns as $pattern) {
            if (str_contains($normalizedKey, $pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Redact sensitive patterns inside a string.
     */
    public static function redactString(string $text): string
    {
        if (empty($text)) {
            return $text;
        }

        foreach (self::$stringPatterns as $pattern => $replacement) {
            $text = preg_replace($pattern, $replacement, $text);
        }

        return $text;
    }

    /**
     * Safely format and redact an exception so sensitive stack traces are cleaned.
     */
    public static function redactThrowable(Throwable $e, int $depth = 0): array
    {
        return [
            'class' => get_class($e),
            'message' => self::redactString($e->getMessage()),
            'code' => $e->getCode(),
            'file' => $e->getFile() . ':' . $e->getLine(),
            'trace' => array_slice(
                array_map(function ($frame) {
                    return [
                        'file' => ($frame['file'] ?? '[internal]') . ':' . ($frame['line'] ?? 0),
                        'function' => ($frame['class'] ?? '') . ($frame['type'] ?? '') . ($frame['function'] ?? ''),
                    ];
                }, $e->getTrace()),
                0,
                15
            ),
            'previous' => $e->getPrevious() ? self::redactThrowable($e->getPrevious(), $depth + 1) : null,
        ];
    }
}
