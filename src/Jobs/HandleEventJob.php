<?php

namespace Sh1ne\MySqlBot\Jobs;

use Exception;
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

    /**
     * @throws Exception
     */
    public function handle() : void
    {
        $this->assertEventType();

        $appMentionDto = new AppMentionDto($this->eventData);

        $messenger = new SlackMessenger($appMentionDto->event->channel, $appMentionDto->event->ts);

        (new BotService($messenger))->processAppMention($appMentionDto->event->text);
    }

    /**
     * @throws Exception
     */
    private function assertEventType() : void
    {
        $eventType = $this->eventData['event']['type'] ?? '<null>';

        if ($eventType !== 'app_mention') {
            throw new Exception("Got unknown event type: $eventType");
        }
    }

}