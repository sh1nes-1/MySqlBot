<?php

namespace Sh1ne\MySqlBot;

use Dotenv\Dotenv;
use Sh1ne\MySqlBot\Core\BaseApplication;
use Sh1ne\MySqlBot\Core\Database\DbConnection;
use Sh1ne\MySqlBot\Core\Database\DbConnectionWithLog;
use Sh1ne\MySqlBot\Core\Http\ExceptionHandler as ExceptionHandlerContract;
use Sh1ne\MySqlBot\Core\Log;
use Sh1ne\MySqlBot\Core\ServiceContainer;
use Sh1ne\MySqlBot\Domain\Messenger\Messenger;

class Application extends BaseApplication
{

    protected function register() : void
    {
        $container = ServiceContainer::instance();

        $container->register(ExceptionHandlerContract::class, ExceptionHandler::class);

        $dbConnection = app(DbConnection::class);
        $container->singletonByInstance(DbConnection::class, new DbConnectionWithLog($dbConnection));
    }

    public function boot() : void
    {
        Dotenv::createImmutable(__DIR__ . '/..')->safeLoad();

        Log::init(__DIR__ . '/../logs/app.log');
    }

}