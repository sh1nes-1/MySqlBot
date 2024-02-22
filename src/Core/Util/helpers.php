<?php

use Sh1ne\MySqlBot\Core\ServiceContainer;

if (!function_exists('app')) {
    /**
     * @template T
     * @template F of T
     * @param class-string<T> $abstract
     *
     * @return F
     */
    function app(string $abstract)
    {
        return ServiceContainer::instance()->get($abstract);
    }
}