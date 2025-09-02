<?php


namespace Veloxia\Core;

class Env
{
    public static function load(string $path = __DIR__ . '/../.env'): void {
        if (!file_exists($path)) return;

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (str_starts_with($line, '#')) continue;

            [$key, $value] = explode('=', $line, 2);
            $key   = trim($key);
            $value = trim($value);

            if (!getenv($key)) {
                putenv("$key=$value");
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }


    public static function get(string $key, $default = null): mixed
    {
        return $_ENV[$key] ?? getenv($key) ?? $default;
    }
}
