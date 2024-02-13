<?php

namespace Sh1ne\MySqlBot\Controllers;

use Sh1ne\MySqlBot\Core\Http\Controller;
use Sh1ne\MySqlBot\Core\Http\Request;
use Sh1ne\MySqlBot\Core\Http\Response;
use Sh1ne\MySqlBot\Data\AppMention\AppMentionDto;

class SlackController extends Controller
{

    public function mentionEvent(Request $request) : Response
    {
        $appMentionDto = new AppMentionDto($request->body());

        return $this->json([
            'success' => 'true',
        ]);
    }

}