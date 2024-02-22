<?php

namespace Sh1ne\MySqlBot\Core;

use Sh1ne\MySqlBot\Core\ErrorHandler\BasicErrorHandler;
use Sh1ne\MySqlBot\Core\ErrorHandler\ErrorHandler;
use Sh1ne\MySqlBot\Core\Http\BasicOutput;
use Sh1ne\MySqlBot\Core\Http\BasicRequest;
use Sh1ne\MySqlBot\Core\Http\BasicResponseFactory;
use Sh1ne\MySqlBot\Core\Http\Output;
use Sh1ne\MySqlBot\Core\Http\Request;
use Sh1ne\MySqlBot\Core\Http\ResponseFactory;
use Sh1ne\MySqlBot\Core\Http\Router;

abstract class BaseApplication
{

    public function registerServices() : void
    {
        $container = ServiceContainer::instance();

        $container->singletonByInstance(ErrorHandler::class, new BasicErrorHandler());
        $container->singletonByInstance(Output::class, new BasicOutput());
        $container->singletonByInstance(ResponseFactory::class, new BasicResponseFactory());
        $container->singletonByInstance(Request::class, new BasicRequest());
        $container->singleton(Router::class, Router::class);

        $this->register();
    }

    abstract protected function register() : void;

    abstract public function boot() : void;

}