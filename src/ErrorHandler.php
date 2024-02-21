<?php

namespace Sh1ne\MySqlBot;

use Sh1ne\MySqlBot\Core\Http\Output;
use Sh1ne\MySqlBot\Core\Http\ResponseFactory;
use Sh1ne\MySqlBot\Core\Log;

class ErrorHandler
{

    private Output $output;

    private ResponseFactory $responseFactory;

    public function __construct(Output $output, ResponseFactory $responseFactory)
    {
        $this->output = $output;
        $this->responseFactory = $responseFactory;
    }

    public function disableErrorReporting() : void
    {
        error_reporting(0);
    }

    public function registerShutdownCallback() : void
    {
        register_shutdown_function([$this, 'onShutdown']);
    }

    protected function onShutdown() : void
    {
        $error = error_get_last();

        if (is_null($error)) {
            return;
        }

        Log::critical('App shut down', $error);

        $response = $this->responseFactory->json([
            'message' => 'An error occurred',
        ], 500);

        $this->output->sendResponse($response);
    }

}