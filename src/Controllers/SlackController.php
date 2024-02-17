<?php

namespace Sh1ne\MySqlBot\Controllers;

use GuzzleHttp\Client;
use Sh1ne\MySqlBot\Core\BasicDbConnection;
use Sh1ne\MySqlBot\Core\Config\AppConfig;
use Sh1ne\MySqlBot\Core\Http\Controller;
use Sh1ne\MySqlBot\Core\Http\Request;
use Sh1ne\MySqlBot\Core\Http\Response;
use Sh1ne\MySqlBot\Domain\Data\AppMention\AppMentionDto;
use Sh1ne\MySqlBot\Domain\Messenger\SlackMessenger;
use Sh1ne\MySqlBot\Domain\Services\BotService;

class SlackController extends Controller
{

    public function handleEvent(Request $request) : Response
    {
        // TODO: determine which DTO to instantiate, as there is single URL for all events
        $appMentionDto = new AppMentionDto($request->body());

        $dbConnection = new BasicDbConnection();

        $client = new Client([
            'base_uri' => 'https://slack.com/api/',
            'headers' => [
                'Authorization' => 'Bearer ' . AppConfig::getSlackApiKey(),
            ],
        ]);

        $messenger = new SlackMessenger($client, $appMentionDto->event->channel, $appMentionDto->event->ts);

        (new BotService($dbConnection, $messenger))->processAppMention($appMentionDto);

        return $this->responseFactory->json([
            'success' => 'true',
        ]);
    }

}