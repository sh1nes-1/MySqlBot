<?php

namespace Sh1ne\MySqlBot\Core;

class Clock
{

    private static ?int $testNow = null;

    public static function setTestNow(?int $now) : void
    {
        self::$testNow = $now;
    }

    public static function now() : int
    {
        return self::$testNow ?? time();
    }

}