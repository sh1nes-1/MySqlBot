<?php

namespace Sh1ne\MySqlBot\Middleware;

use Sh1ne\MySqlBot\Config\AppConfig;
use Sh1ne\MySqlBot\Core\Http\Middleware;
use Sh1ne\MySqlBot\Core\Http\Request;
use Sh1ne\MySqlBot\Core\Http\Response;
use Sh1ne\MySqlBot\Core\Log;

class LogRequestsMiddleware extends Middleware
{

    // TODO: change error handler logic to go here too
    public function handle(Request $request) : Response
    {
        $requestData = [
            'body' => $request->body(),
        ];

        if (AppConfig::isDebugMode()) {
            $requestData['headers'] = $request->headers();
        }

        Log::info("Request {$request->method()} {$request->uri()}", $requestData);

        $timeBefore = microtime(true);

        $response = $this->next->handle($request);

        $responseTime = microtime(true) - $timeBefore;

        Log::info("Response", [
            'code' => $response->getStatusCode(),
            'body' => $response->getBodyAsText(),
            'headers' => $response->getHeaders(),
            'response_time_seconds' => $responseTime,
        ]);

        return $response;
    }

}