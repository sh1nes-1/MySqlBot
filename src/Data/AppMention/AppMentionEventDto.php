<?php

namespace Sh1ne\MySqlBot\Data\AppMention;

/**
 * @see AppMentionDto
 */
class AppMentionEventDto
{

    /**
     * @var string $type Example: app_mention
     */
    public readonly string $type;

    /**
     * @var string $user Example: U123ABC456
     */
    public readonly string $user;

    /**
     * @var string $text Example: What is the hour of the pearl, <@U0LAN0Z89>?
     */
    public readonly string $text;

    /**
     * @var string $ts Example: 1515449522.000016
     */
    public readonly string $ts;

    /**
     * @var string $channel Example: C123ABC456
     */
    public readonly string $channel;

    /**
     * @var string $eventTs Example: 1515449522000016
     */
    public readonly string $eventTs;

    public function __construct(array $data)
    {
        $this->type = $data['type'];
        $this->user = $data['user'];
        $this->text = $data['text'];
        $this->ts = $data['ts'];
        $this->channel = $data['channel'];
        $this->eventTs = $data['event_ts'];
    }

}