<?php

namespace Sh1ne\MySqlBot\Core\Config;

use InvalidArgumentException;

class AppConfig
{

    public static function isDebugMode() : bool
    {
        return self::getBool('APP_DEBUG');
    }

    public static function getBotName() : string
    {
        return self::get('BOT_NAME');
    }

    public static function getSlackApiKey() : string
    {
        return self::get('SLACK_API_KEY');
    }

    public static function getSlackSigningSecret() : string
    {
        return self::get('SLACK_SIGNING_SECRET');
    }

    private static function getBool(string $key) : bool
    {
        $value = self::get($key);

        return filter_var($value, FILTER_VALIDATE_BOOL);
    }

    private static function get(string $key) : string
    {
        return Env::get($key) ?? throw new InvalidArgumentException("$key environment variable is not set");
    }

}