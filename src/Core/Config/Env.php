<?php

namespace Sh1ne\MySqlBot\Core\Config;

class Env
{

    public static function set(string $key, string $value) : void
    {
        $_ENV[$key] = $value;
    }

    public static function get(string $key) : ?string
    {
        return $_ENV[$key] ?? null;
    }

}