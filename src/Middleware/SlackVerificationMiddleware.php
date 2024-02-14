<?php

namespace Sh1ne\MySqlBot\Middleware;

use Sh1ne\MySqlBot\Core\Http\Middleware;
use Sh1ne\MySqlBot\Core\Http\Request;
use Sh1ne\MySqlBot\Core\Http\Response;

class SlackVerificationMiddleware extends Middleware
{

    public function handle(Request $request) : Response
    {
        if ($request->input('type') === 'url_verification') {
            return $this->responseFactory->json([
                'challenge' => $request->input('challenge'),
            ]);
        }

        return $this->next->handle($request);
    }

}