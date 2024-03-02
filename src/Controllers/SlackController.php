<?php

namespace Sh1ne\MySqlBot\Controllers;

use Sh1ne\MySqlBot\Core\Config\AppConfig;
use Sh1ne\MySqlBot\Core\Http\Controller;
use Sh1ne\MySqlBot\Core\Http\Request;
use Sh1ne\MySqlBot\Core\Http\Response;
use Sh1ne\MySqlBot\Jobs\HandleEventJob;

class SlackController extends Controller
{

    public function handleEvent(Request $request) : Response
    {
        $job = new HandleEventJob($request->body());

        $job->dispatch()->onQueue(AppConfig::getHandleEventQueueName());

        return $this->responseFactory->json([
            'success' => 'true',
        ]);
    }

}