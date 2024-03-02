<?php

namespace Sh1ne\MySqlBot\Core;

use Throwable;

class Log
{

    private static bool $isInitialized = false;

    private static string $filename;

    private static string $traceId;

    public static function init(string $filename) : void
    {
        static::$filename = realpath($filename);
        static::$traceId = (string) mt_rand(100000000, 999999999);
        static::$isInitialized = true;
    }

    public static function debug(string $message, array $context = []) : void
    {
        self::log('DEBUG', $context, $message);
    }

    public static function warning(string $message, array $context = []) : void
    {
        self::log('WARNING', $context, $message);
    }

    public static function error(string $message, array $context = []) : void
    {
        self::log('ERROR', $context, $message);
    }

    public static function info(string $message, array $context = []) : void
    {
        self::log('INFO', $context, $message);
    }

    public static function critical(string $message, array $context = []) : void
    {
        self::log('CRITICAL', $context, $message);
    }

    private static function log(string $level, array $context, string $message) : void
    {
        if (!self::$isInitialized) {
            return;
        }

        $traceId = self::$traceId;

        $dateTime = date('Y-m-d H:i:s');

        $contextJson = !empty($context) ? json_encode($context) : '';

        $message = "[$dateTime][$traceId][$level] $message $contextJson\n";

        try {
            file_put_contents(static::$filename, $message, FILE_APPEND);
        } catch (Throwable) {
            // TODO: think about
        }
    }

}