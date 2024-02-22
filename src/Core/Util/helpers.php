<?php

use Sh1ne\MySqlBot\Core\ServiceContainer;

if (!function_exists('app')) {
    /**
     * @template T
     * @param class-string<T> $abstract
     *
     * @return T
     */
    function app(string $abstract)
    {
        return ServiceContainer::instance()->get($abstract);
    }
}