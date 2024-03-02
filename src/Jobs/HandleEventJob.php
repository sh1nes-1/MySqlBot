<?php

namespace Sh1ne\MySqlBot\Jobs;

use Sh1ne\MySqlBot\Core\Database\DbConnection;
use Sh1ne\MySqlBot\Core\Queue\Job;
use Sh1ne\MySqlBot\Data\AppMention\AppMentionDto;
use Sh1ne\MySqlBot\Domain\Messenger\SlackMessenger;
use Sh1ne\MySqlBot\Domain\Services\BotService;

class HandleEventJob extends Job
{

    private array $eventData;

    public function __construct(array $eventData)
    {
        $this->eventData = $eventData;
    }

    public function handle() : void
    {
        // TODO: determine which DTO to instantiate, as there is single URL for all events
        $appMentionDto = new AppMentionDto($this->eventData);

        // TODO: custom facade DB with static methods to prevent creating DbConnection everywhere??
        $dbConnection = app(DbConnection::class);

        $messenger = new SlackMessenger($appMentionDto->event->channel, $appMentionDto->event->ts);

        (new BotService($dbConnection, $messenger))->processAppMention($appMentionDto->event->text);
    }

}