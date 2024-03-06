<?php

namespace Sh1ne\MySqlBot\Core\Config;

use InvalidArgumentException;

class Config
{

    protected static function getBool(string $key) : bool
    {
        $value = self::get($key);

        return filter_var($value, FILTER_VALIDATE_BOOL);
    }

    protected static function getInt(string $key) : int
    {
        $value = self::get($key);

        return intval($value);
    }

    protected static function get(string $key) : string
    {
        return Env::get($key) ?? throw new InvalidArgumentException("$key environment variable is not set");
    }


}