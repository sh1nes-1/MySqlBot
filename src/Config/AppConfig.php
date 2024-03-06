<?php

namespace Sh1ne\MySqlBot\Config;

use InvalidArgumentException;
use Sh1ne\MySqlBot\Core\Config\Config;

class AppConfig extends Config
{

    public static function isDebugMode() : bool
    {
        try {
            return self::getBool('APP_DEBUG');
        } catch (InvalidArgumentException) {
            return true;
        }
    }

    public static function getHandleEventQueueName() : string
    {
        return self::get('HANDLE_EVENT_QUEUE_NAME');
    }

    public static function getBotName() : string
    {
        return self::get('BOT_NAME');
    }

    public static function getSlackApiBaseUrl() : string
    {
        return self::get('SLACK_API_BASE_URL');
    }

    public static function getSlackApiKey() : string
    {
        return self::get('SLACK_API_KEY');
    }

    public static function getSlackSigningSecret() : string
    {
        return self::get('SLACK_SIGNING_SECRET');
    }

    public static function getDbHost() : string
    {
        return self::get('DB_HOST');
    }

    public static function getDbPort() : string
    {
        return self::get('DB_PORT');
    }

    public static function getDbUser() : string
    {
        return self::get('DB_USER');
    }

    public static function getDbPassword() : string
    {
        return self::get('DB_PASSWORD');
    }

    public static function getDbName() : string
    {
        return self::get('DB_NAME');
    }

    public static function getAmqpHost() : string
    {
        return self::get('AMQP_HOST');
    }

    public static function getAmqpPort() : int
    {
        return self::getInt('AMQP_PORT');
    }

    public static function getAmqpUser() : string
    {
        return self::get('AMQP_USER');
    }

    public static function getAmqpPassword() : string
    {
        return self::get('AMQP_PASSWORD');
    }

    public static function getResultMessageFormat() : string
    {
        return self::get('RESULT_MESSAGE_FORMAT');
    }

}