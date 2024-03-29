<?php

namespace Sh1ne\MySqlBot;

use Sh1ne\MySqlBot\Config\AppConfig;
use Sh1ne\MySqlBot\Core\Http\ExceptionHandler as ExceptionHandlerContract;
use Sh1ne\MySqlBot\Core\Http\Response;
use Sh1ne\MySqlBot\Core\Log;
use Throwable;

class ExceptionHandler extends ExceptionHandlerContract
{

    public function handle(Throwable $throwable) : Response
    {
        $context = [
            'file' => $throwable->getFile(),
            'line' => $throwable->getLine(),
            'message' => $throwable->getMessage(),
            'code' => $throwable->getCode(),
            'trace' => $throwable->getTrace(),
        ];

        Log::info("[ExceptionHandler] {$throwable->getMessage()}", $context);

        if (AppConfig::isDebugMode()) {
            $data = $context;
        } else {
            $data = [
                'message' => 'An error occurred',
                'time' => time(),
            ];
        }

        return $this->responseFactory->json($data, 500);
    }

    public function handleShutdown() : Response
    {
        $data = [
            'message' => 'An error occurred',
        ];

        if (AppConfig::isDebugMode()) {
            $data['last_error'] = error_get_last();
        }

        Log::critical('App shut down', error_get_last());

        return $this->responseFactory->json($data, 500);
    }

}