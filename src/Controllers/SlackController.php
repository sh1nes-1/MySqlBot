<?php

namespace Sh1ne\MySqlBot\Controllers;

use Sh1ne\MySqlBot\Core\Database\DbConnection;
use Sh1ne\MySqlBot\Core\Http\Controller;
use Sh1ne\MySqlBot\Core\Http\Request;
use Sh1ne\MySqlBot\Core\Http\Response;
use Sh1ne\MySqlBot\Data\AppMention\AppMentionDto;
use Sh1ne\MySqlBot\Domain\Messenger\SlackMessenger;
use Sh1ne\MySqlBot\Domain\Services\BotService;

class SlackController extends Controller
{

    public function handleEvent(Request $request) : Response
    {
        // TODO: handle event in queue, because when we don't respond in 3 seconds, slack will mark it as timeout and repeat
        // TODO: determine which DTO to instantiate, as there is single URL for all events
        $appMentionDto = new AppMentionDto($request->body());

        // TODO: custom facade DB with static methods to prevent creating DbConnection everywhere??
        $dbConnection = app(DbConnection::class);

        $messenger = new SlackMessenger($appMentionDto->event->channel, $appMentionDto->event->ts);

        (new BotService($dbConnection, $messenger))->processAppMention($appMentionDto->event->text);

        return $this->responseFactory->json([
            'success' => 'true',
        ]);
    }

}