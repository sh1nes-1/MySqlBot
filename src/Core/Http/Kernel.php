<?php

namespace Sh1ne\MySqlBot\Core\Http;

use Dotenv\Dotenv;
use Sh1ne\MySqlBot\Core\BaseApplication;
use Sh1ne\MySqlBot\Core\ErrorHandler\ErrorHandler;
use Throwable;

class Kernel
{

    protected BaseApplication $application;

    public function __construct(BaseApplication $application)
    {
        $this->application = $application;
    }

    public function boot() : void
    {
        Dotenv::createImmutable($this->application->getBaseDirectory())->safeLoad();

        $this->application->registerServices();

        $this->setupErrorHandler();

        $this->application->boot();
    }

    public function handleRequest() : void
    {
        $request = app(Request::class);

        app(Router::class)->handleRequest($request);
    }

    private function setupErrorHandler() : void
    {
        app(ErrorHandler::class)->setup(fn () => $this->handleShutdown());
    }

    private function handleShutdown() : void
    {
        try {
            $exceptionHandler = app(ExceptionHandler::class);

            $response = $exceptionHandler->handleShutdown();
        } catch (Throwable) {
            $response = app(ResponseFactory::class)->json([
                'message' => 'Fatal error',
            ], 500);
        }

        app(Output::class)->sendResponse($response);
    }

}