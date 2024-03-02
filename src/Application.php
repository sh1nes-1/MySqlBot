<?php

namespace Sh1ne\MySqlBot;

use Sh1ne\MySqlBot\Core\BaseApplication;
use Sh1ne\MySqlBot\Core\Database\DbConnection;
use Sh1ne\MySqlBot\Core\Database\DbConnectionWithLog;
use Sh1ne\MySqlBot\Core\Http\ExceptionHandler as ExceptionHandlerContract;
use Sh1ne\MySqlBot\Core\Log;
use Sh1ne\MySqlBot\Core\Queue\Amqp\AmqpDispatcher;
use Sh1ne\MySqlBot\Core\Queue\Dispatcher;
use Sh1ne\MySqlBot\Core\ServiceContainer;

class Application extends BaseApplication
{

    private string $baseDirectory;

    public function __construct(string $baseDirectory)
    {
        $this->baseDirectory = $baseDirectory;
    }

    protected function register() : void
    {
        $container = ServiceContainer::instance();

        $container->register(ExceptionHandlerContract::class, ExceptionHandler::class);
        $container->register(Dispatcher::class, AmqpDispatcher::class);

        $dbConnection = app(DbConnection::class);
        $container->singletonByInstance(DbConnection::class, new DbConnectionWithLog($dbConnection));
    }

    public function boot() : void
    {
        Log::init($this->baseDirectory . '/logs/app.log');
    }

    public function getBaseDirectory() : string
    {
        return $this->baseDirectory;
    }

}