<?php

namespace Sh1ne\MySqlBot;

use Sh1ne\MySqlBot\Core\Config\AppConfig;
use Sh1ne\MySqlBot\Core\Http\ExceptionHandler as ExceptionHandlerContract;
use Sh1ne\MySqlBot\Core\Http\JsonResponse;
use Sh1ne\MySqlBot\Core\Http\Response;
use Throwable;

class ExceptionHandler implements ExceptionHandlerContract
{

    public function handle(Throwable $throwable) : Response
    {
        if (AppConfig::isDebugMode()) {
            $data = [
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
                'message' => $throwable->getMessage(),
                'code' => $throwable->getCode(),
                'trace' => $throwable->getTrace(),
            ];
        } else {
            $data = [
                'message' => 'An error occurred',
            ];
        }

        return new JsonResponse($data, 422);
    }

}