<?php

namespace Sh1ne\MySqlBot;

use Dotenv\Dotenv;
use Sh1ne\MySqlBot\Core\BaseApplication;
use Sh1ne\MySqlBot\Core\Http\ExceptionHandler as ExceptionHandlerContract;
use Sh1ne\MySqlBot\Core\Log;
use Sh1ne\MySqlBot\Core\ServiceContainer;

class Application extends BaseApplication
{

    protected function register() : void
    {
        $container = ServiceContainer::instance();

        $container->register(ExceptionHandlerContract::class, ExceptionHandler::class);
    }

    public function boot() : void
    {
        Dotenv::createImmutable(__DIR__ . '/..')->safeLoad();

        Log::init(__DIR__ . '/../logs/app.log');
    }

}