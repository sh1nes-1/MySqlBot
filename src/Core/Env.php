<?php

namespace Sh1ne\MySqlBot\Core;

class Env
{

    public static function get(string $key) : ?string
    {
        return $_ENV[$key] ?? null;
    }

}