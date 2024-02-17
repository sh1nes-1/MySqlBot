<?php

namespace Sh1ne\MySqlBot\Core;

class Log
{

    private static string $filename;

    private static string $traceId;

    public static function init(string $filename) : void
    {
        static::$filename = $filename;
        static::$traceId = (string) random_int(100000000, 999999999);
    }

    public static function info(string $message, array $context = []) : void
    {
        $traceId = self::$traceId;

        $dateTime = date('Y-m-d H:i:s');

        $contextJson = !empty($context) ? json_encode($context) : '';

        $message = "[$dateTime][$traceId] $message $contextJson\n";

        file_put_contents(static::$filename, $message, FILE_APPEND);
    }

}