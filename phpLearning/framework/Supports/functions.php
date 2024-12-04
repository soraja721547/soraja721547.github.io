<?php
if (!function_exists('data_get')) {
    /**
     * Get an item from an array or object using "dot" notation.
     */
    function data_get(
        mixed $target,
        array|string|int|null $key,
        mixed $default = null
    ): mixed {
        if (is_null($target) || is_null($key)) {
            return $target;
        }

        $key = is_array($key) ? $key : explode('.', $key);

        foreach ($key as $part) {
            if (is_null($part)) {
                return $target;
            }

            if (is_array($target) && array_key_exists($part, $target)) {
                $target = $target[$part];
            } elseif (is_object($target) && property_exists($target, $part)) {
                $target = $target->{$part};
            } else {
                return $default;
            }
        }

        return $target;
    }
}

if (function_exists('value')) {
    /**
     * Return the default value of the given value.
     */
    function value(mixed $value, mixed ...$args): mixed
    {
        return $value instanceof Closure ? $value(...$args) : $value;
    }
}

if (!function_exists('base_path')) {
    /**
     * Get the path to the base of the install.
     */
    function base_path(string $path = ''): string
    {
        return dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . ltrim($path, '/\\');
    }
}

if (!function_exists('beautify_throwable')) {
    /**
     * Convert throwable to an human-understandable array structure.
     */
    function beautify_throwable(Throwable $throwable): array
    {
        return [
            'message' => $throwable->getMessage(),
            'throwable' => get_class($throwable),
            'file' => $throwable->getFile(),
            'line' => $throwable->getLine(),
            'trace' => $throwable->getTrace(),
        ];
    }
}
?>