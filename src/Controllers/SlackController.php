<?php

namespace Sh1ne\MySqlBot\Controllers;

use Sh1ne\MySqlBot\Config\AppConfig;
use Sh1ne\MySqlBot\Core\Http\Controller;
use Sh1ne\MySqlBot\Core\Http\Request;
use Sh1ne\MySqlBot\Core\Http\Response;
use Sh1ne\MySqlBot\Core\Log;
use Sh1ne\MySqlBot\Jobs\HandleEventJob;

class SlackController extends Controller
{

    public function handleEvent(Request $request) : Response
    {
        $job = new HandleEventJob($request->body());

        $dispatch = $job->dispatch()->onQueue(AppConfig::getHandleEventQueueName());

        Log::info('Dispatched job', [
            'dispatch_id' => $dispatch->getId(),
        ]);

        return $this->responseFactory->json([
            'success' => 'true',
        ]);
    }

}